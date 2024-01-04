<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_name',
        'teacher_id',
        'subject_id',
        'assessment_id',
        'testcategory_id',
        'limit'
    ];
}
