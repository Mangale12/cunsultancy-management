<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_application_id',
        'student_id',
        'payment_type',
        'payment_method',
        'amount',
        'currency',
        'exchange_rate',
        'due_date',
        'paid_date',
        'status',
        'paid_amount',
        'transaction_reference',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'due_date' => 'date',
        'paid_date' => 'date',
        'paid_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function studentApplication(): BelongsTo
    {
        return $this->belongsTo(StudentApplication::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->amount - $this->paid_amount;
    }

    public function isFullyPaid(): bool
    {
        return $this->paid_amount >= $this->amount;
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && !$this->isFullyPaid();
    }

    public function getPaymentTypeLabelAttribute(): string
    {
        return match($this->payment_type) {
            'application_fee' => 'Application Fee',
            'tuition_fee' => 'Tuition Fee',
            'visa_fee' => 'Visa Fee',
            'accommodation' => 'Accommodation',
            'insurance' => 'Insurance',
            'flight' => 'Flight',
            'other' => 'Other',
            default => ucfirst($this->payment_type),
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending' => ['label' => 'Pending', 'variant' => 'secondary'],
            'partial' => ['label' => 'Partial', 'variant' => 'default'],
            'paid' => ['label' => 'Paid', 'variant' => 'default'],
            'overdue' => ['label' => 'Overdue', 'variant' => 'destructive'],
            'cancelled' => ['label' => 'Cancelled', 'variant' => 'outline'],
            default => ['label' => ucfirst($this->status), 'variant' => 'secondary'],
        };
    }
}
