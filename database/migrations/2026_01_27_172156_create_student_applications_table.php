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
        Schema::create('student_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('university_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->enum('application_status', [
                'draft', 'submitted', 'under_review', 'admitted', 
                'rejected', 'enrolled', 'withdrawn', 'deferred'
            ])->default('draft');
            $table->date('application_date')->nullable();
            $table->date('submission_deadline')->nullable();
            $table->date('admission_deadline')->nullable();
            $table->enum('visa_status', [
                'not_started', 'documents_collected', 'application_submitted',
                'interview_scheduled', 'interview_completed', 'approved',
                'rejected', 'issued'
            ])->default('not_started');
            $table->date('visa_application_date')->nullable();
            $table->date('visa_interview_date')->nullable();
            $table->date('visa_approval_date')->nullable();
            $table->enum('pre_departure_status', [
                'not_started', 'documents_ready', 'flight_booked',
                'accommodation_arranged', 'insurance_done', 'ready'
            ])->default('not_started');
            $table->decimal('tuition_fee', 10, 2)->nullable();
            $table->decimal('scholarship_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'application_status']);
            $table->index(['university_id', 'course_id']);
            $table->index('application_status');
            $table->index('visa_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_applications');
    }
};
