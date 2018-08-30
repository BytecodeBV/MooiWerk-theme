<div class="card shadow border-light blog-card flex-md-row mb-4 box-shadow h-md-250">
    <img class="card-img-left blog-card__image flex-auto d-block" src="{{ get_the_post_thumbnail_url()? get_the_post_thumbnail_url(null, [200, 250]) : '//placehold.it/200x250' }}" alt="Thumbnail">
    <div class="card-body blog-card__body d-flex flex-column align-items-start">
        <h3 class="mb-2">
        <a class="text-dark blog-card__title" href="{{ get_permalink() }}">{{ get_the_title() }}</a>
        </h3>
        <div class="mb-2 blog-card__time text-muted">{{get_post_time('d M')}}</div>
        <div class="card-text blog-card__content mb-auto">{!! wp_kses_post(wp_trim_words(get_the_content(), 25, '...')) !!}</div>
        <a href="{{ get_permalink() }}" class="blog-card__link">bekijk ></a>
    </div>
</div>
        