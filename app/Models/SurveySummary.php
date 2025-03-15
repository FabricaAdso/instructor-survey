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

    public function instructor()
    {
        return $this-> belongsto(Instructor::class);
    }
    public function course()
    {
        return $this-> belongsto(Course::class);
    }
    public function question()
    {
        return $this-> belongsto(Question::class);
    }
}
