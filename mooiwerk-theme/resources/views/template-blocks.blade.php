{{--
  Template Name: Page with Content Blocks
--}}

@extends('layouts.app')

@section('content')
    @include('partials.page-header')
    @php
        $id = get_the_ID();
        $blocks = App::getBlocks($id);
    @endphp
    @if($blocks)
        @include('partials.content-blocks')
    @endif
    @while(have_posts()) @php the_post() @endphp
        
        @include('partials.content-page-blocks')
    @endwhile
@endsection
