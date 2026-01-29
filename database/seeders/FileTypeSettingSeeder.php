<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileTypeSetting;

class FileTypeSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fileTypes = [
            [
                'name' => 'Passport',
                'code' => 'PASSPORT',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Passport copy or scan',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Birth Certificate',
                'code' => 'BIRTH_CERT',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Birth certificate copy',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Academic Transcripts',
                'code' => 'TRANSCRIPTS',
                'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Academic transcripts and grade reports',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Degree Certificate',
                'code' => 'DEGREE_CERT',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Degree certificates and diplomas',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'IELTS Score',
                'code' => 'IELTS',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'IELTS test scores and certificates',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'TOEFL Score',
                'code' => 'TOEFL',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'TOEFL test scores and certificates',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Bank Statement',
                'code' => 'BANK_STATEMENT',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Bank statements and financial documents',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Sponsorship Letter',
                'code' => 'SPONSORSHIP',
                'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Sponsorship letters and financial support documents',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Student Visa',
                'code' => 'STUDENT_VISA',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Student visa and immigration documents',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Medical Certificate',
                'code' => 'MEDICAL_CERT',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Medical certificates and health records',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Police Clearance',
                'code' => 'POLICE_CLEARANCE',
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Police clearance certificates',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Letter of Recommendation',
                'code' => 'RECOMMENDATION',
                'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Letters of recommendation and references',
                'is_active' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'Statement of Purpose',
                'code' => 'SOP',
                'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Statement of purpose and personal statements',
                'is_active' => true,
                'sort_order' => 13,
            ],
            [
                'name' => 'Resume/CV',
                'code' => 'RESUME',
                'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
                'max_file_size_mb' => 10,
                'allows_multiple_files' => true,
                'max_files' => 50,
                'description' => 'Resume, CV, and professional documents',
                'is_active' => true,
                'sort_order' => 14,
            ],
        ];

        foreach ($fileTypes as $fileType) {
            FileTypeSetting::create($fileType);
        }
    }
}
