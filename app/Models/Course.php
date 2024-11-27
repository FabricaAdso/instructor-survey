<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'code',
        'instructor_id',
        'program_id',
        'municipality_id'
    ];
    protected $allowIncluded = ['instructors']; 

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

    public function apprentices ()
    {
        return $this->hasMany(Apprentice::class);
    }

    public function municipality ()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function instructors ()
    {
        return $this->belongsToMany(Instructor::class,'course_instructor', 'course_id', 'instructor_id');
    }
}
