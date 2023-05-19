<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurtherCourse extends Model
{
    use HasFactory;

    public static $further_courses_enum = [
        'akadémikus',
        'általános iskolai tanár',
        'egyetemi és/vagy főiskolai oktató',
        'minisztériumi tisztviselő',
        'középiskolai igazgató',
        'középiskolai tanár',
        'közgyűjteményi dolgozó',
        'közgyűjteményi igazgató',
        'író',
        'művész',
        'politikus',
        'tudományos kutató',
        'vállalkozó',
        'egyéb'
    ];

    public function alumni()
    {
        return $this->belongsToMany(Alumnus::class)->withTimestamps();
    }
}
