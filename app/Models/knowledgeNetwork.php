<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class knowledgeNetwork extends Model
{
    public function instructors ()
    {
        return $this->hasMany(Instructor::class);
    }
}
