<?php

namespace Database\Seeders;

use App\Models\AidDistribution;
use App\Models\Beneficiary;
use App\Models\Family;
use App\Models\Orphan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([['Admin', 'admin@example.com', 'admin'], ['Data Entry', 'dataentry@example.com', 'data_entry'], ['Viewer', 'viewer@example.com', 'viewer']] as [$name,$email,$role]) {
            User::updateOrCreate(['email' => $email], ['name' => $name, 'role' => $role, 'is_active' => true, 'password' => bcrypt('password')]);
        }
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $governorates = ['North Gaza', 'Gaza', 'Deir al-Balah', 'Khan Younis', 'Rafah'];
        $providers = ['has_provider', 'no_provider', 'deceased_provider', 'missing_provider', 'disabled_provider'];
        $vulnerabilities = ['low', 'medium', 'high', 'critical'];
        for ($i = 1; $i <= 30; $i++) {
            $f = Family::updateOrCreate(['case_number' => sprintf('FAM-%d-%04d', now()->year, $i)], ['head_of_household_name' => 'Fictional Household '.$i, 'phone' => '059900'.str_pad((string) $i, 4, '0', STR_PAD_LEFT), 'governorate' => $governorates[$i % 5], 'area' => 'Community Area '.(($i % 6) + 1), 'family_size' => 3 + ($i % 6), 'provider_status' => $providers[$i % 5], 'vulnerability_status' => $vulnerabilities[$i % 4], 'created_by' => $admin->id]);
            for ($j = 1; $j <= 4; $j++) {
                $n = (($i - 1) * 4) + $j;
                Beneficiary::updateOrCreate(['beneficiary_number' => sprintf('BEN-%d-%04d', now()->year, $n)], ['family_id' => $f->id, 'full_name' => 'Fictional Beneficiary '.$n, 'gender' => $j % 2 ? 'female' : 'male', 'date_of_birth' => Carbon::now()->subYears(4 + $j + $i % 12)->toDateString(), 'relationship_to_head' => $j === 1 ? 'child' : 'family member', 'beneficiary_type' => $j === 1 ? 'child' : ($j === 2 ? 'adult' : 'other'), 'disability_status' => $n % 11 === 0, 'created_by' => $admin->id]);
            }
        }
        $beneficiaries = Beneficiary::orderBy('id')->get();
        foreach ($beneficiaries->take(30) as $idx => $b) {
            Orphan::updateOrCreate(['beneficiary_id' => $b->id], ['family_id' => $b->family_id, 'orphan_number' => sprintf('ORP-%d-%04d', now()->year, $idx + 1), 'orphan_status' => $idx % 3 === 0 ? 'double_orphan' : ($idx % 2 ? 'paternal' : 'maternal'), 'father_status' => $idx % 2 ? 'deceased' : 'missing', 'mother_status' => $idx % 3 ? 'alive' : 'deceased', 'guardian_name' => 'Fictional Guardian '.($idx + 1), 'guardian_relationship' => 'relative', 'school_status' => $idx % 2 ? 'enrolled' : 'needs follow-up', 'sponsorship_status' => ['sponsored', 'not_sponsored', 'pending'][$idx % 3], 'created_by' => $admin->id]);
        }
        for ($i = 1; $i <= 60; $i++) {
            $f = Family::orderBy('id')->skip(($i - 1) % 30)->first();
            AidDistribution::updateOrCreate(['reference_number' => 'AID-DEMO-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT)], ['family_id' => $f->id, 'aid_type' => ['cash', 'food', 'medical', 'education', 'hygiene'][$i % 5], 'distribution_date' => Carbon::now()->subDays($i)->toDateString(), 'quantity' => $i % 3 ? 2 : 1, 'amount' => $i % 3 ? null : 100 + $i, 'currency' => 'USD', 'provider_organization' => 'Fictional Relief Organization', 'created_by' => $admin->id]);
        }
    }
}
