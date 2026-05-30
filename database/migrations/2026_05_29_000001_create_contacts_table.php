<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('phone', 30)->unique();
            $table->string('email', 255)->nullable();
            $table->string('gender', 20)->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('age')->unsigned()->nullable();
            $table->text('health_complaint')->nullable();
            $table->string('info_source', 255)->nullable();
            $table->string('source_file', 100)->nullable();
            $table->text('notes')->nullable();
            // Cached from latest contact_log for fast display
            $table->timestamp('last_contacted_at')->nullable();
            $table->enum('last_contacted_type', ['wa', 'email'])->nullable();
            $table->timestamps();

            $table->index('last_contacted_at');
            $table->index('source_file');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
