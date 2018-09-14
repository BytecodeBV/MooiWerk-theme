<section class="teams bg-secondary">
    <div class="container">
        <h1 class="teams__title">{{ $team_title }}</h1>
        <div class="row teams__cards">
            
            @foreach($teams as $team)
                <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
                    <div class="card team-card">
                        <div class="card-body team-card__content text-center">
                            <p><img class=" img-thumbnail rounded-circle team-card__image" src="{{ $team['avatar'] }}" alt="card image"></p>
                            <h4 class="card-title team-card__name">{{ $team['name'] }}</h4>
                            <div class="card-text team-card__bio">{{ $team['bio'] }}</div>
                            <div class="team-card__meta">                            
                                <ul class="team-card__meta-content">
                                    <li class="team-card__meta-item team-card__phone"><i class="fa fa-phone"></i> {{$team['phone']}}</li>
                                    <li class="team-card__meta-item team-card__email"><i class="fa fa-envelope"></i> {{$team['email']}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            @empty($teams)    
                <div class="alert alert-warning">{{__('Geen cursus gevonden', 'mooiwerk-breda-theme')}}</div>
            @endempty
        </div>
    </div>
</section>