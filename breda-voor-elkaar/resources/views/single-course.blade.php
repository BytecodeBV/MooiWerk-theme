
@extends('layouts.app')

@section('content')
    @include('partials.page-header-image')
    <section @php post_class('cv') @endphp>
        <div class="container">
            <div class="row">
                <div class="col-sm-10  cv__header">
                    <h1 class="cv__name"> {{ $title }}</h1>
                    <p class="cv__detail">{{ $subtitle }}</p>
                    <p class="cv__detail"> {{ $date }}</p>
                </div>
            </div>
            <div class="row">
                <nav class="col d-flex flex-column flex-sm-row justify-content-end border-top cv__social-bar">
                    <div class="course__social-icons">
                        <span class="d-sm-none d-md-inline cv__social-text">Deel deze pagina</span>
                        <a href="{{$share['facebook']}}" target="_blank">
                            <img src="@asset('images/facebook.svg')" class="cv__social-link" alt="Facebook">
                        </a>
                        <a href="{{$share['twitter']}}" target="_blank">
                            <img src="@asset('images/twitter.svg')" class="cv__social-link" alt="Twitter">
                        </a>
                        <a href="{{$share['linkedin']}}" target="_blank">
                            <img src="@asset('images/linkedin.svg')" class="cv__social-link" alt="Linkedin">
                        </a>
                        <a href="{{$share['gplus']}}" target="_blank">
                            <img src="@asset('images/google.svg')" class="cv__social-link cv__social-link_last" alt=Google + "">
                        </a>
                    </div>
                </nav>
            </div>
            <div class="row cv__content">
                <article class="col-lg-8 cv__profile">
                    <div class="post__content" >
                            {!! do_shortcode($content) !!}
                    </div>
                </article>
                <aside class="col-lg-4 cv__sidebar sidebar">
                    @include('partials.sidebar')
                </aside>
            </div>
        </div>
    </section>
@endsection