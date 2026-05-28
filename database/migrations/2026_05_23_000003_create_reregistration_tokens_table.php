<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reregistration_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->timestamp('used_at')->nullable();
            $table->foreignId('new_registration_id')
                ->nullable()
                ->constrained('registrations')
                ->nullOnDelete();
            $table->timestamp('expires_at');
            $table->timestamp('created_at')->useCurrent();

            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reregistration_tokens');
    }
};
