<section class="vacancies bg-secondary">
        <div class="container">
            <h1 class="vacancy__title">{{ $course_title }}</h1>
            <div class="row">
                <sidebar class="col-lg-4 vacancy-sidebar d-md-flex justify-content-md-center">
                    <div class="bg-danger text-light p-4">
                        <h3 class="vacancy-sidebar___header">{{$course_intro}}</h3>
                        <div class="vacancy-sidebar___text">
                            {!! $course_description !!}
                        </div>
                        <a href="{{$course_link}}" class="btn btn-light d-block mx-auto vacancy-sidebar___button">{{__('alle cursussen ›', 'sage')}}</a>
                    </div>
                </sidebar>
                <div class="w-100 d-lg-none my-3">
                    <!-- wrap every 1 on xs-->
                </div>
                <div class="col-lg-8 vacancy__lists">
                    @foreach($courses as $course)
                        @include('partials.content-course-card')
                    @endforeach

                    @empty($courses)
                        <div class="alert alert-warning">{{__('Geen cursussen gevonden', 'sage')}}</div>
                    @endempty
                </div>
            </div>
        </div>
    </section>