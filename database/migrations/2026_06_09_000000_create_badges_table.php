<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('badges')) {
            Schema::create('badges', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('effect', 50)->default('none');
                $table->string('requirement_type', 50)->nullable();
                $table->integer('requirement_value')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('user_badge')) {
            Schema::create('user_badge', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
                $table->boolean('is_selected')->default(false);
                $table->timestamp('unlocked_at')->nullable();
                $table->timestamps();
                $table->primary(['user_id', 'badge_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badge');
        Schema::dropIfExists('badges');
    }
};
