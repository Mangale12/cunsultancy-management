<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove the constraint that prevents having both agent and branch
            // Using raw SQL for MariaDB compatibility
            DB::statement('ALTER TABLE students DROP CONSTRAINT students_agent_or_branch_chk');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Re-add the constraint if needed
            $table->check('(agent_id is not null and branch_id is null) or (agent_id is null and branch_id is not null)');
        });
    }
};
