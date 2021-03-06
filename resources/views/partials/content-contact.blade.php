<section @php post_class('contact') @endphp>
    <div class="page__body container">
        <div class="row page__content contact__cards mt-5">
            <div class="col-lg-6 mb-3">
                <h1 class="mb-3">{{__('Contact', 'mooiwerk-breda-theme')}}</h1>
                @if( $phone = get_field('phone') )
                    <div class="media contact__card mb-3 shadow bg-white rounded">
                        <a class="media-left" href="#">
                            <img class="media-object p-2" src="@asset('images/phone.png')" width="120" alt="">
                        </a>
                        <div class="media-body px-3 pt-3">
                            {!! $phone !!}
                        </div>                    
                    </div>
                @endif
                @if( $address = get_field('address') )
                    <div class="media contact__card mb-3 shadow bg-white rounded">
                        <a class="media-left" href="#">
                            <img class="media-object p-2" src="@asset('images/envelope.png')" width="120" alt="">
                        </a>
                        <div class="media-body px-3 pt-3">
                            {!!$address !!}
                        </div>                    
                    </div>
                @endif
                @if( $email = get_field('email') )
                    <div class="media contact__card mb-3 shadow bg-white rounded">
                        <a class="media-left" href="#">
                            <img class="media-object p-2" src="@asset('images/at.png')" width="120" alt="">
                        </a>
                        <div class="media-body px-3 pt-3">
                            {{ $email }}
                        </div>                    
                    </div>
                @endif
                @if( $info = get_field('info') )
                    <div class="media contact__card mb-3 shadow bg-white rounded">
                        <a class="media-left" href="#">
                            <img class="media-object p-2" src="@asset('images/map-marker.png')" width="120" alt="">
                        </a>
                        <div class="media-body px-3 pt-3">
                            {!! $info !!}
                        </div>                    
                    </div>
                @endif             
            </div>
            <div class="col-lg-6 mb-3">
                @php
                    $teams = App::teams();
                @endphp
                @if($teams)
                    <h1 class="mb-3"> {{__('TEAM', 'mooiwerk-breda-theme')}} </h1>
                    @foreach($teams as $team)
                        <div class="media contact__card mb-3 shadow bg-white rounded">
                            <a class="media-left" href="#">
                                <img class="media-object" src="{{$team['avatar']}}" width="120" alt="">
                            </a>
                            <div class="media-body ml-3 mt-2">
                                <h4 class="media-heading">{{$team['name']}}</h4>
                                    <ul class="m-0 contact__list">
                                        <li class="contact__list-item"><i class="fa fa-phone"></i> {{$team['phone']}}</li>
                                        <li class="contact__list-item"><i class="fa fa-envelope"></i> {{$team['email']}}</li>
                                    </ul>
                            </div>                    
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>