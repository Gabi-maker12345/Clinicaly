<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'clinic_id')) {
                $table->foreignId('clinic_id')->nullable()->after('role')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('users', 'specialty')) {
                $table->string('specialty')->nullable()->after('clinic_id');
            }

            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 40)->nullable()->after('specialty');
            }

            if (! Schema::hasColumn('users', 'activity_hours')) {
                $table->string('activity_hours', 80)->nullable()->after('phone');
            }

            if (! Schema::hasColumn('users', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('activity_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['is_available', 'activity_hours', 'phone', 'specialty'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('users', 'clinic_id')) {
                $table->dropConstrainedForeignId('clinic_id');
            }
        });
    }
};
