@extends('layouts.app')
@section('title', 'Alumni')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Alumni tagok</h1>
        </div>
        <div class="col-12 col-md-4">
            <div class="float-lg-end">
                @can('create', \App\Models\Alumnus::class)
                {{-- TODO: Links, policy --}}
                <a href="{{ route('alumni.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Új hozzáadása </a>
                {{-- TODO: Links, policy --}}
                <a href="#" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Importálás </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- TODO: Session flashes --}}
    @if (Session::has('alumnus_created'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('alumnus_created') }} hozzáadva!
        </div>
    @endif

    @if (Session::has('alumnus_deleted'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('alumnus_deleted') }} törölve az adatbázisból!
        </div>
    @endif

    <div class="row mt-3">
    <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Keresés
                        </div>
                        <div class="card-body">
                        <form method="GET" action="{{ route('alumni.search') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="text" class="form-control" name="name" placeholder="Név">
                            <input type="text" class="form-control" placeholder="Collegiumi tagság kezdete" pattern="\d{4}" maxlength="4" name="start_of_membership">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select name="major" class="form-control" id="majorDropdown">
                                            <option value="">Szak kiválasztása</option>
                                            @foreach ($majors_enum as $major)
                                                <option value="{{ $major }}">{{ $major }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select name="further_course" class="form-control" id="furtherCourseDropdown">
                                            <option value="">További pálya kiválasztása</option>
                                            @foreach ($further_courses_enum as $further_course)
                                                <option value="{{ $further_course }}">{{ $further_course }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select name="scientific_degree" class="form-control" id="scientificDegreeDropdown">
                                            <option value="">Tudományos fokozat kiválasztása</option>
                                            @foreach ($scientific_degrees_enum as $scientific_degree)
                                                <option value="{{ $scientific_degree }}">{{ $scientific_degree }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select name="research_field" class="form-control" id="researchFieldDropdown">
                                            <option value="">Kutatási terület kiválasztása</option>
                                            @foreach ($research_fields_enum as $research_field)
                                                <option value="{{ $research_field }}">{{ $research_field }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Keresés</button>
                        </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        @cannot('create', \App\Models\Alumnus::class)
        <p class="lead">Ha nem talál valakit, <a href="mailto:root@eotvos.elte.hu">írjon nekünk</a>.</p>
        @endcan
        <div class="col-12 col-lg-12">
            <div class="row">
                {{-- TODO: Read posts from DB --}}

                @forelse ($alumni as $alumnus)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100">

                            @if ($alumnus->is_draft)
                            <div class="card-body bg-info">
                            @else
                            <div class="card-body">
                            @endif
                                {{-- TODO: Title --}}
                                <h5 class="card-title mb-0">{{ $alumnus->name }}</h5>
                                @if ($alumnus->is_draft)
                                <p class="card-text text-danger">Jóváhagyásra vár</p>
                                @endif
                                <p class="small mb-0">
                                    @isset($alumnus->start_of_membership)
                                    <span class="me-2">
                                        <i class="fas fa-user"></i>
                                        <span>Collegiumi tagság kezdete: {{ $alumnus->start_of_membership }}</span>
                                    </span>
                                    <br>
                                    @endisset

                                    @if($alumnus->majors()->exists())
                                     @php
                                        $majors_array = Arr::flatten($alumnus->majors()->select('name')->get()->makeHIdden('pivot')->toArray());
                                    @endphp
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        <span>Egyetemi szak(ok): {{ implode(", ", $majors_array) }}</span>
                                    </span>
                                    @endif
                                </p>
                            </div>
                            <div class="card-footer">
                                {{-- TODO: Link --}}
                                <a href="{{ route('alumni.show', $alumnus->id) }}" class="btn btn-primary">
                                    <span>Részletek</span> <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            Nem található alumni!
                        </div>
                    </div>
                @endforelse
            </div>
            @if(isset($alumni) && !empty($alumni) && !isset($search))
                <div class="d-flex justify-content-center">
                    {{ $alumni->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#majorDropdown').on('change', function() {
            var selectedMajor = $(this).val();

            // Perform actions based on the selected major
            if (selectedMajor) {
                // Example: Display selected major in console
                // console.log('Selected Major:', selectedMajor);

                // Example: Make an AJAX request to fetch related data based on the selected major
                $.ajax({
                    url: '/api/majors/' + selectedMajor,
                    method: 'GET',
                    success: function(response) {
                        // Handle the response data
                        console.log('Response:', response);
                    },
                    error: function(xhr, status, error) {
                        // Handle the error
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#furtherCourseDropdown').on('change', function() {
            var selectedMajor = $(this).val();

            // Perform actions based on the selected major
            if (selectedMajor) {
                // Example: Display selected major in console
                // console.log('Selected Major:', selectedMajor);

                // Example: Make an AJAX request to fetch related data based on the selected major
                $.ajax({
                    url: '/api/further_courses/' + selectedMajor,
                    method: 'GET',
                    success: function(response) {
                        // Handle the response data
                        console.log('Response:', response);
                    },
                    error: function(xhr, status, error) {
                        // Handle the error
                        console.error('Error:', error);
                    }
                });
            }
        });
    });
</script>

@endsection
