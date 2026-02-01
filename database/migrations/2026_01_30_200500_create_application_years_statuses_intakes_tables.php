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
        Schema::create('application_years', function (Blueprint $table) {
            $table->id();
            $table->string('year'); // e.g., 2026, 2027
            $table->timestamps();
        });

        Schema::create('application_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Draft, Submitted, etc.
            $table->string('code')->unique(); // e.g., draft, submitted
            $table->timestamps();
        });

        Schema::create('intakes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., January, May, September
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intakes');
        Schema::dropIfExists('application_statuses');
        Schema::dropIfExists('application_years');
    }
};
