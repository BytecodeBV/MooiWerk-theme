@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    <section class="blog-index__body">
        <div class="container">
            <h1 class="blog-index__header">{!! App::title() !!}</h1>
            
            @empty($items)
                <div class="jumbotron mt-3">
                    <h1>{{__('Opps! Nothing found', 'sage')}}</h1>
                    <p class="lead">{{__('There is nothing to display.', 'sage')}}</p>
                    <a class="btn btn-lg btn-primary" href="{{wp_get_referer()}}" role="button">{{__('Go Back »', 'sage')}}</a>
                  </div>
            @endempty
            <div class="row">
                @foreach($items as $item)
                    <div class="col-md-6 col-xl-4 col-xxl-3 mb-3">
                        @include('partials.content-'.get_post_type())
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    {!!  App\bootstrap_pagination() !!}
@endsection
