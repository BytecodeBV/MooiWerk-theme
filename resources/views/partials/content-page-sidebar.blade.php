<section @php post_class() @endphp>
    <div class="page__body container">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <h1 class="page__header"> @php the_title() @endphp</h1>
                <div class="page__content" >
                    @php the_content() @endphp
                </div>
            </div>
            <aside class="col-lg-4 sidebar mb-4">
                @include('partials.sidebar')
            </aside>
        </div>
    </div>
</section>