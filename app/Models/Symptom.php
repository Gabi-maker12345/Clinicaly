<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = ['name', 'severity',];

    public function diseases()
    {
        return $this->belongsToMany(Disease::class)
                    ->withPivot('weight');
    }

    public function descriptions()
    {
        return $this->morphMany(Description::class, 'describable');
    }
}
