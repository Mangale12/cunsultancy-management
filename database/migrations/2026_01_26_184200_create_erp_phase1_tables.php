<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('iso2', 2)->unique();
            $table->char('iso3', 3)->nullable()->unique();
            $table->string('phone_code', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->index()->constrained('countries')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 10)->nullable();
            $table->timestamps();

            $table->unique(['country_id', 'name']);
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->index()->constrained('countries')->restrictOnDelete();
            $table->foreignId('state_id')->nullable()->index()->constrained('states')->nullOnDelete();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('manager_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();

            $table->primary(['role_id', 'permission_id']);
            $table->index('permission_id');
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->index()->constrained('branches')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->index()->constrained('users')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('job_title')->nullable();
            $table->date('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id']);
        });

        Schema::create('employee_role', function (Blueprint $table) {
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

            $table->primary(['employee_id', 'role_id']);
            $table->index('role_id');
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->index()->constrained('branches')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->index()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_agent_id')->nullable()->index()->constrained('agents')->nullOnDelete();
            $table->string('name');
            $table->string('code', 50)->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->timestamps();

            $table->unique(['user_id']);
        });

        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->index()->constrained('countries')->nullOnDelete();
            $table->foreignId('state_id')->nullable()->index()->constrained('states')->nullOnDelete();
            $table->string('name');
            $table->string('code', 50)->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->index()->constrained('universities')->cascadeOnDelete();
            $table->string('name');
            $table->string('level')->nullable();
            $table->unsignedSmallInteger('duration_months')->nullable();
            $table->decimal('tuition_fee', 12, 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->timestamps();

            $table->unique(['university_id', 'name']);
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->date('date_of_birth')->nullable();

            $table->foreignId('country_id')->nullable()->index()->constrained('countries')->nullOnDelete();
            $table->foreignId('state_id')->nullable()->index()->constrained('states')->nullOnDelete();
            $table->text('address')->nullable();

            $table->foreignId('branch_id')->nullable()->index()->constrained('branches')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->index()->constrained('agents')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->index()->constrained('courses')->nullOnDelete();

            $table->string('status')->default('new');
            $table->timestamps();
        });

        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'pgsql'], true)) {
            DB::statement(
                "ALTER TABLE students ADD CONSTRAINT students_agent_or_branch_chk CHECK ((agent_id IS NOT NULL AND branch_id IS NULL) OR (agent_id IS NULL AND branch_id IS NOT NULL))"
            );
        }

        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->index()->constrained('students')->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->index()->constrained('agents')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->index()->constrained('branches')->nullOnDelete();
            $table->foreignId('university_id')->nullable()->index()->constrained('universities')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->index()->constrained('courses')->nullOnDelete();

            $table->enum('type', ['flat', 'percentage'])->index();
            $table->decimal('value', 12, 4);
            $table->char('currency', 3)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        if (in_array($driver, ['mysql', 'pgsql'], true)) {
            DB::statement(
                "ALTER TABLE commissions ADD CONSTRAINT commissions_agent_or_branch_chk CHECK ((agent_id IS NOT NULL AND branch_id IS NULL) OR (agent_id IS NULL AND branch_id IS NOT NULL))"
            );
            DB::statement(
                "ALTER TABLE commissions ADD CONSTRAINT commissions_value_chk CHECK ((type = 'flat' AND value >= 0) OR (type = 'percentage' AND value >= 0 AND value <= 100))"
            );
        }

        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');
            $table->string('event')->nullable()->index();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        // Spatie Permission Tables
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
            $table->primary(['permission_id', 'model_id', 'model_type']);

            $table->index(['model_id', 'model_type']);
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->primary(['role_id', 'model_id', 'model_type']);

            $table->index(['model_id', 'model_type']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('students');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('universities');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('employee_role');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_permissions');
    }
};
