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
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Policies\AlumnusPolicy;

class AlumnusController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->can('create', Alumnus::class)) {
            //if there is a draft pair, we only show the drafts
            $idsHavingDraftPairs = DB::table('alumni')->where('is_draft', false)->whereNotNull('pair_id')->pluck('id');

            return view('alumni.index', [
                'alumni' => \App\Models\Alumnus::whereNotIn('id', $idsHavingDraftPairs)->paginate(10),
                'majors_enum' => Major::$majors_enum,
                'further_courses_enum' => FurtherCourse::$further_courses_enum,
                'scientific_degrees_enum' => ScientificDegree::$scientific_degrees_enum,
                'research_fields_enum' => ResearchField::$research_fields_enum,
            ]);
        } else {
            return view('alumni.index', [
                'alumni' => \App\Models\Alumnus::where('is_draft', false)->paginate(10),
                'majors_enum' => Major::$majors_enum,
                'further_courses_enum' => FurtherCourse::$further_courses_enum,
                'scientific_degrees_enum' => ScientificDegree::$scientific_degrees_enum,
                'research_fields_enum' => ResearchField::$research_fields_enum,
            ]);
        }
    }

    public function searchAlumni(Request $request)
    {
        // TODO: megoldani hogy pagination oldal váltásnál is megmaradjon a szűrés
        // vagy legalább ha üres a request akkor legyen pagination

        $name = $request->input('name');
        $start_of_membership = $request->input('start_of_membership');
        $major = $request->input('major');
        $further_course = $request->input('further_course');
        $scientific_degree = $request->input('scientific_degree');
        $research_field = $request->input('research_field');

        $query = Alumnus::query();

        if (isset($name)) {
            $query->where('name', 'LIKE', "%$name%");
        }

        if (isset($start_of_membership)) {
            $query->where('start_of_membership', $start_of_membership);
        }

        if (isset($major)) {
            $query->whereHas('majors', function (Builder $q) use ($major) {
                $q->where('name', $major);
            });
        }

        if (isset($further_course)) {
            $query->whereHas('further_courses', function (Builder $q) use ($further_course) {
                $q->where('name', $further_course);
            });
        }

        if (isset($scientific_degree)) {
            $query->whereHas('scientific_degrees', function (Builder $q) use ($scientific_degree) {
                $q->where('name', $scientific_degree);
            });
        }

        if (isset($research_field)) {
            $query->whereHas('research_fields', function (Builder $q) use ($research_field) {
                $q->where('name', $research_field);
            });
        }

        $user = Auth::user();
        if ($user && $user->can('create', Alumnus::class)) {
            $idsHavingDraftPairs = DB::table('alumni')->where('is_draft', false)->whereNotNull('pair_id')->pluck('id');
            $alumni = $query->whereNotIn('id', $idsHavingDraftPairs)->paginate(10);
        } else {
            $alumni = $query->where('is_draft', false)->paginate(10);
        }

        return view('alumni.index', [
            'alumni' => $alumni,
            'search' => true,
            'majors_enum' => Major::$majors_enum,
            'further_courses_enum' => FurtherCourse::$further_courses_enum,
            'scientific_degrees_enum' => ScientificDegree::$scientific_degrees_enum,
            'research_fields_enum' => ResearchField::$research_fields_enum,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('alumni.create_or_edit', [
            'university_faculties' => UniversityFaculty::$university_faculties_enum,
            'majors' => Major::$majors_enum,
            'further_courses' => FurtherCourse::$further_courses_enum,
            'scientific_degrees' => ScientificDegree::$scientific_degrees_enum,
            'research_fields' => ResearchField::$research_fields_enum,
        ]);
    }

    /**
     * Validates a store/update request and returns an array containing the validated keys and values.
     */
    private static function validateRequest(Request $request): array
    {
        // TODO: kar, szak stb.
        return $request->validate(
            [
                'name' => 'required|min:3',
                'email' => 'nullable|email',
                'birth_date' => 'nullable|numeric|gt:1930',
                'birth_place' => 'nullable|min:3',
                'high_school' => 'nullable|min:3',
                'graduation_date' => 'nullable|numeric|gt:1930',
                'further_course_detailed' => 'nullable|max:2000',
                'start_of_membership' => 'nullable|numeric|gt:1930',
                'recognations' => 'nullable|max:2000',
                'research_field_detailed' => 'nullable|max:2000',
                'links' => 'nullable|max:2000',
                'works' => 'nullable|max:2000',
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
    }

    /**
     * Validates and then creates an alumnus from
     * a given request, a given draft bit and a given pair id (the latter can be null too).
     * This function is used both in `store` and in `update`.
     */
    private static function validateAndStore(Request $request, bool $isDraft, ?int $pairId) : Alumnus
    {
        $validated = AlumnusController::validateRequest($request);

        // TODO: id?
        $alumnus = Alumnus::factory()->create([
            'is_draft' => $isDraft,
            'pair_id' => $pairId,
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
        AlumnusController::synchroniseConnections($alumnus, $validated);
        return $alumnus;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        // it will be a draft if:
        //   they are guests, or
        //   they are registered and cannot create non-draft entries but can create drafts
        $isDraft = (!$user) || (!$user->can('create', Alumnus::class) && $user->can('createDraft', Alumnus::class));

        $alumnus = AlumnusController::validateAndStore($request, $isDraft, null);

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

        if ($isDraft) {
            return Redirect::route('alumni.index')->with('success','Az adatokat elmentettük; egy adminisztrátor jóváhagyása után lesznek elérhetőek. Köszönjük!');
        } else {
            return Redirect::route('alumni.show', $alumnus);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function show(Alumnus $alumnus)
    {
        if ($alumnus->is_draft) {
            $this->authorize('viewDraft', Alumnus::class);
        }
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
        $user = Auth::user();
        if (!$user || $user->can('update', $alumnus) || $user->can('createDraftFor', $alumnus)) { //now this is true for everyone
            return view('alumni.create_or_edit', [
                'university_faculties' => UniversityFaculty::$university_faculties_enum,
                'majors' => Major::$majors_enum,
                'further_courses' => FurtherCourse::$further_courses_enum,
                'scientific_degrees' => ScientificDegree::$scientific_degrees_enum,
                'research_fields' => ResearchField::$research_fields_enum,
                'alumnus' => $alumnus,
            ]);
        } else abort(403);
    }

    /**
     * Synchronises the alumnus' connections with other tables.
     */
    private static function synchroniseConnections(Alumnus $alumnus, array $validated): void {
        if (isset($validated["university_faculties"])) {
            $ids = UniversityFaculty::all()->whereIn('name', $validated['university_faculties'])->pluck('id')->toArray();
            $alumnus->university_faculties()->sync($ids);
        }

        if (isset($validated["majors"])) {
            $ids = Major::all()->whereIn('name', $validated['majors'])->pluck('id')->toArray();
            $alumnus->majors()->sync($ids);
        }

        if (isset($validated["further_courses"])) {
            $ids = FurtherCourse::all()->whereIn('name', $validated['further_courses'])->pluck('id')->toArray();
            $alumnus->further_courses()->sync($ids);
        }

        if (isset($validated["research_fields"])) {
            $ids = ResearchField::all()->whereIn('name', $validated['research_fields'])->pluck('id')->toArray();
            $alumnus->research_fields()->sync($ids);
        }
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

        $user = Auth::user();
        // it will be a draft if:
        //   they are guests, or
        //   they are registered and cannot create non-draft entries but can create drafts
        if((!$user) || !$user->can('update', $alumnus)) {
            
            $this->authorize('createDraftFor', $alumnus); //this also ensures $alumnus is not a draft

            $draftAlumnus = AlumnusController::validateAndStore($request, true, $alumnus->id);
            $alumnus->pair_id = $draftAlumnus->id;
            $alumnus->save();

            Session::flash('draft_changes_saved');
            return Redirect::route('alumni.show', $alumnus);
        } else {
            //they are no guests and they can edit the non-draft directly
            //this also ensures $alumnus is not a draft

            $validated = AlumnusController::validateRequest($request);
            $alumnus->update([
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
            AlumnusController::synchroniseConnections($alumnus, $validated);
            
            Session::flash('alumnus_updated', $alumnus->name);
            return Redirect::route('alumni.show', $alumnus);
        }
    }

    /**
     * Accept a draft created from outside.
     * Changes the id to the original's id, then deletes the original.
     * If there is no original, it simply changes the is_draft bit to false.
     * 
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function accept(Alumnus $alumnus)
    {
        $this->authorize('accept', $alumnus); //this also guarantees that $alumnus really is a draft

        if ($alumnus->pair_id) {
            //the order is important!
            $originalPairId = $alumnus->pair_id;
            $originalPair = Alumnus::find($originalPairId);
            $alumnus->pair_id = null;
            $alumnus->is_draft = false;
            $alumnus->save();

            $originalPair->delete();

            $alumnus->id = $originalPairId; //because of onUpdate('cascade'), this will update the connection tables, too
            $alumnus->save();
        } else { //if it is null
            $alumnus->is_draft = false;
            $alumnus->save();
        }

        Session::flash('alumnus_accepted', $alumnus->name);
        return redirect()->route('alumni.show', $alumnus);
    }

    /**
     * Reject a draft created from outside. Simply deletes the draft.
     * 
     * @param  \App\Models\Alumnus  $alumnus
     * @return \Illuminate\Http\Response
     */
    public function reject(Alumnus $alumnus)
    {
        $this->authorize('reject', $alumnus); //this also guarantees that $alumnus really is a draft

        $pairId = $alumnus->pair_id;

        Session::flash('alumnus_rejected', $alumnus->name);

        if ($pairId) { //if there is a non-draft pair
            $pairAlumnus = Alumnus::find($pairId);
            $pairAlumnus->pair_id = null;
            $pairAlumnus->save();
            $alumnus->delete();
            return redirect()->route('alumni.show', $pairAlumnus)->with('message', "Módosítások elutasítva és törölve");
        } else {
            $alumnus->delete();
            return redirect()->route('alumni.index')->with('message', "Módosítások elutasítva és törölve");
        }
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
