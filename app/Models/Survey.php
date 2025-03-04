<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function apprentices ()
    {
        return $this->hasMany(Apprentice::class);
    }

    public function questions ()
    {
        return $this->hasMany(Question::class);
    }
}
