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
            $table->json('alertas_criticos')->after('dados_biometricos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            $table->dropColumn('alertas_criticos');
        });
    }
};
