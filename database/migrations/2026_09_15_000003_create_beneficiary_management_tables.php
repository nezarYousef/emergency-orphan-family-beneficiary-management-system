<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('role')->default('viewer')->index());
        Schema::create('families', function (Blueprint $t) {
            $t->id();
            $t->string('case_number')->unique();
            $t->string('head_of_household_name');
            $t->string('national_id')->nullable()->index();
            $t->string('phone', 40);
            $t->string('alternative_phone', 40)->nullable();
            $t->string('governorate')->index();
            $t->string('area')->index();
            $t->text('address')->nullable();
            $t->unsignedInteger('family_size');
            $t->string('provider_status')->index();
            $t->string('housing_status')->nullable();
            $t->string('vulnerability_status')->index();
            $t->text('notes')->nullable();
            $t->foreignId('created_by')->constrained('users');
            $t->foreignId('updated_by')->nullable()->constrained('users');
            $t->softDeletes();
            $t->timestamps();
        });
        Schema::create('beneficiaries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('family_id')->constrained()->restrictOnDelete();
            $t->string('beneficiary_number')->unique();
            $t->string('full_name');
            $t->string('national_id')->nullable()->index();
            $t->string('gender');
            $t->date('date_of_birth');
            $t->string('relationship_to_head');
            $t->string('beneficiary_type')->index();
            $t->text('health_status')->nullable();
            $t->boolean('disability_status')->default(false);
            $t->string('education_status')->nullable();
            $t->text('notes')->nullable();
            $t->foreignId('created_by')->constrained('users');
            $t->foreignId('updated_by')->nullable()->constrained('users');
            $t->softDeletes();
            $t->timestamps();
        });
        Schema::create('orphans', function (Blueprint $t) {
            $t->id();
            $t->foreignId('beneficiary_id')->unique()->constrained()->restrictOnDelete();
            $t->foreignId('family_id')->constrained()->restrictOnDelete();
            $t->string('orphan_number')->unique();
            $t->string('orphan_status');
            $t->string('father_status');
            $t->string('mother_status');
            $t->string('guardian_name')->nullable();
            $t->string('guardian_relationship')->nullable();
            $t->string('school_status')->nullable();
            $t->string('sponsorship_status')->index();
            $t->text('notes')->nullable();
            $t->foreignId('created_by')->constrained('users');
            $t->foreignId('updated_by')->nullable()->constrained('users');
            $t->softDeletes();
            $t->timestamps();
        });
        Schema::create('aid_distributions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('family_id')->constrained()->restrictOnDelete();
            $t->foreignId('beneficiary_id')->nullable()->constrained()->nullOnDelete();
            $t->string('aid_type')->index();
            $t->date('distribution_date')->index();
            $t->decimal('quantity', 12, 2)->nullable();
            $t->decimal('amount', 12, 2)->nullable();
            $t->string('currency', 3)->nullable();
            $t->string('provider_organization')->nullable();
            $t->string('reference_number')->nullable()->index();
            $t->text('notes')->nullable();
            $t->foreignId('created_by')->constrained('users');
            $t->foreignId('updated_by')->nullable()->constrained('users');
            $t->timestamps();
        });
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->nullOnDelete();
            $t->string('action')->index();
            $t->string('model_type')->index();
            $t->unsignedBigInteger('model_id');
            $t->text('description');
            $t->json('old_values')->nullable();
            $t->json('new_values')->nullable();
            $t->ipAddress('ip_address')->nullable();
            $t->timestamp('created_at')->useCurrent();
            $t->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('aid_distributions');
        Schema::dropIfExists('orphans');
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('families');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
