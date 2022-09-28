<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnus extends Model
{
    use HasFactory;
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function further_courses()
    {
        return $this->belongsToMany(FurtherCourse::class)->withTimestamps();
    }
    public function scientific_degrees()
    {
        return $this->belongsToMany(ScientificDegree::class)->withTimestamps();
    }
}
