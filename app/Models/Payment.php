<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        '_token', // CSRF token for form submissions
        'student_id',
        'amount',
        'payment_date',
        'payment_mode',
        'payment_proof',
        'status',
        'verified_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
