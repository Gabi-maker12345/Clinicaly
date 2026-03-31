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
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->foreignId('chat_session_id')->after('user_id')->nullable()->constrained('chat_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down()
    {
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->dropForeign(['chat_session_id']);
            $table->dropColumn('chat_session_id');
        });
    }
};
