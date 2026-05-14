<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitorings', function (Blueprint $table) {
            if (! Schema::hasColumn('monitorings', 'duration_days')) {
                $table->integer('duration_days')->nullable()->after('interval_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monitorings', function (Blueprint $table) {
            if (Schema::hasColumn('monitorings', 'duration_days')) {
                $table->dropColumn('duration_days');
            }
        });
    }
};
