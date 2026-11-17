<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('biometric_data', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assessed_at')->index();
            $table->decimal('height_cm', 5, 2);
            $table->decimal('weight_kg', 5, 2);
            $table->decimal('bmi', 5, 2);
            $table->unsignedSmallInteger('systolic')->nullable();
            $table->unsignedSmallInteger('diastolic')->nullable();
            $table->decimal('fasting_glucose_mgdl', 6, 2)->nullable();
            $table->unsignedSmallInteger('resting_heart_rate')->nullable();
            $table->timestamps();
        });

        Schema::create('lifestyle_surveys', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('biometric_data_id')->constrained('biometric_data')->cascadeOnDelete();
            $table->boolean('smoker')->default(false);
            $table->boolean('heavy_alcohol')->default(false);
            $table->boolean('phys_activity')->default(false);
            $table->boolean('fruit')->default(false);
            $table->boolean('veggies')->default(false);
            $table->unsignedTinyInteger('sleep_hours')->nullable();
            $table->unsignedTinyInteger('stress_level')->nullable();
            $table->boolean('family_history')->default(false);
            $table->boolean('stroke')->default(false);
            $table->json('diagnosed_conditions')->nullable();
            $table->timestamps();
        });

        Schema::create('risk_assessments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('biometric_data_id')->constrained('biometric_data')->cascadeOnDelete();
            $table->string('engine', 20);
            $table->decimal('overall_score', 5, 2);
            $table->string('overall_level', 20);
            $table->json('condition_results');
            $table->json('risk_factors')->nullable();
            $table->timestamp('assessed_at')->index();
            $table->timestamps();
        });

        Schema::create('risk_rules', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->json('applies_to');
            $table->string('fact_key');
            $table->string('operator', 20);
            $table->decimal('threshold', 8, 2)->nullable();
            $table->string('severity', 20);
            $table->unsignedSmallInteger('points')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('recommendation_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('body');
            $table->text('rationale');
            $table->string('priority', 20);
            $table->string('category', 30);
            $table->string('goal_metric')->nullable();
            $table->json('trigger_codes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('preventive_recommendations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained()->cascadeOnDelete();
            $table->string('template_code');
            $table->string('title');
            $table->text('advice');
            $table->text('rationale');
            $table->string('priority', 20);
            $table->string('status', 20)->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('health_goals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('metric', 40);
            $table->decimal('target', 8, 2);
            $table->decimal('start_value', 8, 2)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('achieved_at')->nullable();
            $table->timestamp('abandoned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('wearable_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('source', 20);
            $table->date('session_date')->index();
            $table->unsignedSmallInteger('average_heart_rate')->nullable();
            $table->unsignedInteger('steps')->nullable();
            $table->decimal('sleep_hours', 4, 2)->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });

        Schema::create('report_shares', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('risk_assessment_id')->constrained()->cascadeOnDelete();
            $table->string('token', 96)->unique();
            $table->string('provider_email')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('revoked_at')->nullable();
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('usability_feedback', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('ease_of_use')->nullable();
            $table->unsignedTinyInteger('usefulness')->nullable();
            $table->unsignedTinyInteger('confidence')->nullable();
            $table->unsignedTinyInteger('intention')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('submitted_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usability_feedback');
        Schema::dropIfExists('report_shares');
        Schema::dropIfExists('wearable_sessions');
        Schema::dropIfExists('health_goals');
        Schema::dropIfExists('preventive_recommendations');
        Schema::dropIfExists('recommendation_templates');
        Schema::dropIfExists('risk_rules');
        Schema::dropIfExists('risk_assessments');
        Schema::dropIfExists('lifestyle_surveys');
        Schema::dropIfExists('biometric_data');
    }
};
