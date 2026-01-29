<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            // Drop unnecessary columns
            $table->dropColumn(['iso2', 'iso3', 'phone_code']);
            
            // Add code column if it doesn't exist
            if (!Schema::hasColumn('countries', 'code')) {
                $table->string('code')->unique()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            // Add back the original columns
            $table->char('iso2', 2)->unique()->after('name');
            $table->char('iso3', 3)->nullable()->unique()->after('iso2');
            $table->string('phone_code', 10)->nullable()->after('iso3');
            
            // Drop the code column
            $table->dropColumn('code');
        });
    }
};
