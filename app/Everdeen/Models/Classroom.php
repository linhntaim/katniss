<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    const STATUS_OPENING = 1;
    const STATUS_CLOSED = 0;

    protected $table = 'classrooms';

    protected $fillable = [
        'student_id', 'teacher_id', 'supporter_id', 'status'
    ];

    public function teacherProfile()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function teacherUserProfile()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function studentProfile()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    public function studentUserProfile()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function supporter()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
}
