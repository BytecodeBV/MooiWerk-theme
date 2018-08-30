<header class="header bg-primary">
  <div class="container d-none d-md-flex justify-content-md-end header__content">
    <div class="header__search">
      <input type="text" placeholder="Zoeken" class="header__input" name="s" id="search_input" onkeypress="search" />
    </div>
    @if(is_user_logged_in())
        @php
            $user = wp_get_current_user();
        @endphp
        @if(in_array('volunteer', (array) $user->roles) || in_array('organisation', (array) $user->roles))
            <a href="{{home_url('/mijn-account')}}" class="header__profile">Profiel</a>
        @else
            <a href="{{admin_url('profile.php')}}" class="header__profile">Profiel</a>
        @endif
        <a href="{{wp_logout_url()}}" class="header__logout">Log Uit</a>
    @else
        <a href="{{wp_login_url()}}" class="header__login">Inloggen</a>
    @endif
  </div>
</header>
