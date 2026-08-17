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
        Schema::table('queries', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('preferred_contact')->nullable()->after('phone');
            $table->json('selected_services')->nullable()->after('preferred_contact');
        });
    }

    public function down(): void
    {
        Schema::table('queries', function (Blueprint $table) {
            $table->dropColumn(['phone', 'preferred_contact', 'selected_services']);
        });
    }
};
