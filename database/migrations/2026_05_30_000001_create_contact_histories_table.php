<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_histories', function (Blueprint $table) {
            $table->id();
            $table->enum('action_type', [
                'import', 'create', 'edit', 'bulk_edit', 'delete', 'bulk_delete'
            ]);
            $table->string('description', 255);
            $table->integer('affected_count')->default(0);
            $table->longText('snapshot'); // JSON
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_histories');
    }
};
