<div class="vacancy__item vacancy-tab">
    <div class="vacancy-tab__top p-2 ">{{$course['date']}}</div>
    <div class="vacancy-tab__content p-4">
        <h3 class="vacancy-tab__header">{{$course['title']}}</h3>
        <h3 class="vacancy-tab__subheader">{{$course['lesson']}}</h3>
        <div class="vacancy-tab__body">{{strip_tags($course['excerpt'])}}</div>
        <a href="{{$course['link']}}" class="vacancy-tab__link">lees meer ›</a>
    </div>
</div>