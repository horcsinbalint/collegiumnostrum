@extends('layouts.app')
@section('title', 'Importálás fájlból')

@section('content')
<div class="container">
    <h1>Importálás fájlból</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{ route('alumni.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a kezdőlapra </a>
    </div>

    <p>Töltse ki a <a href="{{ asset('storage/spreadsheet_sample.ods'); }}">mintatáblázatot</a>, majd töltse fel ide.</p>

    {{-- TODO: action, method, enctype --}}
    <form action="{{ route('alumni.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- TODO: Validation --}}
        <label for="file">Fájl feltöltése (támogatott típusok: .xlsx, .ods, .csv):</label>
        <input type="file" id="file" name="file"
          accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.oasis.opendocument.spreadsheet,
                  text/csv" />

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Feltöltés</button>
        </div>
    </form>
</div>
@endsection
