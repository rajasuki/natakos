<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kos_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('kos_profiles', 'late_fee_per_day')) {
                $table->integer('late_fee_per_day')->default(0)->after('logo');
            }
            if (! Schema::hasColumn('kos_profiles', 'max_late_fee')) {
                $table->integer('max_late_fee')->nullable()->after('late_fee_per_day');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kos_profiles', function (Blueprint $table) {
            $table->dropColumn(['late_fee_per_day', 'max_late_fee']);
        });
    }
};
