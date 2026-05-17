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
            if (! Schema::hasColumn('kos_profiles', 'google_maps_embed_url')) {
                $table->string('google_maps_embed_url', 2048)->nullable();
            }

            if (! Schema::hasColumn('kos_profiles', 'nearby_places')) {
                $table->text('nearby_places')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('kos_profiles')) {
            return;
        }

        if (Schema::hasColumn('kos_profiles', 'nearby_places')) {
            Schema::table('kos_profiles', function (Blueprint $table) {
                $table->dropColumn('nearby_places');
            });
        }

        if (Schema::hasColumn('kos_profiles', 'google_maps_embed_url')) {
            Schema::table('kos_profiles', function (Blueprint $table) {
                $table->dropColumn('google_maps_embed_url');
            });
        }
    }
};
