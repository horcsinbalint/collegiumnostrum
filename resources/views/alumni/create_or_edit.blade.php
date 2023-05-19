@extends('layouts.app')
@section('title', 'Alumnus hozzáadása')

{{-- if $alumnus is set, we are editing an existing entry (or suggesting changes to it);
     otherwise, we are creating a new entry --}}

@section('content')
<div class="container">
    @if(isset($alumnus))
    @can('create', \App\Models\Alumnus::class)
    <h1>Alumnus szerkesztése</h1>
    @else
    <h1>Új információ beküldése</h1>
    <p class="lead">Töltse ki a megfelelő mezőket új információkkal.<br>
    <strong>Figyelem:</strong> a változtatások csak adminisztrátori jóváhagyás után jelennek meg!</p>
    @endcan
    @else
    <h1>Alumnus hozzáadása</h1>
    @endif
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{ route('alumni.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a kezdőlapra </a>
    </div>

    {{-- TODO: action, method, enctype --}}
    @if(isset($alumnus))
    <form action="{{ route('alumni.update', $alumnus) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
    @else
    <form action="{{ route('alumni.store') }}" method="POST" enctype="multipart/form-data">
    @endif
        @csrf

        {{-- TODO: Validation --}}

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    @if(isset($alumnus))
                    value="{{ $alumnus->name }}"
                    @else
                    value="{{ old('name') }}"
                    @endif
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="email" class="col-sm-2 col-form-label">E-mail cím</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    @if(isset($alumnus))
                    value="{{ $alumnus->email }}"
                    @else
                    value="{{ old('email') }}"
                    @endif
                >
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="birth_date" class="col-sm-2 col-form-label">Születési év</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date"
                    @if(isset($alumnus))
                    value="{{ $alumnus->birth_date }}"
                    @else
                    value="{{ old('birth_date') }}"
                    @endif
                >
                @error('birth_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="birth_place" class="col-sm-2 col-form-label">Születési hely (város)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place"
                    @if(isset($alumnus))
                    value="{{ $alumnus->birth_place }}"
                    @else
                    value="{{ old('birth_place') }}"
                    @endif
                >
                @error('birth_place')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="high_school" class="col-sm-2 col-form-label">Középiskolai tanulmányok</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('high_school') is-invalid @enderror" id="high_school" name="high_school"
                    @if(isset($alumnus))
                    value="{{ $alumnus->high_school }}"
                    @else
                    value="{{ old('high_school') }}"
                    @endif
                >
                @error('high_school')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="graduation_date" class="col-sm-2 col-form-label">Érettségi éve</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('graduation_date') is-invalid @enderror" id="graduation_date" name="graduation_date"
                    @if(isset($alumnus))
                    value="{{ $alumnus->graduation_date }}"
                    @else
                    value="{{ old('graduation_date') }}"
                    @endif
                >
                @error('graduation_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="start_of_membership" class="col-sm-2 col-form-label">Collegiumi tagság kezdete (szakkollégiumi felvétel éve)</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('start_of_membership') is-invalid @enderror" id="start_of_membership" name="start_of_membership"
                    @if(isset($alumnus))
                    value="{{ $alumnus->start_of_membership }}"
                    @else
                    value="{{ old('start_of_membership') }}"
                    @endif
                >
                @error('start_of_membership')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- TODO: university_faculty, major, further_course, scientific_degree (and somehow year), research_field -->



        {{--
            Handling invalid input fields:

            <input type="text" class="form-control is-invalid" ...>
            <div class="invalid-feedback">
                Message
            </div>
        --}}

        <div class="form-group row mb-3">
            <label for="further_course_detailed" class="col-sm-2 col-form-label">További pálya részletesen</label>
            <div class="col-sm-10">
                {{-- this has to be indented this way; otherwise whitespace will appear in the text area --}}
<textarea rows="5" class="form-control @error('further_course_detailed') is-invalid @enderror" id="further_course_detailed" name="further_course_detailed">
@if(isset($alumnus))
{{ $alumnus->further_course_detailed }}</textarea>
@else
{{ old('further_course_detailed') }}</textarea>
@endif
                @error('further_course_detailed')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="recognations" class="col-sm-2 col-form-label">Elismerések</label>
            <div class="col-sm-10">
<textarea rows="5" class="form-control @error('recognations') is-invalid @enderror" id="recognations" name="recognations">
@if(isset($alumnus))
{{ $alumnus->recognations }}</textarea>
@else
{{ old('recognations') }}</textarea>
@endif
                @error('recognations')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="works" class="col-sm-2 col-form-label">Főbb művek (soronként egyet, legfeljebb 5)</label>
            <div class="col-sm-10">
<textarea rows="5" class="form-control @error('works') is-invalid @enderror" id="works" name="works">
@if(isset($alumnus))
{{ $alumnus->works }}</textarea>
@else
{{ old('works') }}</textarea>
@endif
                @error('works')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="research_field_detailed" class="col-sm-2 col-form-label">Kutatási terület részletesen</label>
            <div class="col-sm-10">
<textarea rows="5" class="form-control @error('research_field_detailed') is-invalid @enderror" id="research_field_detailed" name="research_field_detailed">
@if(isset($alumnus))
{{ $alumnus->research_field_detailed }}</textarea>
@else
{{ old('research_field_detailed') }}</textarea>
@endif
                @error('research_field_detailed')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="links" class="col-sm-2 col-form-label">Linkek</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('links') is-invalid @enderror" id="links" name="links"
                    @if(isset($alumnus))
                    value="{{ $alumnus->links }}"
                    @else
                    value="{{ old('links') }}"
                    @endif
                >
                @error('links')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="text-center">
            @if(isset($alumnus))

            @can('update', $alumnus)
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mentés</button>
            @elsecan('createDraftFor', $alumnus)
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane-top"></i> Beküldés jóváhagyásra</button>
            @endcan

            @else

            @can('create', \App\Models\Alumnus::class)
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mentés</button>
            @elsecan('createDraft', \App\Models\Alumnus::class)
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane-top"></i> Beküldés jóváhagyásra</button>
            @endcan

            @endif
        </div>
    </form>
</div>
@endsection
