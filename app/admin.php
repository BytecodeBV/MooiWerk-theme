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
            'title' => __('Newsletter', 'mooiwerk-breda-theme'),
            'capability' => 'edit_theme_options', // Capability needed to tweak
            'description' => __('Set Mailchimp List URL.', 'mooiwerk-breda-theme'),
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
            'label' => __('Mailchimp List URL', 'mooiwerk-breda-theme'),
            'settings' => 'mc_subscriptionlist',
            'type' => 'text',
            'section' => 'newsletter',
        )
    ));

    $wp_customize->add_section(
        'acf_globals',
        array(
            'title' => __('ACF Settings', 'mooiwerk-breda-theme'),
            'capability' => 'edit_theme_options', // Capability needed to tweak
            'description' => __('Set ACF Global Setting.', 'mooiwerk-breda-theme'),
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
            'label' => __('Google Map Key', 'mooiwerk-breda-theme'),
            'settings' => 'acf_google_map',
            'type' => 'text',
            'section' => 'acf_globals',
        )
    ));

    $wp_customize->add_section(
        'header_globals',
        array(
            'title' => __('Site Headers', 'mooiwerk-breda-theme'),
            'capability' => 'edit_theme_options', // Capability needed to tweak
            'description' => __('Set Default Header for Post Types', 'mooiwerk-breda-theme'),
        )
    );

    //Get available headers
    $headers = get_posts(
        array(
            'numberposts' => -1, // we want to retrieve all of the posts
            'post_type' => 'header',
        )
    );
    
    //Populate options with headers
    $options = wp_list_pluck($headers, 'post_title', 'ID');

    // Register a new setting "company_name"
    $wp_customize->add_setting(
        'vacancy_archive_header',
        array(
            'default' => '', // Default setting/value to save
            'type' => 'option',
        )
    );

    // Define input for setting "company_name"
    $wp_customize->add_control(new \WP_Customize_Control(
        $wp_customize,
        'vacancy_archive_header_control', // unique ID for the control
        array(
            'label' => __('Header for Vacancy Archive Page', 'mooiwerk-breda-theme'),
            'settings' => 'vacancy_archive_header',
            'type' => 'select',
            'section' => 'header_globals',
            'choices' => $options
        )
    ));
    
    // Register a new setting "company_name"
    $wp_customize->add_setting(
        'vacancy_header',
        array(
            'default' => '', // Default setting/value to save
            'type' => 'option',
        )
    );

    // Define input for setting "company_name"
    $wp_customize->add_control(new \WP_Customize_Control(
        $wp_customize,
        'vacancy_header_control', // unique ID for the control
        array(
            'label' => __('Header for Vacancy Posts', 'mooiwerk-breda-theme'),
            'settings' => 'vacancy_header',
            'type' => 'select',
            'section' => 'header_globals',
            'choices' => $options
        )
    ));

    // Register a new setting "company_name"
    $wp_customize->add_setting(
        'organisation_header',
        array(
            'default' => '', // Default setting/value to save
            'type' => 'option',
        )
    );

    // Define input for setting "company_name"
    $wp_customize->add_control(new \WP_Customize_Control(
        $wp_customize,
        'organisation_header_control', // unique ID for the control
        array(
            'label' => __('Header for Organisation Profiles', 'mooiwerk-breda-theme'),
            'settings' => 'organisation_header',
            'type' => 'select',
            'section' => 'header_globals',
            'choices' => $options
        )
    ));

    // Register a new setting "company_name"
    $wp_customize->add_setting(
        'volunteer_header',
        array(
            'default' => '', // Default setting/value to save
            'type' => 'option',
        )
    );

    // Define input for setting "company_name"
    $wp_customize->add_control(new \WP_Customize_Control(
        $wp_customize,
        'volunteer_header_control', // unique ID for the control
        array(
            'label' => __('Header for Volunteer Profiles', 'mooiwerk-breda-theme'),
            'settings' => 'volunteer_header',
            'type' => 'select',
            'section' => 'header_globals',
            'choices' => $options
        )
    ));
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});
