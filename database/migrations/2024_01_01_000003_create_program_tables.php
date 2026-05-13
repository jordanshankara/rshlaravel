<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price');
            $table->integer('dp_amount');
            $table->integer('quota')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code', 20)->unique();

            // Personal data
            $table->string('full_name', 255);
            $table->date('birth_date');
            $table->string('occupation', 255);
            $table->string('whatsapp', 30);
            $table->text('address');
            $table->string('height_weight', 100);

            // Program
            $table->foreignId('program_period_id')->constrained('program_periods');

            // Health data
            $table->text('health_complaints');
            $table->text('clinical_details');
            $table->float('bmi')->nullable();
            $table->text('emotion_state');
            $table->text('food_allergies');
            $table->text('treatment_history');
            $table->text('current_meds');
            $table->integer('confidence_level');

            // Status & Admin
            $table->enum('status', ['PENDING_PAYMENT', 'CONFIRMED', 'FULLY_PAID', 'CANCELLED'])->default('PENDING_PAYMENT');
            $table->text('payment_note')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->string('sheets_row_id', 50)->nullable();

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();

            $table->index('program_period_id');
            $table->index('status');
        });

        // Add FK from invoices to registrations (added after registrations table)
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('registration_id')->references('id')->on('registrations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
        });
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('program_periods');
    }
};
