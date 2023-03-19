@extends('layouts.app')
@section('title', 'Alumnus hozzáadása')

@section('content')
<div class="container">
    <h1>Alumnus hozzáadása</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{ route('alumni.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a kezdőlapra </a>
    </div>

    {{-- TODO: action, method, enctype --}}
    <form action="{{ route('alumni.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- TODO: Validation --}}

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
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
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
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
                <input type="number" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
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
                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place') }}">
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
                <input type="text" class="form-control @error('high_school') is-invalid @enderror" id="high_school" name="high_school" value="{{ old('high_school') }}">
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
                <input type="number" class="form-control @error('graduation_date') is-invalid @enderror" id="graduation_date" name="graduation_date" value="{{ old('graduation_date') }}">
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
                <input type="number" class="form-control @error('start_of_membership') is-invalid @enderror" id="start_of_membership" name="start_of_membership" value="{{ old('start_of_membership') }}">
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
                <textarea rows="5" class="form-control @error('further_course_detailed') is-invalid @enderror" id="further_course_detailed" name="further_course_detailed">{{ old('further_course_detailed') }}</textarea>
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
                <textarea rows="5" class="form-control @error('recognations') is-invalid @enderror" id="recognations" name="recognations">{{ old('recognations') }}</textarea>
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
                <textarea rows="5" class="form-control @error('works') is-invalid @enderror" id="works" name="works">{{ old('works') }}</textarea>
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
                <textarea rows="5" class="form-control @error('research_field_detailed') is-invalid @enderror" id="research_field_detailed" name="research_field_detailed">{{ old('research_field_detailed') }}</textarea>
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
                <input type="text" class="form-control @error('links') is-invalid @enderror" id="links" name="links" value="{{ old('links') }}">
                @error('links')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mentés</button>
        </div>
    </form>
</div>
@endsection
