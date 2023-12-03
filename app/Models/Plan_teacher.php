<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'teacher_id',
        'last_teacher'
    ];
}
