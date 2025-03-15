<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = [

    ];

    protected $allowIncluded = ['courses'];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers ()
    {
        return $this->hasMany(Answer::class);
    }

    public function courses ()
    {
        return $this->belongsToMany(Course::class,'course_instructor');
    }

    public function knowledgeNetwork ()
    {
        return $this->belongsTo(KnowledgeNetwork::class);
    }

    public function scopeIncluded(Builder $query)
    {
        if(empty($this->allowIncluded)||empty(request('included'))){
             return;
        }
        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);
        foreach ($relations as $key => $relationship) {

            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }
        $query->with($relations);
    }


    public function getHasGeneralAnswersAttribute()
{
    return $this->answers()
        ->whereIn('question_id', [21, 22])
        ->whereNotNull('qualification')
        ->where('qualification', '<>', '')
        ->exists();
}

public function coursesSurveyOpen()
{
    return $this->belongsToMany(Course::class, 'course_instructor')
                ->where('is_survey_open', true);
}

}
