<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            // Personal Identification
            'passport' => 'Passport',
            'national_id' => 'National ID',
            'birth_certificate' => 'Birth Certificate',
            'driver_license' => 'Driver\'s License',
            'visa' => 'Visa',
            'residence_permit' => 'Residence Permit',
            
            // Academic Documents
            'high_school_diploma' => 'High School Diploma',
            'high_school_transcript' => 'High School Transcript',
            'bachelor_degree' => 'Bachelor\'s Degree',
            'bachelor_transcript' => 'Bachelor\'s Transcript',
            'master_degree' => 'Master\'s Degree',
            'master_transcript' => 'Master\'s Transcript',
            'phd_degree' => 'PhD Degree',
            'phd_transcript' => 'PhD Transcript',
            'language_certificate' => 'Language Certificate',
            'professional_certificate' => 'Professional Certificate',
            'transcript' => 'Academic Transcript',
            'diploma' => 'Diploma',
            'degree_certificate' => 'Degree Certificate',
            
            // Test Scores
            'ielts' => 'IELTS Test Report',
            'toefl' => 'TOEFL Score Report',
            'gre' => 'GRE Score Report',
            'gmat' => 'GMAT Score Report',
            'sat' => 'SAT Score Report',
            'act' => 'ACT Score Report',
            'pte' => 'PTE Academic Score',
            'duolingo' => 'Duolingo English Test',
            
            // Financial Documents
            'bank_statement' => 'Bank Statement',
            'sponsor_letter' => 'Sponsor Letter',
            'scholarship_letter' => 'Scholarship Letter',
            'financial_guarantee' => 'Financial Guarantee',
            'tax_return' => 'Tax Return',
            'affidavit_of_support' => 'Affidavit of Support',
            
            // Application Documents
            'sop' => 'Statement of Purpose',
            'lor' => 'Letter of Recommendation',
            'cv' => 'Curriculum Vitae',
            'resume' => 'Resume',
            'portfolio' => 'Portfolio',
            'research_proposal' => 'Research Proposal',
            'writing_sample' => 'Writing Sample',
            'application_form' => 'Application Form',
            'passport_photo' => 'Passport Photo',
            'medical_report' => 'Medical Report',
            'police_clearance' => 'Police Clearance Certificate',
            
            // Other
            'other' => 'Other Document',
        ];

        $data = [];
        foreach ($documentTypes as $key => $label) {
            $data[] = [
                'key' => $key,
                'label' => $label,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Truncate the table before seeding
        DB::table('document_types')->truncate();
        
        // Insert the document types
        DB::table('document_types')->insert($data);
    }
}
