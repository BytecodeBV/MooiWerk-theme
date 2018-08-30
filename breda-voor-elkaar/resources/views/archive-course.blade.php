@extends('layouts.app')

@section('content')
    @include('partials.page-header')
    <section class="vacancies bg-secondary">
        <div class="container">
            <h1 class="vacancy__title">{{ $title }}</h1>
            <div class="row">                
                <div class="col-lg-8 vacancy__lists">
                    @foreach($courses as $course)
                        @include('partials.content-course-card')
                    @endforeach
                    
                    @empty($courses)
                        <div class="alert alert-warning">Geen cursussen gevonden</div>
                    @endempty
                    {!!  App\bootstrap_pagination()!!}
                </div>
                <div class="w-100 d-lg-none my-3">
                    <!-- wrap every 1 on xs-->
                </div>
                <aside class="col-lg-4 sidebar">
                    @include('partials.sidebar')
                </aside>
            </div>
        </div>
    </section>
@endsection
