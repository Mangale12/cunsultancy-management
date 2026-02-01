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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('code', 3)->nullable()->after('name');
            $table->string('currency', 10)->nullable()->after('code');
            $table->string('phone_code', 10)->nullable()->after('currency');
            $table->boolean('is_active')->default(true)->after('phone_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['code', 'currency', 'phone_code', 'is_active']);
        });
    }
};
