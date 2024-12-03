<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'type',
        'qualification',
        'apprentice_id',
        'instructor_id',
        'question_id',
        'course_id'
    ];
    protected $allowIncluded = ['question', 'instructor'];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function apprentice()
    {
        return $this->belongsTo(Apprentice::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeIncluded(Builder $query)
    {

        if (empty($this->allowIncluded) || empty(request('included'))) {
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
}
