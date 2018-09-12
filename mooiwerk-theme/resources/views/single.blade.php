@extends('layouts.app')

@section('content')
    @include('partials.page-header-image')
    @include('partials.content-single-'.get_post_type())
@endsection
