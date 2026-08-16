<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->string('type');
            $table->text('description');
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_index');
        });

        Schema::create('service_bullet_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('text');
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('service_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('picture_path')->nullable();
            $table->string('title');
            $table->string('type')->nullable();
            $table->string('view_link')->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();

            $table->index('order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_projects');
        Schema::dropIfExists('service_bullet_points');
        Schema::dropIfExists('services');
    }
};
