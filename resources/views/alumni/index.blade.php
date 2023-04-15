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
                {{-- TODO: Links, policy --}}
                <a href="{{ route('alumni.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Új hozzáadása </a>
                {{-- TODO: Links, policy --}}
                <a href="{{ route('alumni.import.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Importálás </a>
            </div>
        </div>
    </div>

    {{-- TODO: Session flashes --}}

    <div class="row mt-3">
    <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Keresés
                        </div>
                        <div class="card-body">
                            {{-- TODO: Read categories from DB --}}
                            @foreach (['primary', 'secondary','danger', 'warning', 'info', 'dark'] as $category)
                                <a href="#" class="text-decoration-none">
                                    <span class="badge bg-{{ $category }}">{{ $category }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12 col-lg-12">
            <div class="row">
                {{-- TODO: Read posts from DB --}}

                @forelse ($alumni as $alumnus)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100">

                            <div class="card-body">
                                {{-- TODO: Title --}}
                                <h5 class="card-title mb-0">{{ $alumnus->name }}</h5>
                                <p class="card-text mt-0">{{ $alumnus->birth_date }}</p>
<!--
                                <p class="small mb-0">
                                    <span class="me-2">
                                        <i class="fas fa-user"></i>
                                        {{-- TODO: Author --}}
                                        <span>By Author</span>
                                    </span>

                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        {{-- TODO: Date --}}
                                        <span>01/01/2022</span>
                                    </span>
                                </p>

                                {{-- TODO: Read post categorismalles from DB --}}
                                @foreach (['primary', 'secondary','danger', 'warning', 'info', 'dark'] as $category)
                                    <a href="#" class="text-decoration-none">
                                        <span class="badge bg-{{ $category }}">{{ $category }}</span>
                                    </a>
                                @endforeach

                                {{-- TODO: Short desc --}}
                                <p class="card-text mt-1">Short description</p>
                                -->
                            </div>
                            <div class="card-footer">
                                {{-- TODO: Link --}}
                                <a href="{{ route('alumni.show', $alumnus) }}" class="btn btn-primary">
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

            <div class="d-flex justify-content-center">
                {{-- TODO: Pagination --}}
                {{ $alumni->links() }}
            </div>

        </div>

    </div>
</div>
@endsection
