<?php

namespace App\Http\Controllers;

use App\Models\Alumnus;
use \App\Models\UniversityFaculty;
use \App\Models\ResearchField;
use \App\Models\FurtherCourse;
use \App\Models\Major;
use \App\Models\ScientificDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class AlumnusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('alumni.index', [
            'alumni' => Alumnus::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('alumni.create', [
            'university_faculties' => UniversityFaculty::$university_faculties_enum,
            'majors' => Major::$majors_enum,
            'further_courses' => FurtherCourse::$further_courses_enum,
            'scientific_degrees' => ScientificDegree::$scientific_degrees_enum,
            'research_fields' => ResearchField::$research_fields_enum,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'email' => 'nullable|email',
                'birth_date' => 'nullable|numeric|gt:1930',
                'birth_place' => 'nullable|min:3',
                'high_school' => 'nullable|min:3',
                'graduation_date' => 'nullable|numeric|gt:1930',
                'further_course_detailed' => 'nullable|max:255',
                'start_of_membership' => 'nullable|numeric|gt:1930',
                'recognations' => 'nullable|max:255',
                'research_field_detailed' => 'nullable|max:255',
                'links' => 'nullable|max:255',
                'works' => 'nullable|max:255',
                'university_faculties' => 'nullable|array',
                'majors' => 'nullable|array',
                'further_courses' => 'nullable|array',
                'scientific_degrees' => 'nullable|array',
                'research_fields' => 'nullable|array',
                'dla_year' => 'nullable|numeric|gt:1930',
                'hab_year' => 'nullable|numeric|gt:1930',
                'mta_year' => 'nullable|numeric|gt:1930',
                'candidate_year' => 'nullable|numeric|gt:1930',
                'doctor_year' => 'nullable|numeric|gt:1930',
                'phd_year' => 'nullable|numeric|gt:1930',
            ]
        );

        $alumnus = Alumnus::factory()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'],
            'high_school' => $validated['high_school'],
            'graduation_date' => $validated['graduation_date'],
            'further_course_detailed' => $validated['further_course_detailed'],
            'start_of_membership' => $validated['start_of_membership'],
            'recognations' => $validated['recognations'],
            'research_field_detailed' => $validated['research_field_detailed'],
            'links' => $validated['links'],
            'works' => $validated['works'],
        ]);

        // University faculty
        // olyan unifaculty létrehozása, ami még nincs, a többi id lekérése database-ből és szinkronizálás
        if (isset($validated["university_faculties"])) {
            $existing_university_faculties = Arr::flatten(UniversityFaculty::select('name')->get()->makeHIdden('pivot')->toArray());
            $missing_university_faculties = array_diff($validated['university_faculties'], $existing_university_faculties);
            foreach ($missing_university_faculties as $faculty) {
                UniversityFaculty::factory()->create([
                    'name' => $faculty
                ]);
            }
            $university_faculty_ids = Arr::flatten(UniversityFaculty::select('id')->whereIn('name', $validated['university_faculties'])->get()->makeHIdden('pivot')->toArray());
            $alumnus->university_faculties()->sync($university_faculty_ids);
        }

        // Major
        if (isset($validated["majors"])) {
            $existing_majors = Arr::flatten(Major::select('name')->get()->makeHIdden('pivot')->toArray());
            $missing_majors = array_diff($validated['majors'], $existing_majors);
            foreach ($missing_majors as $major) {
                Major::factory()->create([
                    'name' => $major
                ]);
            }
            $major_ids = Arr::flatten(Major::select('id')->whereIn('name', $validated['majors'])->get()->makeHIdden('pivot')->toArray());
            $alumnus->majors()->sync($major_ids);
        }

        // Further courses
        if (isset($validated["further_courses"])) {
            $existing_further_courses = Arr::flatten(FurtherCourse::select('name')->get()->makeHIdden('pivot')->toArray());
            $missing_further_courses = array_diff($validated['further_courses'], $existing_further_courses);
            foreach ($missing_further_courses as $further_course) {
                FurtherCourse::factory()->create([
                    'name' => $further_course
                ]);
            }
            $ids = Arr::flatten(FurtherCourse::select('id')->whereIn('name', $validated['further_courses'])->get()->makeHIdden('pivot')->toArray());
            $alumnus->further_courses()->sync($ids);
        }

        // Scientific degree
        if (isset($validated["scientific_degrees"])) {
            $ids = [];
            foreach ($validated["scientific_degrees"] as $scientific_degree) {
                $degree = ScientificDegree::factory()->create([
                    'name' => $scientific_degree,
                    'obtain_year' => (
                            isset($validated['doctor_year']) && strcmp($scientific_degree, 'egyetemi doktor') == 0 ? $validated['doctor_year'] :
                            (isset($validated['candidate_year']) && strcmp($scientific_degree, 'kandidátus') == 0 ? $validated['candidate_year'] :
                            (isset($validated['mta_year']) && strcmp($scientific_degree, 'tudományok doktora/MTA doktora') == 0 ? $validated['mta_year'] :
                            (isset($validated['hab_year']) && strcmp($scientific_degree, 'habilitáció') == 0 ? $validated['hab_year'] :
                            (isset($validated['phd_year']) && strcmp($scientific_degree, 'PhD') == 0 ? $validated['phd_year'] :
                            (isset($validated['dla_year']) && strcmp($scientific_degree, 'DLA') == 0 ? $validated['dla_year'] : null)))))
                        )
                ]);
                array_push($ids,$degree->id);
            }
            $alumnus->scientific_degrees()->sync($ids);
        }

        // Research fields
        if (isset($validated["research_fields"])) {
            $existing_research_fields = Arr::flatten(ResearchField::select('name')->get()->makeHIdden('pivot')->toArray());
            $missing_research_fields = array_diff($validated['research_fields'], $existing_research_fields);
            foreach ($missing_research_fields as $research_field) {
                ResearchField::factory()->create([
                    'name' => $research_field
                ]);
            }
            $ids = Arr::flatten(ResearchField::select('id')->whereIn('name', $validated['research_fields'])->get()->makeHIdden('pivot')->toArray());
            $alumnus->research_fields()->sync($ids);
        }

        Session::flash('alumnus_created', $alumnus->name);

        // TODO: rather index?
        return Redirect::route('alumni.show', $alumnus);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function show(Alumnus $alumnus)
    {
        //
        return view('alumni.show', [
            'alumnus' => $alumnus,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function edit(Alumnus $alumnus)
    {
        return view('alumni.edit', [
            'university_faculties' => UniversityFaculty::$university_faculties_enum,
            'majors' => Major::$majors_enum,
            'further_courses' => FurtherCourse::$further_courses_enum,
            'scientific_degrees' => ScientificDegree::$scientific_degrees_enum,
            'research_fields' => ResearchField::$research_fields_enum,
            'alumnus' => $alumnus,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alumnus $alumnus)
    {
        // TODO: scientific degree and years somehow and in the seader create every field!!
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'email' => 'nullable|email',
                'birth_date' => 'nullable|numeric|gt:1930',
                'birth_place' => 'nullable|min:3',
                'high_school' => 'nullable|min:3',
                'graduation_date' => 'nullable|numeric|gt:1930',
                'further_course_detailed' => 'nullable|max:255',
                'start_of_membership' => 'nullable|numeric|gt:1930',
                'recognations' => 'nullable|max:255',
                'research_field_detailed' => 'nullable|max:255',
                'links' => 'nullable|max:255',
                'works' => 'nullable|max:255',
                'university_faculties' => 'nullable|array',
                'majors' => 'nullable|array',
                'further_courses' => 'nullable|array',
                'scientific_degrees' => 'nullable|array',
                'research_fields' => 'nullable|array',
            ]
        );

        $alumnus->name = $validated['name'];
        $alumnus->email = $validated['email'];
        $alumnus->birth_date = $validated['birth_date'];
        $alumnus->birth_place = $validated['birth_place'];
        $alumnus->high_school = $validated['high_school'];
        $alumnus->graduation_date = $validated['graduation_date'];
        $alumnus->further_course_detailed = $validated['further_course_detailed'];
        $alumnus->start_of_membership = $validated['start_of_membership'];
        $alumnus->recognations = $validated['recognations'];
        $alumnus->research_field_detailed = $validated['research_field_detailed'];
        $alumnus->links = $validated['links'];
        $alumnus->works = $validated['works'];
        //$alumnus->scientific_degrees = $validated['scientific_degrees'];
        $alumnus->save();

        if (isset($validated["university_faculties"])) {
            $ids = UniversityFaculty::all()->whereIn('name', $validated['university_faculties'])->pluck('id')->toArray();
            $alumnus->university_faculties()->sync($ids);
        }

        if (isset($validated["majors"])) {
            $ids = UniversityFaculty::all()->whereIn('name', $validated['majors'])->pluck('id')->toArray();
            $alumnus->majors()->sync($ids);
        }

        if (isset($validated["further_courses"])) {
            $ids = UniversityFaculty::all()->whereIn('name', $validated['further_courses'])->pluck('id')->toArray();
            $alumnus->further_courses()->sync($ids);
        }

        if (isset($validated["research_fields"])) {
            $ids = UniversityFaculty::all()->whereIn('name', $validated['research_fields'])->pluck('id')->toArray();
            $alumnus->research_fields()->sync($ids);
        }

        Session::flash('alumnus_updated');
        return Redirect::route('alumni.show', $alumnus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alumnus $alumnus)
    {
        // TODO: authorize
        $alumnus->delete();
        Session::flash('alumnus_deleted', $alumnus->name);
        return Redirect::route('alumni.index');

    }
}
