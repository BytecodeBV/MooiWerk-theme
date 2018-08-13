@extends('layouts.app')

@section('content')
  @include('partials.page-header')
    <section @php post_class() @endphp>
        <div class="search__body container">
            <div class="row">
                <aside class="col-lg-4 sidebar">
                    @include('partials.sidebar')
                </aside>
                <div class="col-lg-8">
                    @if (!have_posts())
                        <div class="alert alert-warning">
                            {{ __('Sorry, no results were found.', 'sage') }}
                        </div>
                        {!! get_search_form(false) !!}
                    @else
                        <h1 class="my-4">{!! App::title() !!}</h1> 

                        @while(have_posts()) 
                            @php 
                                the_post();
                                $post_type = get_post_type(get_the_ID()); 
                            @endphp
                            @if($post_type == 'vacancies')
                                @php                    
                                    $time = human_time_diff(get_post_time('U', true, get_the_ID()), current_time('timestamp')) . ' ago';
                                    $vacancy = [
                                        'title' => get_the_title(),
                                        'link' => get_the_permalink(),
                                        'image_link' => get_the_post_thumbnail_url(null, [200, 200]),
                                        'excerpt' => wp_kses_post(wp_trim_words(get_the_content(), 25, '...')),
                                        'subtitle' => implode(", ",get_field('categorie', get_the_ID())),
                                        'footer' => $time . ' - Breda, Nederland',
                                    ];
                                @endphp
                                @include('partials.content-vacancy')
                            @elseif($post_type == 'course')
                                @php
                                    $course = [
                                        'title' => get_the_title(),
                                        'link' => get_the_permalink(),
                                        'excerpt' => wp_kses_post(wp_trim_words(get_the_content(), 40, '...')),
                                        'date' =>  date_format(date_create(get_field("date", get_the_ID())), "d M"),
                                        'lesson' => get_field("lesson", get_the_ID()),
                                    ];
                                @endphp
                                @include('partials.content-course-card')
                            @else
                                @include('partials.content-search')
                            @endif
                        @endwhile 
                        {!! App\bootstrap_pagination() !!}
                    @endif
                </div>
            </div>            
        </div>
    </section>
@endsection
