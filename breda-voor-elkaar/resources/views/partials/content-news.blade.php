<section class="newsdeck">
    <div class="container">
        <h1 class="newsdeck__title">{{$news_title}}</h1>
        <div class="row">
             @foreach($news as $item)
			    @if ($loop->first) 
                    <div class="col-lg-6">
                        <div class="card-deck newsdeck__item_margin-bottom">
                            <div class="card newsdeck__item newsdeck__item_small d-flex align-items-start flex-column">
                                <div class="card-block">
                                    <h3 class="card-title">{{$item['title']}}</h3>
                                    <p class="card-text">{{strip_tags($item['excerpt'])}}</p>
                                    <a href="{{$item['link']}} " class="mt-auto">lees meer ›</a>
                                </div>
                            </div>
                            <div class="w-100 d-sm-none my-3">
                                <!-- wrap every 1 on xs-->
                            </div>
				@endif
				@if ($loop->iteration == 2)
				            <div class="card border-top newsdeck__item newsdeck__item_small">
                                <div class="card-block newsdeck__item_align d-flex align-items-start flex-column">
                                    <h3 class="card-title">{{$item['title']}}</h3>
                                    <p class="card-text">{{strip_tags($item['excerpt'])}}</p>
                                    <a href="{{$item['link']}}" class="mt-auto">lees meer ›</a>
                                </div>
                            </div>
                        </div>
				@endif
				@if ($loop->iteration == 3)
                        <div class="card newsdeck__item newsdeck__item_big">
                            <div class="card-block newsdeck__item_align">
                            <img class="card-img w-100" src="{{$item['image_link']}}" alt="{{ $item['title'] }} thumbnail" />
                                <div class="card-img-overlay newsdeck__caption d-flex flex-column justify-content-end text-white">
                                    <h3 class="card-title">{{$item['title']}}</h3>
                                    <p class="card-text">{{strip_tags($item['excerpt'])}}</p>
                                    <a href="{{$item['link']}}" class="text-white">lees meer ›</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 d-lg-none my-3">
                        <!-- wrap every 1 on xs-->
                    </div>
				@endif
				@if ($loop->iteration == 4)
                    <div class="col-lg-6">
                        <div class="card newsdeck__item newsdeck__item_big">
                            <div class="card-block">
                                <img class="card-img w-100" src="{{$item['image_link']}}" alt="{{ $item['title'] }} thumbnail" />
                                <div class="card-img-overlay newsdeck__caption d-flex flex-column justify-content-end text-white">
                                    <h3 class="card-title">{{$item['title']}}</h3>
                                    <p class="card-text">{{strip_tags($item['excerpt'])}}</p>
                                    <a href="{{$item['link']}}" class="text-white">lees meer ›</a>
                                </div>
                            </div>
                        </div>
                @endif
				@if ($loop->iteration == 5)
				        <div class="card-deck newsdeck__item_margin-top">
                            <div class="card newsdeck__item newsdeck__item_small">
                                <div class="card-block newsdeck__item_align d-flex align-items-start flex-column">
                                    <h3 class="card-title">{{$item['title']}}</h3>
                                    <p class="card-text">{{strip_tags($item['excerpt'])}}</p>
                                    <a href="{{$item['link']}}" class="mt-auto">lees meer ›</a>
                                </div>
                            </div>
                            <div class="w-100 d-sm-none my-3">
                                <!-- wrap every 1 on xs-->
                            </div>
                @endif
				@if ($loop->iteration == 6)
				        <div class="card newsdeck__item newsdeck__item_small">
                            <div class="card-block newsdeck__item_align d-flex align-items-start flex-column">
                                <h3 class="card-title">{{$item['title']}}</h3>
                                <p class="card-text">{{strip_tags($item['excerpt'])}}</p>
                                <a href="{{$item['link']}}" class="mt-auto">lees meer ›</a>
                            </div>
                        </div>
                    </div>
				@endif
        @endforeach
         @empty($news)
            <div class="alert alert-warning"> Niet genoeg inhoud om raster te maken</div>
        @endempty
        </div>
    </div>
</section>
