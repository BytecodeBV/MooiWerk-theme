<section class="news-list">
    <div class="container">
        <h3 class="news-list__header">Of bent u op zoek na Copy</h3>
        <div class="row">
            
            @foreach($links as $link)
                
                @if($loop->first || $loop->iteration % 3 == 1)
                    <div class="col-md-4">
                        <ul class="list-group list-group-flush news-list__item">
                @endif
                            <li class="list-group-item">
                                <a href="<?php echo get_field("url") ?>" class="news-list__link"><?php the_title();?></a>
                            </li>
                @if($loop->iteration % 3 == 0 || $loop->last)
                        </ul>
                    </div>
                    <div class="w-100 d-md-none my-3">
                        <!-- wrap every 1 on xs-->
                    </div>
                @endif
            @endforeach
            
            @empty($links)
                <div> No links found</div>
            @endempty
        </div>
    </div>    
</section>