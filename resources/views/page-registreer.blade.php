@extends('layouts.app')

@section('content')
  @include('partials.page-header')

    <section @php post_class('error') @endphp>
        <div class="signup__body container">
            @while(have_posts()) @php the_post() @endphp 
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="signup__content text-left">
                            <h1 class="signup__code mb-4">{{__('Registreren', 'mooiwerk-breda-theme')}}</h1>
                            @php the_content() @endphp
                        </div>
                    </div>
                </div>      
            @endwhile
        </div>
    </section>
@endsection
