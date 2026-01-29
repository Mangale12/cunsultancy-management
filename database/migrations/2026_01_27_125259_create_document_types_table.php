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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Unique identifier
            $table->text('description')->nullable();
            $table->enum('category', ['academic', 'financial', 'identification', 'visa', 'medical', 'other']);
            $table->boolean('is_required')->default(false);
            $table->boolean('has_expiry')->default(false);
            $table->json('allowed_file_types'); // ['pdf', 'jpg', 'png', 'doc', 'docx']
            $table->integer('max_file_size')->default(5120); // KB
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['category']);
            $table->index(['is_required']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
