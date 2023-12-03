<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'subject_id',
        'trainingTypeCode',
        'group_id',
        'group_name',
        'subject_name',
        'trainingTypeName',
        'year_id',
        'semester_id',
        'isactive',
    ];
}
