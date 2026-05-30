<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->enum('type', ['wa', 'email']);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('contacted_at')->useCurrent();

            $table->index(['contact_id', 'contacted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_logs');
    }
};
