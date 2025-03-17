<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeNetwork extends Model
{
    public function instructors ()
    {
        return $this->hasMany(Instructor::class);
    }
}
