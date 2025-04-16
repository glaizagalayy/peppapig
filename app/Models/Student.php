<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student  extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'first_name', 'last_name', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}