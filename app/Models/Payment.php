<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id'; // Define the primary key

    protected $fillable = [
        'student_id',
        'amount',
        'payment_date',
        'payment_mode',
        'payment_proof',
        'status',
        'verified_by',
        'reference_number',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}