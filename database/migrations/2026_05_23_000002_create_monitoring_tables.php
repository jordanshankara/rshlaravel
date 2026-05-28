<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->tinyInteger('day_number'); // 1–7
            $table->string('token', 64)->unique();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['registration_id', 'day_number']);
            $table->index('token');
        });

        Schema::create('monitoring_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_token_id')
                ->constrained('monitoring_tokens')
                ->cascadeOnDelete();
            $table->enum('category', ['EMOSI', 'FISIK']);
            $table->tinyInteger('question_number'); // 1–8
            $table->tinyInteger('answer');          // 0=red, 1=yellow, 2=green

            $table->unique(['monitoring_token_id', 'category', 'question_number'], 'unique_response');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_responses');
        Schema::dropIfExists('monitoring_tokens');
    }
};
