<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            if (! Schema::hasColumn('diagnosticos', 'id_doenca')) {
                $table->foreignId('id_doenca')->nullable()->after('id_paciente')->constrained('diseases')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            if (Schema::hasColumn('diagnosticos', 'id_doenca')) {
                $table->dropConstrainedForeignId('id_doenca');
            }
        });
    }
};
