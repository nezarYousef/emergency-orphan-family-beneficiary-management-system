<?php

namespace Tests\Feature;

use App\Models\Family;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_defaults_to_english(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="en" dir="ltr">', false)
            ->assertSeeText('Turn urgent needs into')
            ->assertSeeText('coordinated care.')
            ->assertSee('bootstrap.min.css')
            ->assertDontSee('bootstrap.rtl.min.css');
    }

    public function test_switching_to_arabic_persists_locale_and_renders_rtl_landing(): void
    {
        $this->from('/')->post(route('locale.update'), ['locale' => 'ar'])
            ->assertRedirect('/')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('locale', 'ar');

        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="ar" dir="rtl">', false)
            ->assertSeeText('حوّل الاحتياجات العاجلة إلى')
            ->assertSeeText('رعاية منسّقة.')
            ->assertDontSeeText('Turn urgent needs into')
            ->assertSee('bootstrap.rtl.min.css')
            ->assertDontSee('bootstrap.min.css');
    }

    #[DataProvider('locales')]
    public function test_language_switcher_is_present_on_landing_and_login(string $locale): void
    {
        $this->withSession(['locale' => $locale]);

        foreach (['/', '/login'] as $page) {
            $this->assertLanguageSwitcher($this->get($page)->assertOk(), $locale);
        }
    }

    #[DataProvider('locales')]
    public function test_language_switcher_is_present_on_authenticated_dashboard(string $locale): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->withSession(['locale' => $locale]);

        $this->assertLanguageSwitcher($this->get('/dashboard')->assertOk(), $locale);
        $this->assertAuthenticatedAs($admin);
    }

    public function test_switching_back_to_english_restores_ltr_landing(): void
    {
        $this->withSession(['locale' => 'ar'])->get('/')
            ->assertOk()
            ->assertSeeText('حوّل الاحتياجات العاجلة إلى');

        $this->from('/')->post(route('locale.update'), ['locale' => 'en'])
            ->assertRedirect('/')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('locale', 'en');

        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="en" dir="ltr">', false)
            ->assertSeeText('Turn urgent needs into')
            ->assertSeeText('coordinated care.')
            ->assertDontSeeText('حوّل الاحتياجات العاجلة إلى')
            ->assertSee('bootstrap.min.css')
            ->assertDontSee('bootstrap.rtl.min.css');
    }

    #[DataProvider('locales')]
    public function test_unsupported_locale_is_rejected_without_changing_session(string $locale): void
    {
        $this->withSession(['locale' => $locale])->from('/')
            ->post(route('locale.update'), ['locale' => 'fr'])
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHasErrors('locale')
            ->assertSessionHas('locale', $locale);

        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="'.$locale.'" dir="'.($locale === 'ar' ? 'rtl' : 'ltr').'">', false);
    }

    public function test_missing_locale_is_rejected_without_changing_session(): void
    {
        $this->withSession(['locale' => 'ar'])->from('/')
            ->post(route('locale.update'), [])
            ->assertRedirect('/')
            ->assertSessionHasErrors('locale')
            ->assertSessionHas('locale', 'ar');
    }

    public function test_invalid_session_locale_falls_back_to_english(): void
    {
        $this->withSession(['locale' => 'ar'])->get('/')
            ->assertOk()
            ->assertSee('<html lang="ar" dir="rtl">', false);

        $this->withSession(['locale' => 'fr'])->get('/')
            ->assertOk()
            ->assertSee('<html lang="en" dir="ltr">', false)
            ->assertSeeText('Turn urgent needs into')
            ->assertSeeText('coordinated care.')
            ->assertDontSeeText('حوّل الاحتياجات العاجلة إلى')
            ->assertSee('bootstrap.min.css')
            ->assertDontSee('bootstrap.rtl.min.css');
    }

    #[DataProvider('locales')]
    public function test_viewer_cannot_open_write_forms_users_or_audit_logs_in_either_locale(string $locale): void
    {
        $viewer = User::factory()->create(['role' => 'viewer']);
        $this->actingAs($viewer)->withSession(['locale' => $locale]);

        $this->get('/families')->assertOk();
        $this->get('/families/create')->assertForbidden();
        $this->get('/beneficiaries/create')->assertForbidden();
        $this->get('/orphans/create')->assertForbidden();
        $this->get('/aid-distributions/create')->assertForbidden();
        $this->get('/users/create')->assertForbidden();
        $this->get('/users')->assertForbidden();
        $this->get('/audit-logs')->assertForbidden();
        $this->assertAuthenticatedAs($viewer);
    }

    #[DataProvider('locales')]
    public function test_admin_can_open_users_and_audit_logs_in_either_locale(string $locale): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->withSession(['locale' => $locale]);

        $this->get('/users')->assertOk();
        $this->get('/audit-logs')->assertOk();
        $this->assertAuthenticatedAs($admin);
    }

    #[DataProvider('locales')]
    public function test_data_entry_can_persist_and_search_arabic_family_data_in_either_locale(string $locale): void
    {
        $dataEntry = User::factory()->create(['role' => 'data_entry']);
        $this->actingAs($dataEntry)->withSession(['locale' => $locale]);

        $this->get('/users')->assertForbidden();
        $this->get('/audit-logs')->assertForbidden();
        $this->get('/families/create')->assertOk();

        $this->post('/families', [
            'head_of_household_name' => 'أحمد محمود',
            'phone' => '0599000001',
            'governorate' => 'Gaza',
            'area' => 'الرمال',
            'family_size' => 4,
            'provider_status' => 'no_provider',
            'vulnerability_status' => 'high',
        ])->assertSessionHasNoErrors()->assertRedirect('/families');

        $family = Family::where('head_of_household_name', 'أحمد محمود')->firstOrFail();
        $this->assertSame($dataEntry->id, $family->created_by);
        $this->assertDatabaseHas('families', [
            'id' => $family->id,
            'head_of_household_name' => 'أحمد محمود',
            'area' => 'الرمال',
            'governorate' => 'Gaza',
            'provider_status' => 'no_provider',
            'vulnerability_status' => 'high',
            'created_by' => $dataEntry->id,
        ]);

        $this->get('/families?'.http_build_query(['search' => 'أحمد']))
            ->assertOk()
            ->assertSee('<td>أحمد محمود</td>', false)
            ->assertSee('<td>الرمال</td>', false)
            ->assertSeeText($family->case_number)
            ->assertViewHas('families', fn ($families) => $families->total() === 1 && $families->first()->id === $family->id);

        $this->get('/families?'.http_build_query(['search' => 'اسم غير موجود']))
            ->assertOk()
            ->assertDontSeeText('أحمد محمود')
            ->assertViewHas('families', fn ($families) => $families->total() === 0);
    }

    public function test_family_validation_errors_render_in_arabic(): void
    {
        $dataEntry = User::factory()->create(['role' => 'data_entry']);
        $this->actingAs($dataEntry)->withSession(['locale' => 'ar']);
        $this->get('/families/create')->assertOk();

        $this->from('/families/create')->post('/families', [])
            ->assertRedirect('/families/create')
            ->assertSessionHasErrors([
                'head_of_household_name' => 'حقل اسم رب الأسرة مطلوب.',
                'governorate' => 'حقل المحافظة مطلوب.',
                'area' => 'حقل المنطقة مطلوب.',
                'family_size' => 'حقل عدد أفراد الأسرة مطلوب.',
                'provider_status' => 'حقل حالة المعيل مطلوب.',
                'vulnerability_status' => 'حقل درجة الهشاشة مطلوب.',
            ]);

        $this->withCookie(config('session.cookie'), session()->getId())->get('/families/create')
            ->assertOk()
            ->assertSee('<html lang="ar" dir="rtl">', false)
            ->assertSeeText('حقل اسم رب الأسرة مطلوب.')
            ->assertSeeText('حقل المحافظة مطلوب.')
            ->assertSeeText('حقل المنطقة مطلوب.')
            ->assertSeeText('حقل عدد أفراد الأسرة مطلوب.')
            ->assertSeeText('حقل حالة المعيل مطلوب.')
            ->assertSeeText('حقل درجة الهشاشة مطلوب.');

        $this->assertDatabaseCount('families', 0);
    }

    public function test_authenticated_language_switch_returns_to_same_page_and_preserves_login(): void
    {
        $user = User::factory()->create(['role' => 'data_entry']);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->withCookie(config('session.cookie'), session()->getId());

        foreach (['ar' => 'rtl', 'en' => 'ltr'] as $locale => $direction) {
            $this->app['auth']->forgetGuards();
            $this->get('/families')->assertOk();

            $this->from('/families')->post(route('locale.update'), ['locale' => $locale])
                ->assertRedirect('/families')
                ->assertSessionHasNoErrors()
                ->assertSessionHas('locale', $locale);

            $this->app['auth']->forgetGuards();
            $this->get('/families')
                ->assertOk()
                ->assertSee('<html lang="'.$locale.'" dir="'.$direction.'">', false);
            $this->assertAuthenticatedAs($user);
        }
    }

    public static function locales(): array
    {
        return [
            'English' => ['en'],
            'Arabic' => ['ar'],
        ];
    }

    private function assertLanguageSwitcher(TestResponse $response, string $locale): void
    {
        $targetLocale = $locale === 'ar' ? 'en' : 'ar';

        $response->assertSee('<form method="post" action="'.route('locale.update').'" class="language-switcher"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('<input type="hidden" name="locale" value="'.$targetLocale.'">', false)
            ->assertSeeText($targetLocale === 'ar' ? 'العربية' : 'English');
    }
}
