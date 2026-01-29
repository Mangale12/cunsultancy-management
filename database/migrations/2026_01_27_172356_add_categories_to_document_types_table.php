<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create documents table first
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->integer('file_size');
            $table->string('file_hash')->unique(); // MD5 hash for integrity
            $table->enum('status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_public')->default(false); // For sharing with universities
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['student_id', 'document_type_id']);
            $table->index(['status']);
            $table->index(['expiry_date']);
        });

        // Create document_verifications table
        Schema::create('document_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['document_id', 'status']);
        });

        Schema::table('document_types', function (Blueprint $table) {
            // First update existing data to valid categories
            \DB::statement("UPDATE document_types SET category = 'other' WHERE category NOT IN ('academic', 'financial', 'identity', 'medical', 'visa', 'language', 'experience', 'recommendation', 'personal', 'travel', 'accommodation', 'other')");
            
            // Update category to be more specific for abroad consultancy
            $table->enum('category', [
                'academic',      // Transcripts, certificates, degrees
                'financial',     // Bank statements, sponsorship letters
                'identity',      // Passport, birth certificate, national ID
                'medical',       // Health reports, vaccinations, medical tests
                'visa',          // Visa application forms, interview documents
                'language',      // IELTS, TOEFL, language proficiency
                'experience',    // Work experience, internships
                'recommendation', // Letters of recommendation
                'personal',      // Personal statement, SOP, essays
                'travel',        // Flight bookings, travel insurance
                'accommodation', // Housing documents, rental agreements
                'other'          // Miscellaneous documents
            ])->default('other')->change();
            
            // Add visa-specific fields
            $table->boolean('is_visa_required')->default(false);
            $table->string('visa_document_type')->nullable(); // student_visa, work_visa, etc.
            $table->text('visa_requirements')->nullable();
            
            // Add expiry tracking
            $table->boolean('has_expiry_validation')->default(false);
            $table->integer('expiry_warning_days')->default(30);
            
            // Add document processing fields
            $table->boolean('requires_verification')->default(true);
            $table->boolean('requires_notarization')->default(false);
            $table->boolean('requires_translation')->default(false);
            
            // Add indexes for performance
            $table->index(['category', 'is_active']);
            $table->index(['is_visa_required', 'visa_document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_types', function (Blueprint $table) {
            $table->dropColumn([
                'is_visa_required',
                'visa_document_type', 
                'visa_requirements',
                'has_expiry_validation',
                'expiry_warning_days',
                'requires_verification',
                'requires_notarization',
                'requires_translation'
            ]);
        });
        
        Schema::dropIfExists('document_verifications');
        Schema::dropIfExists('documents');
    }
};
