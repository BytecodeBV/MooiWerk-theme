<section class="blog-index__body">
    <div class="container">
        <h1 class="blog-index__header">{!! App::title() !!}</h1>
        
        @empty($posts)
            <div class="jumbotron mt-3">
                <h1>{{__('Opps! Nothing found', 'mooiwerk-breda-theme')}}</h1>
                <p class="lead">{{__('There is nothing to display.', 'mooiwerk-breda-theme')}}</p>
                <a class="btn btn-lg btn-primary" href="{{wp_get_referer()}}" role="button">{{__('Go Back »', 'mooiwerk-breda-theme')}}</a>
                </div>
        @endempty
        <div class="row">
            @foreach($posts as $item)
                <div class="col-md-6 col-xl-4 col-xxl-3 mb-3">
                    @include('partials.content-post')
                </div>
            @endforeach
        </div>
    </div>
</section>