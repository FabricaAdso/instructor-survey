<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_identifier',
        'instructor_id',
        'question_id',
        'response',
    ];

    // En OpenQuestion.php
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
