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
                            <div class="signup__details mb-4">
                               <p>{{__('Om toegang te krijgen tot álle mogelijkheden van de vacature-site is het belangrijk dat je geregistreerd bent.', 'mooiwerk-breda-theme')}}</p>
                                <p>{{__('Je kunt je jezelf op twee manier registreren; als vrijwilliger die op zoek in naar MOOIWERK of je kunt jouw organisatie registreren voor het plaatsen van vacatures. Hieronder kun je beslissing hoe je wil registreren.', 'mooiwerk-breda-theme')}}</p>
                            </div>
                            <div class="signup__actions text-center">
                            @php
                                $volunteer = get_page_by_title('Registreer Vrijwilliger');
                                $volunteer_url = home_url('/'.$volunteer->post_name);

                                $organisation = get_page_by_title('Registreer Organisatie');
                                $organisation_url = home_url('/'.$organisation->post_name);
                            @endphp
                                <a href="{{$volunteer_url}}" class="btn btn-outline-primary btn-lg signup__link">
                                    {{__('Als vrijwilliger', 'mooiwerk-breda-theme')}}
                                </a>                                
                                <a href="{{$organisation_url}}" class="btn btn-outline-primary btn-lg signup__link">
                                    {{__('Als organisatie', 'mooiwerk-breda-theme')}} 
                                </a>
                            </div>
                        </div>
                    </div>
                </div>      
            @endwhile

        </div>
    </section>
@endsection
