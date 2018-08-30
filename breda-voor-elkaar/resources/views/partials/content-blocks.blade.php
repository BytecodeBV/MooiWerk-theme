<section class="top-news">
    <div class="container d-flex justify-content-center d-md-block">
        <div class="card-deck top-news__cards">
            
            @foreach($vacancies as $card)
                <div class="card top-news__card">
                    <div class="card-body d-flex align-items-start flex-column">
                        <h3 class="card-title">{{ $card['title'] }}</h3>
                        <p class="card-text mb-auto">{{ strip_tags($card['content']) }}</p>
                        <a href="{{ $card['link'] }}">lees meer ›</a>
                    </div>
                </div>
                <div class="w-100 d-sm-none my-3">
                    <!-- wrap every 1 on xs-->
                </div>
                
                @if( $loop->iteration % 2 == 0)
                    <div class="w-100 d-none d-sm-block d-lg-none my-3">
                        <!-- wrap every 2 on sm-->
                    </div>
                @endif

            @endforeach
            
            @empty($vacancies)    
                <div class="aert alert-warning">Geen cursus gevonden</div>
            @endempty
        </div>
    </div>
</section>