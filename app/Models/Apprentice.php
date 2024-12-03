<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Apprentice extends Authenticatable
{
    protected $fillable = [
        'name',
        'last_name',
        'second_last_name',
        'identity_document',
        'course_id'
    ];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function survey ()
    {
        return $this->belongsTo(Survey::class);
    }

    public function course ()
    {
        return $this->belongsTo(Course::class);
    }

    public function answers ()
    {
        return $this->hasMany(Answer::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor', 'course_id', 'instructor_id');
    }

}
