<section class="news-list">
    <div class="container">
        <h3 class="news-list__header">Of bent u op zoek na Copy</h3>
        <div class="row">
            
            @foreach($categories as $link)
                
                @if($loop->first || $loop->iteration % 3 == 1)
                    <div class="col-md-4 d-flex justify-content-center d-md-block">
                        <ul class="list-group list-group-flush news-list__item">
                @endif
                            <li class="list-group-item">
                                <a href="{{ $link["url"] }}" class="news-list__link">{{strip_tags($link['title'])}}</a>
                            </li>
                @if($loop->iteration % 3 == 0 || $loop->last)
                        </ul>
                    </div>
                    <div class="w-100 d-md-none my-3">
                        <!-- wrap every 1 on xs-->
                    </div>
                @endif
            @endforeach
            
            @empty($categories)
                <div class="alert alert-warning"> Geen links gevonden</div>
            @endempty

        </div>
    </div>    
</section>