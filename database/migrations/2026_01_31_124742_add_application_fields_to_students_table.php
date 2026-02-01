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
        Schema::table('students', function (Blueprint $table) {
            $table->string('application_status')->default('pending')->after('status');
            $table->timestamp('application_completed_at')->nullable()->after('application_status');
            $table->text('application_notes')->nullable()->after('application_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'application_status',
                'application_completed_at',
                'application_notes'
            ]);
        });
    }
};
