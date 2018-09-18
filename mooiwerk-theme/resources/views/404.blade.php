@extends('layouts.app')

@section('content')
  @include('partials.page-header')

    <section @php post_class('error') @endphp>
        <div class="error__body container">
                <div class="row my-4">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="error__content">
                            <h1 class="error__header">{{__('Oops!', 'mooiwerk-breda-theme')}}</h1>
                            <h2 class="error__code mb-4">{{__('Je hebt een 404', 'mooiwerk-breda-theme')}}</h2>
                            <div class="error__details mb-4">
                               {{__('Sorry, maar de gevraagde bron is niet gevonden!', 'mooiwerk-breda-theme')}}
                            </div>
                            <div class="error__actions">
                                <a href="{{site_url()}}" class="btn btn-primary btn-lg error__link">
                                    <span class="fa fa-home"></span> {{__('Gaan Huis', 'mooiwerk-breda-theme')}} 
                                </a>
                                <a href="mailto:{{get_option('admin_email')}}" class="btn btn-secondary btn-lg error__link">
                                    <span class="fa fa-envelope"></span> {{__('Contact Ondersteuning', 'mooiwerk-breda-theme')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div> 

        </div>
    </section>
@endsection
