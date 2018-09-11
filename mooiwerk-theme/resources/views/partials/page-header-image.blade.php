@php
    $header = get_field('page_header', get_the_ID());
    if ($header) {
        $banner = get_header_by_ID($header);
    }
@endphp
@if($banner)
  {!! $banner !!}
@else
    <section class="hero-banner" style="background-image:url({{App\asset_path('images/header_u.png')}}); background-size:cover; background-position: center;">
    </section>
@endif