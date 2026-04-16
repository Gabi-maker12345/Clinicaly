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
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_medico')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_paciente')->constrained('users')->onDelete('cascade');
            
            $table->json('doencas_sugeridas'); 
            $table->json('id_sintomas')->nullable(); 
            $table->json('links_referencia')->nullable();
            $table->text('dados_biometricos')->nullable();
            
            $table->enum('status', ['pendente', 'validado', 'descartado'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
