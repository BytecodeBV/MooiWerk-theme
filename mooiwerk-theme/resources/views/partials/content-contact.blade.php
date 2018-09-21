<section @php post_class('contact') @endphp>
    <div class="page__body container">
        <div class="row page__content mt-5">
            <div class="col-md-6 contact__cards">
                <div class="col-sm-12 my-4">
                    <div class="card contatct__card border-0">
                        <div class="card-body">
                            <p class="mb-4"><i class="fa fa-phone fa-2x mb-2" aria-hidden="true"></i></p>
                            <div class="big-text">{!! get_field('phone') !!}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 my-4">
                    <div class="card  contatct__card border-0">
                        <div class="card-body">
                            <p class="mb-4"><i class="fa fa-envelope fa-2x mb-2" aria-hidden="true"></i></p>
                            <div class="big-text">{!! get_field('address') !!} </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 my-4">
                    <div class="card  contatct__card border-0">
                        <div class="card-body">
                            <p class="mb-4"><i class="fa fa-at fa-2x mb-2" aria-hidden="true"></i></p>
                            <div class="big-text">{{get_field('email')}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 my-4">
                    <div class="card  contatct__card border-0">
                        <div class="card-body">                            
                            <p class="mb-4"><i class="fa fa-map-marker fa-2x mb-2" aria-hidden="true"></i></p>
                            <div class="big-text">{!! get_field('info') !!}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6  contact__form">
                @php
                    $teams = App::teams();
                @endphp
                @if($teams)
                    @foreach($teams as $team)
                        <div class="d-flex flex-row border rounded mb-4">
                            <div class="p-0 w-25">
                                <img src="{{$team['avatar']}}" class="img-thumbnail border-0" />                        
                            </div>
                            <div class="pl-3 pt-2 pr-2 pb-2 w-75 border-left">
                                    <h4 class="text-primary">{{$team['name']}}</h4>
                                    <ul class="m-0 float-left" style="list-style: none; margin:0; padding: 0">
                                        <li><i class="fab fa-facebook-square"></i> {{$team['phone']}}</li>
                                        <li><i class="fab fa-twitter-square"></i> {{$team['email']}}</li>
                                    </ul>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>