<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_active')->default(true)->index();
        });

        Schema::table('families', function (Blueprint $table): void {
            $table->unique('national_id', 'families_national_id_unique');
        });

        Schema::table('beneficiaries', function (Blueprint $table): void {
            $table->unique('national_id', 'beneficiaries_national_id_unique');
        });

        Schema::table('aid_distributions', function (Blueprint $table): void {
            $table->unique('reference_number', 'aid_distributions_reference_number_unique');
            $table->foreignId('family_id')->nullable()->change();
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE aid_distributions ADD CONSTRAINT aid_distributions_subject_check CHECK (family_id IS NOT NULL OR beneficiary_id IS NOT NULL)');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE aid_distributions DROP CONSTRAINT IF EXISTS aid_distributions_subject_check');
        }

        Schema::table('aid_distributions', function (Blueprint $table): void {
            $table->dropUnique('aid_distributions_reference_number_unique');
            $table->foreignId('family_id')->nullable(false)->change();
        });
        Schema::table('beneficiaries', fn (Blueprint $table) => $table->dropUnique('beneficiaries_national_id_unique'));
        Schema::table('families', fn (Blueprint $table) => $table->dropUnique('families_national_id_unique'));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_active'));
    }
};
