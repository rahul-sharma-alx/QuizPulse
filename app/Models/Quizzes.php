<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Questions;
use App\Models\Attempts;


class Quizzes extends Model
{
    protected $fillable = ['admin_id', 'title', 'description', 'duration_minutes', 'is_published'];
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function questions()
    {
        return $this->hasMany(Questions::class, 'quiz_id');
    }
    public function attempts()
    {
        return $this->hasMany(Attempts::class, 'quiz_id');
    }
}
