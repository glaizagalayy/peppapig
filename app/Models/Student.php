<?php

namespace App\Models;
use App\Models\Batch;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'middle_initial',
        'suffix',
        'email',
        'batch_year',
        'group_num',
        'student_number',
        'center_training_code',
        'region_code'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id', 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_year', 'batch_year');
    }

    public function getTotalPaidAttribute()
    {
        // Sum all approved payments (added by finance or approved payment proofs)
        return $this->payments()->whereIn('status', ['Approved', 'Added by Finance'])->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        // Calculate remaining balance based on batch total_due and total_paid
        $totalDue = $this->batch->total_due ?? 0; // Default to 0 if no batch is assigned
        return max(0, $totalDue - $this->total_paid); // Ensure remaining balance is not negative
    }
}