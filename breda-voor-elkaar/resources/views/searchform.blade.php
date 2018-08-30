<form role="search" method="get" id="search-form" action="@php echo esc_url( home_url( '/' ) ); @endphp" class="form-inline">
    <label class="sr-only" for="s">Zoek</label>
    <input name="s" placeholder="Zoek op trefwoord" class="hero__search" />
    <input type="hidden" name="type" value="vacancies"/>
    <button type="submit" class="btn hero__btn">Zoek</button>
</form>