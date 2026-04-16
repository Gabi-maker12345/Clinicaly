<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = ['name', 'icd_code', 'category_id', 'severity',];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class)
                    ->withPivot('weight')
                    ->withTimestamps();
    }

    public function medications()
    {
        return $this->belongsToMany(Medication::class)->withTimestamps();
    }

    public function descriptions()
    {
        return $this->morphMany(Description::class, 'describable');
    }
}
