<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question_option extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_text',
        'correct'
    ];
}
