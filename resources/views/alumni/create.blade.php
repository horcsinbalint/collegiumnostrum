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
            <label for="university_faculties" class="col-sm-2 col-form-label py-0">Egyetemi kar</label>
            <div class="col-sm-10">
                @forelse ($university_faculties as $faculty)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $faculty }}"
                            id="faculty-{{ $faculty }}"
                            name="university_faculties[]"
                            {{-- TODO: name, checked --}}

                            @checked(
                                is_array(old('university_faculties')) &&
                                in_array(strval($faculty), old('university_faculties', [])))
                            )
                        >
                        {{-- TODO --}}
                        <label for="faculty-{{ $faculty }}" class="form-check-label">
                            <span>{{ $faculty }}</span>
                        </label>
                    </div>
                @empty
                    <p>Nincsenek egyetemi karok</p>
                @endforelse

                {{-- {{ in_array(strval(1), old('university_faculties', [])) }}
                {{ json_encode( old('university_faculties', [])) }} --}}

                @foreach ($errors->get('university_faculties.*') as $message)
                    <div class="text-danger">
                        {{ $message }}<br>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="majors" class="col-sm-2 col-form-label py-0">Egyetemi szak</label>
            <div class="col-sm-10">
                @forelse ($majors as $major)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $major }}"
                            id="major-{{ $major }}"
                            name="majors[]"
                            {{-- TODO: name, checked --}}

                            @checked(
                                is_array(old('majors')) &&
                                in_array(strval($major), old('majors', [])))
                            )
                        >
                        {{-- TODO --}}
                        <label for="major-{{ $major }}" class="form-check-label">
                            <span>{{ $major }}</span>
                        </label>
                    </div>
                @empty
                    <p>Nincsenek egyetemi szakok</p>
                @endforelse

                {{-- {{ in_array(strval(1), old('majors', [])) }}
                {{ json_encode( old('majors', [])) }} --}}

                @foreach ($errors->get('majors.*') as $message)
                    <div class="text-danger">
                        {{ $message }}<br>
                    </div>
                @endforeach

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

        <div class="form-group row mb-3">
            <label for="further_courses" class="col-sm-2 col-form-label py-0">Egyetem utáni pálya</label>
            <div class="col-sm-10">
                @forelse ($further_courses as $further_course)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $further_course }}"
                            id="further_course-{{ $further_course }}"
                            name="further_courses[]"
                            {{-- TODO: name, checked --}}

                            @checked(
                                is_array(old('further_courses')) &&
                                in_array(strval($further_course), old('further_courses', [])))
                            )
                        >
                        {{-- TODO --}}
                        <label for="further_course-{{ $further_course }}" class="form-check-label">
                            <span>{{ $further_course }}</span>
                        </label>
                    </div>
                @empty
                    <p>Nincsenek egyetem utáni pályák</p>
                @endforelse

                {{-- {{ in_array(strval(1), old('further_courses', [])) }}
                {{ json_encode( old('further_courses', [])) }} --}}

                @foreach ($errors->get('further_courses.*') as $message)
                    <div class="text-danger">
                        {{ $message }}<br>
                    </div>
                @endforeach

            </div>
        </div>

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
            <label for="scientific_degrees" class="col-sm-2 col-form-label py-0">Tudományos fokozat</label>
            <div class="col-sm-10">
                @forelse ($scientific_degrees as $scientific_degree)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $scientific_degree }}"
                            id="scientific_degree-{{ $scientific_degree }}"
                            name="scientific_degrees[]"
                            {{-- TODO: name, checked --}}

                            @checked(
                                is_array(old('scientific_degrees')) &&
                                in_array(strval($scientific_degree), old('scientific_degrees', [])))
                            )
                        >
                        {{-- TODO --}}
                        <label for="scientific_degree-{{ $scientific_degree }}" class="form-check-label">
                            <span>{{ $scientific_degree }}</span>
                        </label>
                    </div>
                @empty
                    <p>Nincsenek tudományos fokozatok</p>
                @endforelse

                {{-- {{ in_array(strval(1), old('scientific_degrees', [])) }}
                {{ json_encode( old('scientific_degrees', [])) }} --}}

                @foreach ($errors->get('scientific_degrees.*') as $message)
                    <div class="text-danger">
                        {{ $message }}<br>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="doctor_year" class="col-sm-2 col-form-label">Egyetemi doktor tudományos fokozat megszerzésének éve</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('doctor_year') is-invalid @enderror" id="doctor_year" name="doctor_year" value="{{ old('doctor_year') }}">
                @error('doctor_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="candidate_year" class="col-sm-2 col-form-label">Kandidátus tudományos fokozat megszerzésének éve</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('candidate_year') is-invalid @enderror" id="candidate_year" name="candidate_year" value="{{ old('candidate_year') }}">
                @error('candidate_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="mta_year" class="col-sm-2 col-form-label">Tudományok doktora/MTA doktora tudományos fokozat megszerzésének éve</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('mta_year') is-invalid @enderror" id="mta_year" name="mta_year" value="{{ old('mta_year') }}">
                @error('mta_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="phd_year" class="col-sm-2 col-form-label">PhD tudományos fokozat megszerzésének éve</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('phd_year') is-invalid @enderror" id="phd_year" name="phd_year" value="{{ old('phd_year') }}">
                @error('phd_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>


        <div class="form-group row mb-3">
            <label for="hab_year" class="col-sm-2 col-form-label">Habilitáció tudományos fokozat megszerzésének éve</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('hab_year') is-invalid @enderror" id="hab_year" name="hab_year" value="{{ old('hab_year') }}">
                @error('hab_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="dla_year" class="col-sm-2 col-form-label">DLA tudományos fokozat megszerzésének éve</label>
            <div class="col-sm-10">
                <input type="number" class="form-control @error('dla_year') is-invalid @enderror" id="dla_year" name="dla_year" value="{{ old('dla_year') }}">
                @error('dla_year')
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
            <label for="research_fields" class="col-sm-2 col-form-label py-0">Kutatási terület</label>
            <div class="col-sm-10">
                @forelse ($research_fields as $research_field)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $research_field }}"
                            id="research_field-{{ $research_field }}"
                            name="research_fields[]"
                            {{-- TODO: name, checked --}}

                            @checked(
                                is_array(old('research_fields')) &&
                                in_array(strval($research_field), old('research_fields', [])))
                            )
                        >
                        {{-- TODO --}}
                        <label for="research_field-{{ $research_field }}" class="form-check-label">
                            <span>{{ $research_field }}</span>
                        </label>
                    </div>
                @empty
                    <p>Nincsenek kutatási témák</p>
                @endforelse

                {{-- {{ in_array(strval(1), old('research_fields', [])) }}
                {{ json_encode( old('research_fields', [])) }} --}}

                @foreach ($errors->get('research_fields.*') as $message)
                    <div class="text-danger">
                        {{ $message }}<br>
                    </div>
                @endforeach

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
