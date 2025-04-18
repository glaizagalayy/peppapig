<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'login_id',
        'email',
        'password',
        'role',
        'is_active',
        'password_reset_required',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Specify the correct table name.
     *
     * @var string
     */
    protected $table = 'pnph_users';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Specify login_id as the username field.
     *
     * @return string
     */
    protected function username(): string
    {
        return 'login_id';
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function finance()
    {
        return $this->hasOne(Finance::class, 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'student_id', 'login_id'); // Match login_id with student_id
    }

    /**
     * Get the profile name based on the user's role.
     *
     * @return string
     */
    public function getProfileNameAttribute()
    {
        if ($this->role === 'admin' && $this->admin) {
            return $this->admin->first_name . ' ' . $this->admin->last_name;
        }

        if ($this->role === 'finance' && $this->finance) {
            return $this->finance->first_name . ' ' . $this->finance->last_name;
        }

        if ($this->role === 'student' && $this->student) {
            return $this->student->first_name . ' ' . $this->student->last_name;
        }

        return 'Unknown User'; // Provide a default value if no related record is found
    }

    public function getFullNameAttribute()
    {
        if ($this->role === 'admin' && $this->admin) {
            return $this->admin->first_name . ' ' . $this->admin->last_name;
        }

        if ($this->role === 'finance' && $this->finance) {
            return $this->finance->first_name . ' ' . $this->finance->last_name;
        }

        if ($this->role === 'student' && $this->student) {
            return $this->student->first_name . ' ' . $this->student->last_name;
        }

        return 'Unknown User'; // Default if no related record is found
    }
}
