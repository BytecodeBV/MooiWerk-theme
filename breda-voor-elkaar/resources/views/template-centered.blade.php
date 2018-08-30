{{--
  Template Name: Centered Page
--}}

@extends('layouts.app')

@section('content')
    @include('partials.page-header')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.content-page-centered')
  @endwhile
@endsection
