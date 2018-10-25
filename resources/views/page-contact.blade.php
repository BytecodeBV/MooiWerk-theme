@extends('layouts.app')

@section('content')
    @include('partials.page-header')
  @while(have_posts()) @php the_post() @endphp    
    @include('partials.content-contact')
  @endwhile
@endsection
