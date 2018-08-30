<?php

namespace App;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    // Add postMessage support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial('blogname', [
        'selector' => '.brand',
        'render_callback' => function () {
            bloginfo('name');
        },
    ]);

    $wp_customize->add_section(
        'newsletter',
        array(
            'title' => __('Newsletter', 'mooiwerk'),
            'capability' => 'edit_theme_options', // Capability needed to tweak
            'description' => __('Set Mailchimp List URL.', 'mooiwerk'),
        )
    );

    // Register a new setting "company_name"
    $wp_customize->add_setting(
        'mc_subscriptionlist',
        array(
            'default' => '', // Default setting/value to save
            'type' => 'option',
        )
    );

    // Define input for setting "company_name"
    $wp_customize->add_control(new \WP_Customize_Control(
        $wp_customize,
        'mc_subscriptionlist_control', // unique ID for the control
        array(
            'label' => __('Mailchimp List URL', 'mooiwerk'),
            'settings' => 'mc_subscriptionlist',
            'type' => 'text',
            'section' => 'newsletter',
        )
    ));

    $wp_customize->add_section(
        'acf_globals',
        array(
            'title' => __('ACF Settings', 'sage'),
            'capability' => 'edit_theme_options', // Capability needed to tweak
            'description' => __('Set ACF Global Setting.', 'sage'),
        )
    );

    // Register a new setting "company_name"
    $wp_customize->add_setting(
        'acf_google_map',
        array(
            'default' => '', // Default setting/value to save
            'type' => 'option',
        )
    );

    // Define input for setting "company_name"
    $wp_customize->add_control(new \WP_Customize_Control(
        $wp_customize,
        'acf_google_map_control', // unique ID for the control
        array(
            'label' => __('Google Map Key', 'sage'),
            'settings' => 'acf_google_map',
            'type' => 'text',
            'section' => 'acf_globals',
        )
    ));
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});
