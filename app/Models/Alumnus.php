<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnus extends Model
{
    protected $fillable=[
        'name',
        'email',
        'birth_date',
        'birth_place',
        'high_school',
        'graduation_date',
        'further_course_detailed',
        'start_of_membership',
        'recognations',
        'research_field_detailed',
        'links',
        'works',
    ];

    use HasFactory;
    public function majors()
    {
        return $this->belongsToMany(Major::class)->withTimestamps();
    }
    public function further_courses()
    {
        return $this->belongsToMany(FurtherCourse::class)->withTimestamps();
    }
    public function scientific_degrees()
    {
        return $this->belongsToMany(ScientificDegree::class)->withTimestamps();
    }
    public function research_fields()
    {
        return $this->belongsToMany(ResearchField::class)->withTimestamps();
    }
    public function university_faculties()
    {
        return $this->belongsToMany(UniversityFaculty::class)->withTimestamps();
    }

    /**
     * Returns the non-draft pair of a draft alumnus;
     * or the draft pair of a non-draft alumnus, if it has one.
     */
    public function pair() : Alumnus {
        return Alumnus::find($this->pair_id)->first();
    }

    /**
     * Returns whether the entry has a pair, either draft or non-draft.
    */
    public function has_pair() : bool {
        return !is_null($this->pair_id);
    }
}
