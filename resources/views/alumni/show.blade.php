@extends('layouts.app')
{{-- TODO: Post title --}}
@section('title', $alumnus->name)

@section('content')
<div class="container">

    {{-- TODO: Session flashes --}}
    @if (Session::has('alumnus_created'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('alumnus_created') }} hozzáadva!
        </div>
    @endif

    <a href="{{ route('alumni.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a kezdőlapra</a>

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>{{ $alumnus->name }}</h1>

            @if(isset($alumnus->birth_date) or isset($alumnus->birth_place) or isset($alumnus->high_school) or isset($alumnus->graduation_date))
            <div class="mb-2">

                <h2>Általános adatok</h2>

                @isset($alumnus->birth_date)
                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>Születési idő: {{ $alumnus->birth_date }}</span>
                    </p>
                @endisset

                @isset($alumnus->birth_place)
                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>Hely: {{ $alumnus->birth_place }}</span>
                    </p>
                @endisset

                @isset($alumnus->high_school)
                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>Középiskola: {{ $alumnus->high_school }}</span>
                    </p>
                @endisset

                @isset($alumnus->graduation_date)
                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>Érettségi éve: {{ $alumnus->graduation_date }}</span>
                    </p>
                @endisset
            </div>
            @endif

            @if($alumnus->university_faculties()->exists() or $alumnus->majors()->exists())
            <div class="mb-2">
                <h2>Egyetemi adatok</h2>
                @if($alumnus->university_faculties()->exists())
                    @php
                        $university_faculties_array = Arr::flatten($alumnus->university_faculties()->select('name')->get()->makeHIdden('pivot')->toArray());
                    @endphp

                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        @if(count($university_faculties_array) > 1)
                        <span>Egyetemi karok: {{ implode(", ", $university_faculties_array) }}</span>
                        @else
                        <span>Egyetemi kar: {{ implode(", ", $university_faculties_array) }}</h3></span>
                        @endif
                    </p>

                @endif

                @if($alumnus->majors()->exists())
                    @php
                        $majors_array = Arr::flatten($alumnus->majors()->select('name')->get()->makeHIdden('pivot')->toArray());
                    @endphp

                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        @if(count($majors_array) > 1)
                        <span>Egyetemi szakok: {{ implode(", ", $majors_array) }}</span>
                        @else
                        <span>Egyetemi szak: {{ implode(", ", $majors_array) }}</h3></span>
                        @endif
                    </p>
                @endif
            </div>
            @endif

            @if($alumnus->further_courses()->exists() or isset($alumnus->further_course_detailed) or $alumnus->scientific_degrees()->exists() or $alumnus->research_fields()->exists() or isset($alumnus->start_of_membership) or isset($alumnus->recognations) or isset($alumnus->research_field_detailed) or isset($alumnus->links) or isset($alumnus->works))
            <div class="mb-2">
                <h2>Pályafutás</h2>
                @if($alumnus->further_courses()->exists())
                    @php
                        $further_courses_array = Arr::flatten($alumnus->further_courses()->select('name')->get()->makeHIdden('pivot')->toArray());
                    @endphp

                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>Egyetem utáni pálya: {{ implode(", ", $further_courses_array) }}</span>
                    </p>
                @endif

                @isset($alumnus->further_course_detailed)
                    <p class="text mb-0">
                    <i class="fas fa-user"></i>
                        {!! nl2br(e($alumnus->further_course_detailed)) !!}
                    </p>

                @endisset

                @if($alumnus->scientific_degrees()->exists())
                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>Tudományos fokozat:</span>
                    </p>
                    @foreach ($alumnus->scientific_degrees as $degree)
                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>{{ $degree->name . (isset($degree->obtain_year) ? " (" . $degree->obtain_year . ")" : "") }}</span>
                    </p>
                    @endforeach
                @endif

                @isset($alumnus->start_of_membership)
                    <p class="text mb-0">
                    <i class="fas fa-user"></i>
                    <span>Collegiumi tagság kezdete: {{ $alumnus->start_of_membership }}</span>
                    </p>

                @endisset

                @isset($alumnus->recognations)
                    <p class="text mb-0">
                    <i class="fas fa-user"></i>
                    <span>Elismerések: {{ $alumnus->recognations }}</span>
                    </p>
                @endisset

                @if($alumnus->research_fields()->exists())
                    @php
                        $research_fields_array = Arr::flatten($alumnus->research_fields()->select('name')->get()->makeHIdden('pivot')->toArray());
                    @endphp

                    <p class="text mb-0">
                        <i class="fas fa-user"></i>
                        <span>Kutatási terület: {{ implode(", ", $research_fields_array) }}</span>
                    </p>
                @endif

                @isset($alumnus->research_field_detailed)
                    <p class="text mb-0">
                    <i class="fas fa-user"></i>
                        {!! nl2br(e($alumnus->research_field_detailed)) !!}
                    </p>
                @endisset

                @isset($alumnus->links)
                    <p class="text mb-0">
                    <i class="fas fa-user"></i>
                    MTMT hivatkozás vagy saját honlap/wikipédia szócikk:
                        {!! nl2br(e($alumnus->links)) !!}
                    </p>
                @endisset

                @isset($alumnus->works)
                    <p class="text mb-0">
                    <i class="fas fa-user"></i>
                        Főbb művek:
                        {!! nl2br(e($alumnus->works)) !!}
                    </p>
                @endisset
            </div>
            @endif

        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">

                {{-- TODO: Links, policy --}}
                <a role="button" class="btn btn-sm btn-primary" href="{{ route('alumni.edit', $alumnus) }}"><i class="far fa-edit"></i> Szerkesztés</a>

                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt">
                    <span></i> Törlés</span>
                </button>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-confirm-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Törlés megerősítése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Biztosan törölni szeretné a következő személyt az adatbázisból: <strong>{{ $alumnus->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-post-form').submit();"
                    >
                        Igen, törlés
                    </button>

                    <form id="delete-post-form" action="#" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
