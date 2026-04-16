<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            $table->string('doenca_final')->after('doencas_sugeridas')->nullable();
            $table->json('parecer_medico')->after('doenca_final')->nullable();
        });
    }

    public function down()
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            $table->dropColumn(['doenca_final', 'parecer_medico']);
        });
    }
};
