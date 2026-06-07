<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'late_fee')) {
                $table->integer('late_fee')->default(0)->after('amount');
            }
            if (! Schema::hasColumn('payments', 'late_fee_days')) {
                $table->integer('late_fee_days')->default(0)->after('late_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['late_fee', 'late_fee_days']);
        });
    }
};
