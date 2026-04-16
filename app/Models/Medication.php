<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = ['name', 'active_principle'];

    public function diseases()
    {
        return $this->belongsToMany(Disease::class);
    }

    public function descriptions()
    {
        return $this->morphMany(Description::class, 'describable');
    }
}
