<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('diagnostico_id')->nullable()->constrained('diagnosticos')->nullOnDelete();
            $table->dateTime('scheduled_for');
            $table->enum('consultation_type', ['routine', 'follow_up'])->default('follow_up');
            $table->enum('mode', ['presencial', 'telemedicina'])->default('presencial');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('doctor_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'status', 'scheduled_for']);
            $table->index(['patient_id', 'status', 'scheduled_for']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};
