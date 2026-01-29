<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            // Drop the image_path column
            $table->dropColumn('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            // Add back the image_path column
            $table->string('image_path')->nullable()->after('code');
        });
    }
};
