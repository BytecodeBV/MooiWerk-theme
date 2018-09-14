@extends('layouts.app')

@section('content')
  @include('partials.page-header')

    <section @php post_class('error') @endphp>
        <div class="error__body container">
            @while(have_posts()) @php the_post() @endphp 
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="error__content">
                            <h1 class="error__code mb-4">{{__('Account aanmaken', 'mooiwerk-breda-theme')}}</h1>
                            <div class="error__details mb-4">
                               {{__('Maak een account om aan de slag te gaan', 'mooiwerk-breda-theme')}}
                            </div>
                            <div class="error__actions">
                            @php
                                $volunteer = get_page_by_title('Registreer Vrijwilliger');
                                $volunteer_url = home_url('/'.$volunteer->post_name);

                                $organisation = get_page_by_title('Registreer Organisatie');
                                $organisation_url = home_url('/'.$organisation->post_name);
                            @endphp
                                <a href="{{$organisation_url}}" class="btn btn-primary btn-lg error__link">
                                    {{__('ik ben een organisatie', 'mooiwerk-breda-theme')}} 
                                </a>
                                <a href="{{$volunteer_url}}" class="btn btn-secondary btn-lg error__link">
                                    {{__('ik ben een vrijwilliger', 'mooiwerk-breda-theme')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>      
            @endwhile

        </div>
    </section>
@endsection
