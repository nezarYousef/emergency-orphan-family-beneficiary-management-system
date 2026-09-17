<?php

namespace Tests\Feature;

use App\Http\Requests\AidDistributionRequest;
use App\Http\Requests\OrphanRequest;
use App\Models\AidDistribution;
use App\Models\Beneficiary;
use App\Models\Family;
use App\Models\Orphan;
use App\Models\User;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LocalizedFormsTest extends TestCase
{
    use RefreshDatabase;

    private Family $family;

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['role' => 'data_entry']);
        $this->actingAs($user);
        $this->family = Family::create([
            'case_number' => 'FAM-FORMS-001',
            'head_of_household_name' => 'Test Household',
            'phone' => '0599000001',
            'governorate' => 'Gaza',
            'area' => 'Test Area',
            'family_size' => 3,
            'provider_status' => 'no_provider',
            'vulnerability_status' => 'high',
            'created_by' => $user->id,
        ]);
        $this->beneficiary = Beneficiary::create([
            'family_id' => $this->family->id,
            'beneficiary_number' => 'BEN-FORMS-001',
            'full_name' => 'Test Child',
            'gender' => 'female',
            'date_of_birth' => '2015-01-01',
            'relationship_to_head' => 'child',
            'beneficiary_type' => 'child',
            'created_by' => $user->id,
        ]);
    }

    #[DataProvider('locales')]
    public function test_create_forms_offer_all_canonical_values_with_localized_labels(string $locale): void
    {
        $this->withSession(['locale' => $locale]);
        $orphan = $this->get('/orphans/create')->assertOk();

        foreach ($this->orphanEnums() as $field => [$group, $values]) {
            $this->assertSelect($orphan, $field, $group, $values, '', $locale);
        }
        foreach (['guardian_name', 'guardian_relationship', 'school_status'] as $field) {
            $this->assertSame('input', $this->control($orphan, $field)->tagName);
        }

        $aid = $this->get('/aid-distributions/create')->assertOk();
        $this->assertSelect($aid, 'aid_type', 'aid', AidDistributionRequest::AID_TYPES, '', $locale);
        $this->assertSame('date', $this->control($aid, 'distribution_date')->getAttribute('type'));
        $this->assertSame(now()->format('Y-m-d'), $this->control($aid, 'distribution_date')->getAttribute('value'));
    }

    #[DataProvider('locales')]
    public function test_aid_edit_formats_carbon_date_and_preserves_old_input(string $locale): void
    {
        $this->withSession(['locale' => $locale]);
        $this->post('/aid-distributions', $this->aidData())->assertSessionHasNoErrors();
        $distribution = AidDistribution::firstOrFail();
        $url = '/aid-distributions/'.$distribution->id.'/edit';
        $response = $this->get($url)->assertOk();
        $this->assertSame('2026-09-10', $this->control($response, 'distribution_date')->getAttribute('value'));
        $this->assertSelect($response, 'aid_type', 'aid', AidDistributionRequest::AID_TYPES, 'cash', $locale);

        foreach (['2026-09-12', '', 'not-a-date'] as $date) {
            $this->withSession(['_old_input' => ['distribution_date' => $date, 'aid_type' => 'hygiene']]);
            $response = $this->get($url)->assertOk();
            $this->assertSame($date, $this->control($response, 'distribution_date')->getAttribute('value'));
            $this->assertSelect($response, 'aid_type', 'aid', AidDistributionRequest::AID_TYPES, 'hygiene', $locale);
        }
        $this->assertSame('2026-09-10', $distribution->fresh()->distribution_date->format('Y-m-d'));
        $this->assertSame('cash', $distribution->fresh()->aid_type);
    }

    #[DataProvider('locales')]
    public function test_orphan_edit_selections_and_validation_redirect_preserve_canonical_old_input(string $locale): void
    {
        $this->withSession(['locale' => $locale]);
        $data = $this->orphanData();
        $this->post('/orphans', $data)->assertSessionHasNoErrors()->assertRedirect('/orphans');
        $orphan = Orphan::firstOrFail();
        $url = '/orphans/'.$orphan->id.'/edit';
        $response = $this->get($url)->assertOk();
        foreach ($this->orphanEnums() as $field => [$group, $values]) {
            $this->assertSelect($response, $field, $group, $values, $data[$field], $locale);
        }

        $old = array_replace($data, [
            'orphan_status' => 'active',
            'father_status' => 'missing',
            'mother_status' => 'deceased',
            'sponsorship_status' => 'pending',
            'guardian_name' => str_repeat('a', 256),
        ]);
        $this->from($url)->put('/orphans/'.$orphan->id, $old)
            ->assertRedirect($url)->assertSessionHasErrors('guardian_name');
        $response = $this->withCookie(config('session.cookie'), session()->getId())->get($url)->assertOk();
        foreach ($this->orphanEnums() as $field => [$group, $values]) {
            $this->assertSelect($response, $field, $group, $values, $old[$field], $locale);
        }
        $this->assertDatabaseHas('orphans', ['id' => $orphan->id, ...$data]);
    }

    #[DataProvider('locales')]
    public function test_every_supported_enum_can_be_stored_and_updated_without_translating_values(string $locale): void
    {
        $this->withSession(['locale' => $locale]);
        $data = $this->orphanData();
        $this->post('/orphans', $data)->assertSessionHasNoErrors()->assertRedirect('/orphans');
        $orphan = Orphan::firstOrFail();

        foreach ($this->orphanEnums() as $field => [$group, $values]) {
            foreach ($values as $value) {
                $data[$field] = $value;
                $this->put('/orphans/'.$orphan->id, $data)
                    ->assertSessionHasNoErrors()->assertRedirect('/orphans/'.$orphan->id);
                $this->assertDatabaseHas('orphans', ['id' => $orphan->id, ...$data]);
            }
        }

        foreach (AidDistributionRequest::AID_TYPES as $type) {
            $aid = array_replace($this->aidData(), ['aid_type' => $type]);
            $this->post('/aid-distributions', $aid)
                ->assertSessionHasNoErrors()->assertRedirect('/aid-distributions');
            $distribution = AidDistribution::latest('id')->firstOrFail();
            $this->assertSame($type, $distribution->aid_type);
            $this->put('/aid-distributions/'.$distribution->id, $aid)
                ->assertSessionHasNoErrors()->assertRedirect('/aid-distributions/'.$distribution->id);
            $this->assertSame($type, $distribution->fresh()->aid_type);
            $this->assertSame('2026-09-10', $distribution->fresh()->distribution_date->format('Y-m-d'));
        }
    }

    #[DataProvider('locales')]
    public function test_noncanonical_enum_values_are_rejected_on_create_and_update(string $locale): void
    {
        $this->withSession(['locale' => $locale]);
        foreach (['invented', 'يتيم الأب', ['paternal'], 1, ''] as $invalid) {
            $data = array_replace($this->orphanData(), array_fill_keys(array_keys($this->orphanEnums()), $invalid));
            $this->post('/orphans', $data)->assertSessionHasErrors(array_keys($this->orphanEnums()));
        }
        $this->assertDatabaseCount('orphans', 0);
        $this->post('/orphans', $this->orphanData())->assertSessionHasNoErrors();
        $orphan = Orphan::firstOrFail();
        $this->put('/orphans/'.$orphan->id, array_replace($this->orphanData(), [
            'orphan_status' => 'invented', 'father_status' => 'invented',
            'mother_status' => 'invented', 'sponsorship_status' => 'invented',
        ]))->assertSessionHasErrors(array_keys($this->orphanEnums()));
        $this->assertDatabaseHas('orphans', ['id' => $orphan->id, ...$this->orphanData()]);

        foreach (['invented', 'مساعدة نقدية', ['cash'], 1, ''] as $invalid) {
            $this->post('/aid-distributions', array_replace($this->aidData(), ['aid_type' => $invalid]))
                ->assertSessionHasErrors('aid_type');
        }
        $this->assertDatabaseCount('aid_distributions', 0);
        $this->post('/aid-distributions', $this->aidData())->assertSessionHasNoErrors();
        $distribution = AidDistribution::firstOrFail();
        $this->put('/aid-distributions/'.$distribution->id, array_replace($this->aidData(), ['aid_type' => 'invented']))
            ->assertSessionHasErrors('aid_type');
        $this->assertSame('cash', $distribution->fresh()->aid_type);
    }

    public static function locales(): array
    {
        return ['English' => ['en'], 'Arabic' => ['ar']];
    }

    private function orphanEnums(): array
    {
        return [
            'orphan_status' => ['orphan', OrphanRequest::ORPHAN_STATUSES],
            'father_status' => ['parent', OrphanRequest::PARENT_STATUSES],
            'mother_status' => ['parent', OrphanRequest::PARENT_STATUSES],
            'sponsorship_status' => ['sponsorship', OrphanRequest::SPONSORSHIP_STATUSES],
        ];
    }

    private function orphanData(): array
    {
        return [
            'family_id' => $this->family->id,
            'beneficiary_id' => $this->beneficiary->id,
            'orphan_status' => 'paternal',
            'father_status' => 'deceased',
            'mother_status' => 'alive',
            'sponsorship_status' => 'not_sponsored',
            'guardian_name' => 'فاطمة أحمد',
            'guardian_relationship' => 'وصية قانونية',
            'school_status' => 'تعليم منزلي جزئي',
        ];
    }

    private function aidData(): array
    {
        return [
            'family_id' => $this->family->id,
            'aid_type' => 'cash',
            'distribution_date' => '2026-09-10',
            'quantity' => '2.50',
            'amount' => '125.75',
            'currency' => 'USD',
            'provider_organization' => 'جمعية محلية',
        ];
    }

    private function control(TestResponse $response, string $field): DOMElement
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
        $nodes = (new DOMXPath($document))->query('//*[@name="'.$field.'"]');
        $this->assertSame(1, $nodes->length);

        return $nodes->item(0);
    }

    private function assertSelect(TestResponse $response, string $field, string $group, array $values, string $selected, string $locale): void
    {
        $select = $this->control($response, $field);
        $this->assertSame('select', $select->tagName);
        $this->assertTrue($select->hasAttribute('required'));
        $actual = [];
        $selectedValues = [];
        foreach ($select->getElementsByTagName('option') as $option) {
            $value = $option->getAttribute('value');
            if ($option->hasAttribute('selected')) {
                $selectedValues[] = $value;
            }
            if ($value !== '') {
                $actual[] = $value;
                $this->assertSame(__('statuses.'.$group.'.'.$value, [], $locale), $option->textContent);
            }
        }
        $this->assertSame(array_keys(__('statuses.'.$group, [], 'en')), $actual);
        $this->assertSame($values, $actual);
        $this->assertSame([$selected], $selectedValues);
    }
}
