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
        Schema::table('kos_profiles', function (Blueprint $table) {
            $table->string('email', 255)->nullable()->after('whatsapp_number');
            $table->string('owner_name', 255)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('kos_profiles', function (Blueprint $table) {
            $table->dropColumn(['email', 'owner_name']);
        });
    }
};
