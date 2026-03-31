<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diseases', function (Blueprint $table) {
            $table->integer('min_age')->default(0)->after('name'); 
            $table->integer('max_age')->default(120)->after('min_age');
            $table->string('target_gender')->default('both')->after('max_age');
            $table->integer('severity')->default(1)->after('target_gender');
        });
    }

    public function down(): void
    {
        Schema::table('diseases', function (Blueprint $table) {
            $table->dropColumn(['min_age', 'max_age', 'target_gender', 'severity']);
        });
    }
};