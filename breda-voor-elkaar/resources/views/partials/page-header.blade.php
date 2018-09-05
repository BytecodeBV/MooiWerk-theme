@php
    $header = get_field('page_header', get_the_ID());
    if ($header) {
        $banner = get_header_by_ID($header);
    }
@endphp
@if($banner)
  {!! $banner !!}
@else
    <section class="hero">
        <div class="container">
            <h1 class="hero__header">Vrijwilliger zijn?</h1>
            <h3 class="hero__sub-header">Vind je vacature.</h3>
            <div class="d-flex hero__form">
                @include('searchform')
            </div>
        </div>
    </section>
@endif
