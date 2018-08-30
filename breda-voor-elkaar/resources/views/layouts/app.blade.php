<!doctype html>
<html @php language_attributes() @endphp>
  @include('partials.head')
  <body @php body_class() @endphp>
        @include('partials.header')
        @php do_action('get_header') @endphp
        @include('partials.navigation')
        @yield('content')
        @php do_action('get_footer') @endphp
        @include('partials.footer')
        @php wp_footer() @endphp
  </body>
</html>
