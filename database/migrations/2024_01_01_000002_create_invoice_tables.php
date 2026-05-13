<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 100);
            $table->string('account_number', 50);
            $table->string('account_name', 255);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('category', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->string('client_name', 255);
            $table->date('invoice_date');
            $table->enum('payment_status', ['LUNAS', 'BELUM_LUNAS', 'DIBATALKAN'])->default('BELUM_LUNAS');
            $table->decimal('total_amount', 15, 2);
            $table->text('notes')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('payment_detail_id')->nullable()->constrained('payment_details')->nullOnDelete();
            $table->unsignedBigInteger('registration_id')->nullable()->unique();
            $table->boolean('is_system_generated')->default(false);
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->text('description');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 5, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_products');
        Schema::dropIfExists('payment_details');
    }
};
