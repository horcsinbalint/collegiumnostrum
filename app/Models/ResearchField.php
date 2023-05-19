<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchField extends Model
{
    use HasFactory;

    public static $research_fields_enum = [
        'Matematika- és számítástudományok',
        'Fizikai tudományok',
        'Kémiai tudományok',
        'Földtudományok',
        'Biológiai tudományok',
        'Környezettudományok',
        'Multidiszciplináris természettudományok',
        'Gazdálkodás- és szervezéstudományok',
        'Közgazdaságtudományok',
        'Állam- és jogtudományok',
        'Szociológiai tudományok',
        'Politikatudományok',
        'Hadtudományok',
        'Multidiszciplináris társadalomtudományok',
        'Történelemtudományok',
        'Irodalomtudományok',
        'Nyelvtudományok',
        'Filozófiai tudományok',
        'Nevelés- és sporttudományok',
        'Pszichológiai tudományok',
        'Néprajz és kulturális antropológiai tudományok',
        'Művészeti és művelődéstörténeti tudományok',
        'Vallástudományok',
        'Média- és kommunikációs tudományok',
        'Multidiszciplináris bölcsészettudományok',
        'egyéb'
    ];

    public function alumni()
    {
        return $this->belongsToMany(Alumnus::class)->withTimestamps();
    }
}
