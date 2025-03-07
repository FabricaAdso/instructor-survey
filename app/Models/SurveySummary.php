<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'question_id',
        'course_id',
        'average_qualification',
        'total_responses',
        'survey_identifier',
    ];
}
