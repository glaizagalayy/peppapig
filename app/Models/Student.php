<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'login_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id', 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_year', 'batch_year');
    }
}