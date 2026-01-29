<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('phone_code');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('code');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('email');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('joined_at');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('phone');
        });

        Schema::table('universities', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('code');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('currency');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('universities', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
