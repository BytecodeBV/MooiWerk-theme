<section class="news-list">
    <div class="container">
        <h3 class="news-list__header">{{__('Of bent u op zoek na Copy', 'mooiwerk-breda-theme')}}</h3>
        <div class="row">
            @php
                if (count($links) <= 6) {
                    $denominator = 2;
                } else {
                    $denominator = 3;
                }
            @endphp
            @foreach($links as $link)
                
                @if($loop->first || $loop->iteration % $denominator == 1)
                    <div class="col-md-4 d-flex justify-content-center d-md-block">
                        <ul class="list-group list-group-flush news-list__item">
                @endif
                            <li class="list-group-item">
                                <a href="{{ $link["url"] }}" class="news-list__link">{{strip_tags($link['title'])}}</a>
                            </li>
                @if($loop->iteration % $denominator == 0 || $loop->last)
                        </ul>
                    </div>
                    <div class="w-100 d-md-none my-3">
                        <!-- wrap every 1 on xs-->
                    </div>
                @endif
            @endforeach
            
            @empty($links)
                <div class="alert alert-warning">{{__('Geen links gevonden', 'mooiwerk-breda-theme')}}</div>
            @endempty

        </div>
    </div>    
</section>