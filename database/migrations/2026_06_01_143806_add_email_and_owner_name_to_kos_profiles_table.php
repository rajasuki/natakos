<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('kos_profiles')) {
            return;
        }

        Schema::table('kos_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('kos_profiles', 'email')) {
                $table->string('email', 255)->nullable()->after('whatsapp_number');
            }

            if (! Schema::hasColumn('kos_profiles', 'owner_name')) {
                $table->string('owner_name', 255)->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('kos_profiles')) {
            return;
        }

        if (Schema::hasColumn('kos_profiles', 'email')) {
            Schema::table('kos_profiles', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

        if (Schema::hasColumn('kos_profiles', 'owner_name')) {
            Schema::table('kos_profiles', function (Blueprint $table) {
                $table->dropColumn('owner_name');
            });
        }
    }
};
