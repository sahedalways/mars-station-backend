<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        // Delete records with NULL picture_path — these are broken.
        DB::table('service_projects')->whereNull('picture_path')->delete();

        // Delete records whose referenced file no longer exists on disk.
        $remaining = DB::table('service_projects')->whereNotNull('picture_path')->get();
        foreach ($remaining as $record) {
            if (! Storage::disk('public')->exists($record->picture_path)) {
                DB::table('service_projects')->where('id', $record->id)->delete();
            }
        }

        Schema::table('service_projects', function (Blueprint $table) {
            $table->string('picture_path')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_projects', function (Blueprint $table) {
            $table->string('picture_path')->nullable()->change();
        });
    }
};
