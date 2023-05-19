<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityFaculty extends Model
{
    use HasFactory;

    public static $university_faculties_enum = [
        'Állam- és Jogtudományi Kar',
        'Bárczi Gusztáv Gyógypedagógiai Kar',
        'Bölcsészettudományi Kar',
        'Gazdaságtudományi Kar',
        'Informatikai Kar',
        'Pedagógiai és Pszichológiai Kar',
        'Tanító- és Óvóképző Kar',
        'Társadalomtudományi Kar',
        'Természettudományi Kar',
        'egyéb'
    ];

    public function alumni()
    {
        return $this->belongsToMany(Alumnus::class)->withTimestamps();
    }
}
