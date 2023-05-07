<?php

namespace App\Http\Controllers;

use App\Models\Alumnus;
use App\Policies\AlumnusPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Redirect;

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
            ]);
        } else {
            return view('alumni.index', [
                'alumni' => \App\Models\Alumnus::where('is_draft', false)->paginate(10),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: kar, szak, stb. átadása
        return view('alumni.create_or_edit', [
            ''
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
                'further_course_detailed' => 'nullable|max:255',
                'start_of_membership' => 'nullable|numeric|gt:1930',
                'recognations' => 'nullable|max:255',
                'research_field_detailed' => 'nullable|max:255',
                'links' => 'nullable|max:255',
                'works' => 'nullable|max:255',
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
        return Alumnus::factory()->create([
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

        Session::flash('alumnus_created', $alumnus->name);

        if ($isDraft) {
            return "Az adatokat elmentettük; egy adminisztrátor jóváhagyása után lesznek elérhetőek. Köszönjük!";
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
                'alumnus' => $alumnus,
            ]);
        } else abort(403);
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
        $user = Auth::user();
        // it will be a draft if:
        //   they are guests, or
        //   they are registered and cannot create non-draft entries but can create drafts
        if((!$user) || !$user->can('update', $alumnus)) {
            
            $this->authorize('createDraftFor', $alumnus); //this also ensures $alumnus is not a draft

            $draftAlumnus = AlumnusController::validateAndStore($request, true, $alumnus->id);
            $alumnus->pair_id = $draftAlumnus->id;
            $alumnus->save();

            return "Az adatokat elmentettük; egy adminisztrátor jóváhagyása után lesznek elérhetőek. Köszönjük!";
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
            return redirect()->route('alumni.show', $alumnus)->with('message', 'Sikeres módosítás');
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

            $alumnus->id = $originalPairId;
            $alumnus->save();
        } else { //if it is null
            $alumnus->is_draft = false;
            $alumnus->save();
        }

        return redirect()->route('alumni.show', $alumnus)->with('message', "Sikeresen jóváhagyva és publikálva");
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
        //
    }
}
