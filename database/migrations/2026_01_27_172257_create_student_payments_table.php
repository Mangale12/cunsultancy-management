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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained();
            $table->enum('payment_type', [
                'application_fee', 'tuition_fee', 'visa_fee', 
                'accommodation', 'insurance', 'flight', 'other'
            ]);
            $table->string('payment_method'); // bank_transfer, credit_card, cash, etc.
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index(['student_application_id', 'payment_type']);
            $table->index('due_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
