<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Apprentice extends Authenticatable
{
    protected $fillable = [
        'course_id'
    ];

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected function casts(): array
    {
        $cast = [];
        if(!env('APP_DEBUG')) $cast['identity_document'] = 'hashed';
        return $cast;
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

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
