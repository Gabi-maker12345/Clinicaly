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
               
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostico_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('finish_date');
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });

        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->string('medication_name');
            $table->integer('interval_hours');
            $table->dateTime('next_notification_at')->nullable();
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitorings, prescriptions');
    }
};
