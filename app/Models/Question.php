<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['survey_id', 'question', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    protected $allowIncluded = ['answers','instructor'];

    public function survey ()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers ()
    {
        return $this->hasMany(Answer::class);
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

    public static function boot()
{
    parent::boot();

    static::saving(function ($model) {
        if ($model->type === 'radio' && $model->type === null) {
            throw new \Exception('El campo "type" no puede ser null si es de tipo "radio".');
        }
    });
}

}
