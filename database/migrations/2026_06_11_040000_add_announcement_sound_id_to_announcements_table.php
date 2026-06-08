<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('announcement_sound_id')->nullable()->constrained('announcement_sounds')->nullOnDelete()->after('scroll_speed');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['announcement_sound_id']);
            $table->dropColumn('announcement_sound_id');
        });
    }
};
