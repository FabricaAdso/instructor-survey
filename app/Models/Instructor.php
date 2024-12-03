<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'second_last_name',
        'identity_document',
    ];

    protected $allowIncluded = ['courses'];

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

    public function answers ()
    {
        return $this->hasMany(Answer::class);
    }

    public function courses ()
    {
        return $this->belongsToMany(Course::class,'course_instructor');
    }

}
