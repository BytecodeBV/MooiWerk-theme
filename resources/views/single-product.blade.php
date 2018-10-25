
@extends('layouts.app')

@section('content')
    @include('partials.page-header-image')
    <section @php post_class('cv') @endphp>
        <div class="container">

            <div class="row cv__content">
                <article class="col-lg-8 cv__profile">
                    <div class="post__content" >
                        <?php
                            /**
                            * woocommerce_before_main_content hook.
                            *
                            * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                            * @hooked woocommerce_breadcrumb - 20
                            */
                            do_action( 'woocommerce_before_main_content' );
                        ?>

		                <?php while ( have_posts() ) : the_post(); ?>

			                <?php wc_get_template_part( 'content', 'single-product' ); ?>

		                <?php endwhile; // end of the loop. ?>

                        <?php
                            /**
                            * woocommerce_after_main_content hook.
                            *
                            * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                            */
                            do_action( 'woocommerce_after_main_content' );
                        ?>
                    </div>
                </article>
                <aside class="col-lg-4 cv__sidebar sidebar">
                   @include('partials.sidebar')
                </aside>
            </div>
        </div>
    </section>
@endsection