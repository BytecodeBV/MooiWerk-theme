@php
    $header = NULL;

    if(is_singular()) {
        //Load posts Banner
        $header = get_field('page_header', get_the_ID());
    } else if(is_author()){
        //Load selected user banner
        $header = get_field('page_header', 'user_'.get_current_user_id());
    }

    if (empty($header) && is_author()) {
        $user = wp_get_current_user();
        
        //organisation fallback header
        if (in_array('organisation', $user->roles)) {
            $header = get_option('organisation_header');
        }

        //volunteer fallback header   
        if (in_array('volunteer', $user->roles)) {
            $header = get_option('volunteer_header');
        }
    }

    //vacancy fallback header    
    if (empty($header) && is_singular('vacancies')) {
        $header = get_option('vacancy_header');
    }
        
    if ($header) {
        $banner = get_header_by_ID($header);
    }
@endphp
@if($banner)
  {!! $banner !!}
@else
    <section class="hero">
        <div class="container">
            <h1 class="hero__header">{{__('Vrijwilliger zijn?', 'mooiwerk-breda-theme')}}</h1>
            <h3 class="hero__sub-header">{{__('Vind je vacature.', 'mooiwerk-breda-theme')}}</h3>
            <div class="d-flex hero__form">
                @include('searchform')
            </div>
        </div>
    </section>
@endif
