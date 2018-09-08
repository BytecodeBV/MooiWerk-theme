@extends('layouts.app')

@section('content')
  @include('partials.page-header')

    <section @php post_class('error') @endphp>
        <div class="error__body container">
            @if (!have_posts())
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="error__content">
                            <h1 class="error__header">{{__('Oops!', 'sage')}}</h1>
                            <h2 class="error__code mb-4">{{__('Je hebt een 404', 'sage')}}</h2>
                            <div class="error__details mb-4">
                               {{__('Sorry, maar de gevraagde bron is niet gevonden!', 'sage')}}
                            </div>
                            <div class="error__actions">
                                <a href="{{site_url()}}" class="btn btn-primary btn-lg error__link">
                                    <span class="fa fa-home"></span> {{__('Gaan Huis', 'sage')}} 
                                </a>
                                <a href="mailto:{{get_option('admin_email')}}" class="btn btn-secondary btn-lg error__link">
                                    <span class="fa fa-envelope"></span> {{__('Contact Ondersteuning', 'sage')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>      
            @endif

        </div>
    </section>
@endsection
