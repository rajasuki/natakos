<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('kos_profiles')) {
            Schema::create('kos_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('address', 500)->nullable();
                $table->string('whatsapp_number', 20)->nullable();
                $table->string('email', 255)->nullable();
                $table->string('owner_name', 255)->nullable();
                $table->string('google_maps_url', 2048)->nullable();
                $table->string('google_maps_embed_url', 2048)->nullable();
                $table->text('nearby_places')->nullable();
                $table->string('logo', 255)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('slug', 255)->unique();
                $table->integer('price');
                $table->string('size', 100)->nullable();
                $table->string('floor', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('available');
                $table->string('main_image', 255)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('facilities')) {
            Schema::create('facilities', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('type', 50);
                $table->string('icon', 100)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('facility_room')) {
            Schema::create('facility_room', function (Blueprint $table) {
                $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
                $table->foreignId('room_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['facility_id', 'room_id']);
            });
        }

        if (! Schema::hasTable('room_images')) {
            Schema::create('room_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_id')->constrained()->cascadeOnDelete();
                $table->string('image_path', 255);
                $table->string('caption', 255)->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('room_id')->constrained()->cascadeOnDelete();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('status', 20)->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->integer('amount');
                $table->date('period_start');
                $table->date('period_end');
                $table->date('due_date');
                $table->datetime('paid_at')->nullable();
                $table->string('status', 30)->default('unpaid');
                $table->string('proof_image', 255)->nullable();
                $table->datetime('verified_at')->nullable();
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('room_images');
        Schema::dropIfExists('facility_room');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('kos_profiles');
    }
};
