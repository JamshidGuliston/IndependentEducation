<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_topic extends Model
{
    use HasFactory;
    // protected $primaryKey = ['plan_id', 'topic_id'];
    protected $fillable = ['plan_id', 'topic_id'];
}
