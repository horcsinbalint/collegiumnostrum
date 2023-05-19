<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScientificDegree extends Model
{
    use HasFactory;

    public static $scientific_degrees_enum = [
        'egyetemi doktor',
        'kandidátus',
        'tudományok doktora/MTA doktora',
        'PhD',
        'habilitáció',
        'DLA',
        'egyéb'
    ];

    public function alumni()
    {
        return $this->belongsToMany(Alumnus::class)->withTimestamps();
    }
}
