<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit', 30)->default('unidade');
            $table->integer('quantity')->default(0);
            $table->integer('minimum_quantity')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'name']);
        });

        Schema::create('clinic_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clinic_stock_item_id')->constrained('clinic_stock_items')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['entrada', 'saida', 'ajuste'])->default('saida');
            $table->integer('quantity');
            $table->integer('balance_after')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_stock_movements');
        Schema::dropIfExists('clinic_stock_items');
    }
};
