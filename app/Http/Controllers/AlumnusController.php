<?php

namespace App\Http\Controllers;

use App\Models\Alumnus;
use \App\Models\UniversityFaculty;
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
        // TODO: kar, szak, stb. átadása
        return view('alumni.create', [
            'university_faculties' => UniversityFaculty::$university_faculties_enum,
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
        // TODO: kar, szak stb. mentése
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
        $existing_university_faculties = Arr::flatten(UniversityFaculty::select('name')->get()->makeHIdden('pivot')->toArray());
        $missing_university_faculties = array_diff($validated['university_faculties'], $existing_university_faculties);
        foreach ($missing_university_faculties as $faculty) {
            UniversityFaculty::factory()->create([
                'name' => $faculty
            ]);
        }
        $university_faculty_ids = Arr::flatten(UniversityFaculty::select('id')->whereIn('name', $validated['university_faculties'])->get()->makeHIdden('pivot')->toArray());
        if (isset($validated["university_faculties"])) {
            $alumnus->university_faculties()->sync($university_faculty_ids);
        }

        // TODO: a többi

        // Session::flash('alumnus_created', $alumnus->name);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alumnus $alumnus)
    {
        //
    }
}
