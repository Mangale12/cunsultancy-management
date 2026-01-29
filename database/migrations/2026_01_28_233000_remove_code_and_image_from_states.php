<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('states', function (Blueprint $table) {
            // Drop the code and image_path columns
            $table->dropColumn(['code', 'image_path']);
        });
    }

    public function down(): void
    {
        Schema::table('states', function (Blueprint $table) {
            // Add back the code and image_path columns
            $table->string('code', 10)->nullable()->after('name');
            $table->string('image_path')->nullable()->after('code');
        });
    }
};
