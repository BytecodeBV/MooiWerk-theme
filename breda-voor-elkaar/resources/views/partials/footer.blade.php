<footer class="footer">
  @include('partials.newsletter')
  <div class="footer_bottom">
    <div class="container d-flex justify-content-center d-sm-block">
        <div class="row footer-menu">
          <div class="col-sm-6 col-lg-3">
            @php dynamic_sidebar('sidebar-footer-column-1') @endphp
          </div>
          <div class="col-sm-6 col-lg-3">
            @php dynamic_sidebar('sidebar-footer-column-2') @endphp
          </div>
          <div class="col-sm-6 col-lg-3">
            @php dynamic_sidebar('sidebar-footer-column-3') @endphp
          </div>
          <div class="col-sm-6 col-lg-3">
            @php dynamic_sidebar('sidebar-footer-column-4') @endphp
          </div>
        </div>
    </div>
  </div>
  <div class="footer-menu__copyright">
        <div class="container">
            <div class="text-center">{{__('© 2018 Mooi Werk - All rights reserved', 'sage')}}</div>
        </div>
    </div>
</footer>
