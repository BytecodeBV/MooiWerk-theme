<?php

namespace App;

use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Container;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);
    // scripts.php
    wp_localize_script('sage/main.js', 'site_data', array(
        //'debug' => WP_DEBUG,
        'home' => home_url('/'),
    ));
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    add_image_size('header-image', 1349, 395, true); // Hard Crop Mode
    add_image_size('big-grid', 500, 500); // Soft Crop Mode
    add_image_size('small-grid', 232, 232); // Soft Crop Mode
    add_image_size('list-thumbnail', 496, 330); // Soft Crop Mode
    add_image_size('grid-thumbnail', 320, 200); // Soft Crop Mode
    add_image_size('side-column', 674, 350); // Soft Crop Mode

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    $defaults = array(
        'height' => 27,
        'width' => 207,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => array('site-title'),
    );
    add_theme_support('custom-logo', $defaults);

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<div class="%1$s %2$s sidebar__item">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="sidebar__title">',
        'after_title' => '</h5>',
    ];
    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);
    $config = [
        'before_widget' => '<div class="footer_bottom__widget %1$s %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="footer_bottom__header">',
        'after_title' => '</h5>',
    ];
    register_sidebar([
        'name' => __('Footer Column 1', 'sage'),
        'id' => 'sidebar-footer-column-1',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Column 2', 'sage'),
        'id' => 'sidebar-footer-column-2',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Column 3', 'sage'),
        'id' => 'sidebar-footer-column-3',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Column 4', 'sage'),
        'id' => 'sidebar-footer-column-4',
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});
