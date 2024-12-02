<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'code',           
        'program_id',     
        'municipality_id' 
    ];

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
        return $this->belongsToMany(Instructor::class);
    }
}
