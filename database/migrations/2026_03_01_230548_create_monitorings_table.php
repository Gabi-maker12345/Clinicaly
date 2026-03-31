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
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('disease_id')->constrained();

            $table->foreignId('medication_id')->nullable()->constrained();
            
            $table->date('start_date');
            $table->date('finish_date')->nullable(); 
            
            // Intervalo em horas (8 para "8 em 8 horas")
            $table->integer('interval_hours')->nullable(); 
            
            // Próxima notificação (crucial para o sistema saber quando avisar o user)
            $table->dateTime('next_notification_at')->nullable();
            
            $table->enum('status', ['active', 'completed', 'paused'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};
