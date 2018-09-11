<section @php post_class() @endphp>
    <div class="page__body container">
        <div class="col-md-6 offset-md-3">
            <h1 class="page__header">@php the_title() @endphp</h1>
            <div class="page__content" >
                @php the_content() @endphp
            </div>
        </div>
    </div>
</section>