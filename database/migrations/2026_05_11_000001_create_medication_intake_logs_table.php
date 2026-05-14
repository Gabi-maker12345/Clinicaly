<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_intake_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->dateTime('notified_at')->nullable();
            $table->dateTime('due_until')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->enum('status', ['pending', 'completed', 'missed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_intake_logs');
    }
};
