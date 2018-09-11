<form role="search" method="get" id="search-form" action="@php echo esc_url( home_url( '/' ) ); @endphp" class="form-inline">
    <label class="sr-only" for="s">{{__('Zoek', 'mooiwerk-breda-theme')}}</label>
    <input name="s" placeholder="{{__('Zoek op trefwoord', 'mooiwerk-breda-theme')}}" class="hero__search" />
    <input type="hidden" name="type" value="vacancies"/>
    <button type="submit" class="btn hero__btn">{{__('Zoek', 'mooiwerk-breda-theme')}}</button>
</form>