<div class="card blog__item">
    <img class="card-img-top" src="{{ $item['image_link'] }}" alt="{{ $item['title'] }} thumbnail">
    <div class="card-body">
        <h5 class="card-title blog__item-title">{{ $item['title'] }}</h5>
        <p class="card-text">{{ $item['excerpt'] }}</p>
        <a href="{{ $item['link'] }}" class="blog__item-link">{{__('lees meer ›', 'sage')}}</a>
    </div>
</div>

