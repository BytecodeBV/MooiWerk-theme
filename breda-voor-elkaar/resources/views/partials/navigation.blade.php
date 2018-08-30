@php
    //provision for custom logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    if ( has_custom_logo() ) {
        $logo_src=esc_url( $logo[0] );
    } else {
        $logo_src= App\asset_path('images/logo.svg');
    }
@endphp
<nav class="navbar navbar-expand-lg navbar-light text-dark">
  <div class="container">
  <a class="navbar-brand" href="{{ home_url('/') }}"><img src="{{$logo_src}}" alt="{{ get_bloginfo('name', 'display') }}"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--div id="main-nav" class="collapse navbar-collapse"-->
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu($primarymenu) !!}
      @endif
      <!--/div-->
  </div>
</nav>
