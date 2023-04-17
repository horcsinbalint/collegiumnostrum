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

    const ALPHABET="ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**Extracts the rows from the worksheet into an array, from $startingRow (starting from zero) until the end, from the first column until $lastColumn (starting from zero).
     * $lastColumn must be <26 for now.
     */
    public static function worksheet_to_array(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet, int $startingRow, int $lastColumn): array
    {
        $highestRow = $worksheet->getHighestRow();
        $arr=array();
        $lastColLet = AlumnusController::ALPHABET[$lastColumn];
        for ($i = $startingRow+1; $i <= $highestRow; ++$i) //indexing starts from 1 here
        {
            $arr[] = array_values( //converting to an indexed array; otherwise it would have to be indexed by letters
                $worksheet->rangeToArray( //appending it to the end
                    "A$i:$lastColLet$i",     // The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                )[$i] //it would otherwise add an extra dimension
            );
        }
        return $arr;
    }

    /**Maps extensions to PhpSpreadsheet's file descriptors. */
    const EXTENSION_TO_DESCRIPTOR = [
        'txt' => 'Csv', //Laravel recognizes csv files as txt
        'ods' => 'Ods',
        'xls' => 'Xls',
        'xlsx' => 'Xlsx'
    ];

    /**
     * Handles a request with an uploaded worksheet file that contains more than one alumni.
     * Extracts the data and stores it in new Alumnus objects.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import_store(Request $request)
    {
        $request->validate(
            [
                'file' =>  'file',
            ]
        );
        
        $file = $request->file('file');
        if (null == $file)
        {
            return redirect()->back()->with('message', 'Nincs kiválasztva fájl.');
        }

        $extension = $file->extension(); //Laravel guesses the extension based on file content
        if (!isset( AlumnusController::EXTENSION_TO_DESCRIPTOR[$extension] )) {
            return redirect()->back()->with('error', 'Nem támogatott fájlformátum.');
        }

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(
            AlumnusController::EXTENSION_TO_DESCRIPTOR[$extension]
        );
        $reader->setReadDataOnly(true); //we don't care about formatting
        //maybe a ReadFilter for only the cells in the appropriate columns?
        $spreadsheet = $reader->load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();

        $rows = AlumnusController::worksheet_to_array($worksheet, 1, 15);

        //TODO: validating data from spreadsheet

        $len = count($rows);
        if ($len > 0)
        {
            $firstone = $rows[0][0];
            foreach ($rows as $row)
            {
                $alumnus = Alumnus::factory()->create([
                    'name' => $row[0],
                    'email' => $row[1],
                    'birth_date' => $row[2],
                    'birth_place' => $row[3],
                    'high_school' => $row[4],
                    'graduation_date' => $row[5],
                    'start_of_membership' => $row[6],
                    'further_course_detailed' => $row[7],
                    'recognations' => $row[8],
                    'research_field_detailed' => $row[9],
                    'links' => $row[10],
                    'works' => $row[11],
                ]);
                $names[] = $row[0];
            }

            --$len;
            //for some reason this does not work
            return redirect()->route('alumni.index')
                ->with('message', "Added $firstone and $len others");
        } else
        {
            return redirect()->back()
                ->with('message', 'A feltöltött fájl nem tartalmaz alumnikat.');
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