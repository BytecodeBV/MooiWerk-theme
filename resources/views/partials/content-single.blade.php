<section @php post_class() @endphp>
    <div class="container">
        <div class="row">
            <article class="col-lg-8">
                <h1 class="page__header">{{ $item['title'] }}</h1>
                <div class="page__content" >
                    {!! apply_filters('the_content', $item['content']); !!}
                </div>
                
                {{-- comments_template('/partials/comments.blade.php') --}}
            </article>
            <aside class="col-lg-4">
                @include('partials.sidebar')
            </aside>
        </div>
    </div>
</section>

