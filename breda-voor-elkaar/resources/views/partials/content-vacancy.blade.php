<div class="card shadow border-light vacancy-list__item  vacancy-card">
    <div class="row vacancy-card__header-wrapper">
        <div class="col-xxl-2 col-md-3 col-xs-12 vacancy-card__figure d-flex align-items-center">
        <img src="{{ $vacancy['image_link']? $vacancy['image_link'] : '//placehold.it/114x76' }}" class="vacancy-card__image">
        </div>
        <div class="col-xxl-10 col-md-9 col-xs-12 vacancy-card__header-group">
            <h2 class="card-title vacancy-card__header">{{ $vacancy['title'] }}</h2>
            <h3 class="card-subtitle vacancy-card__subheader">{{ $vacancy['subtitle'] }}</h3>
        </div>
    </div>
    <div class="card-body vacancy-card__body">
        <div class="vacancy-card__text">{!! $vacancy['excerpt'] !!}<a href="{{ $vacancy['link'] }}" class="card-link vacancy-card__link">lees meer ›</a></div>       
    </div>
    <div class="card-footer vacancy-card__footer">{{ $vacancy['footer'] }}</div>
</div>