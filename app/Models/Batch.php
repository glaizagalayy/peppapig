<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['batch_year', 'total_due']; // Add other fields as needed
    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Student::class, 'batch_year', 'student_id', 'batch_year', 'student_id');
    }
}