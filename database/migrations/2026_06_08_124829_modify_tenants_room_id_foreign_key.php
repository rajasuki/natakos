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
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign('fk_tenants_room');

            $table->unsignedBigInteger('room_id')->nullable()->change();

            $table->foreign('room_id', 'fk_tenants_room')->references('id')->on('rooms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign('fk_tenants_room');

            $table->unsignedBigInteger('room_id')->nullable(false)->change();

            $table->foreign('room_id', 'fk_tenants_room')->references('id')->on('rooms')->cascadeOnDelete();
        });
    }
};
