<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurtherCourse extends Model
{
    use HasFactory;
    public function alumni()
    {
        return $this->belongsToMany(Alumnus::class)->withTimestamps();
    }
}