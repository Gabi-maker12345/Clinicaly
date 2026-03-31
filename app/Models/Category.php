<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name',];

    public function diseases()
    {
        return $this->belongsToMany(Disease::class);
    }

    public function descriptions()
    {
        return $this->morphMany(Description::class, 'describable');
    }
}
