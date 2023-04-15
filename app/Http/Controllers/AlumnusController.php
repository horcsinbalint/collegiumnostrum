<?php

namespace App\Http\Controllers;

use App\Models\Alumnus;
use Illuminate\Http\Request;

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
            'alumni' => \App\Models\Alumnus::paginate(10),
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
            ''
        ]);
    }

    /**
     * Show the form for importing new alumni from a spreadsheet file.
     * 
     * @return \Illuminate\Http\Response
     */
    public function import_create()
    {
        return view('alumni.import', ['']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: kar, szak stb.
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
            ]
        );

        // TODO: id?
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

        Session::flash('alumnus_created', $alumnus->name);

        // TODO: rather index?
        return Redirect::route('alumni.show', $alumnus);
    }

    public function import_store(Request $request)
    {
        $request->validate(
            [
                'file' =>  'file',
            ]
        );
        
        $file = $request->file('file');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Ods'); //TODO: check extension and select based on it
        $reader->setReadDataOnly(true); //we don't care about formatting
        //TODO: ReadFilter for only the cells in the appropriate rows?
        $spreadsheet = $reader->load($file->getRealPath());

        return response()->json($spreadsheet->getActiveSheet()
        ->rangeToArray(
            'A1:P1',     // The worksheet range that we want to retrieve
            NULL,        // Value that should be returned for empty cells
            TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
            TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
            TRUE         // Should the array be indexed by cell row and cell column
        ));
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