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
}
