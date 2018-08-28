<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment',
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    if ($template) {
        echo template($template, $data);
        return get_stylesheet_directory() . '/index.php';
    }
    return $template;
}, PHP_INT_MAX);

/**
 * Tell WordPress how to find the compiled path of comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );
    return template_path(locate_template(["views/{$comments_template}", $comments_template]) ?: $comments_template);
}, 100);

//return acf array instead of object in blade templates
add_filter('sober/controller/acf/array', function () {
    return true;
});

//Use custom nav walker for menu widget
add_filter('widget_nav_menu_args', function ($nav_menu_args) {
    $nav_menu_args = [
        'menu' => $nav_menu_args['menu'],
        'items_wrap' => '%3$s',
        'container' => 'nav',
        'container_class' => 'footer-menu__nav d-flex flex-column',
        'walker' => new Widget_Walker(),
    ];

    return $nav_menu_args;
});

//validate custom theme my login registration fields
add_filter('registration_errors', function ($errors) {
    if (empty($_POST['role'])) {
        $errors->add(
            'empty_role',
            __(
                '<div class="alert"><strong>ERROR<strong>: Please seect a role.</div>',
                'mooiwerk'
            )
        );
    }
    return $errors;
});

/**
 * Translate TML ddefault domain text to Dutch.
 *
 * @param string $translation  Translated text.
 * @param string $text         Text to translate.
 * @param string $domain       Text domain. Unique identifier for retrieving translated strings.
 *
 * @return string
 */
add_filter('gettext', function ($translation, $text, $domain) {
    // The 'default' text domain is reserved for the WP core. If no text domain
    // is passed to a gettext function, the 'default' domain will be used.
    if ('default' === $domain && 'You are now logged out.' === $text) {
        $translation = "This is the modified version of the original string...";
    }

    if ('default' === $domain && 'User registration is currently not allowed.' === $text) {
        $translation = "This is the modified version of the original string...";
    }

    if ('default' === $domain && 'Registration complete. Please check your email.' === $text) {
        $translation = "This is the modified version of the original string...";
    }

    if ('default' === $domain && 'Check your email for the confirmation link.' === $text) {
        $translation = "This is the modified version of the original string...";
    }

    if ('default' === $domain && 'Check your email for your new password.' === $text) {
        $translation = "This is the modified version of the original string...";
    }
    
    if ('default' === $domain && 'Your password has been reset.' === $text) {
        $translation = "This is the modified version of the original string...";
    }

    if ('default' === $domain && '<strong>You have successfully updated WordPress!</strong> Please log back in to see what&#8217;s new.' === $text) {
        $translation = "This is the modified version of the original string...";
    }

    return $translation;
}, 10, 3);


//alter main query
add_filter('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query()) {
        //alter query to include custom post types in search
        if ($query->is_search) {
            if (!empty($_GET['type'])) {
                $query->set('post_type', array( $_GET['type'] ));
            } else {
                $query->set('post_type', array( 'post', 'course', 'vacancies' ));
            }
        }
        // alter the query to change item count for the home and category pages
        if (is_home() || is_category()) {
            $query->set('posts_per_page', 21);
        }
    }

    return $query;
});

//Change wp-loign title
add_filter('login_headertitle', function () {
    return 'Mooiwerk';
});

/*
//prevent woo-commerce users admin dashboard access
add_filter('woocommerce_prevent_admin_access', '__return_false');

//prevent my-account override on login
add_filter('woocommerce_get_myaccount_page_permalink', function ($permalink) {
    return admin_url();
}, 1);
*/

//set age to select instead of checkbox
add_filter('acf/prepare_field/key=field_5c05963d1f567', function ($field) {
    if (is_page('mijn-account')) {
        $field['type'] = 'select';
    }
    return $field;
});

//set experience to select instead of checkbox
add_filter('acf/prepare_field/key=field_5c05966d1f567', function ($field) {
    if (is_page('mijn-account')) {
        $field['type'] = 'select';
    }
    return $field;
});

/*
//change email from address
add_filter('wp_mail_from', function ($original_email_address) {
    return 'info@example.com';
});
*/
 
//Change email from name
add_filter('wp_mail_from_name', function ($original_email_from) {
    return 'Mooiwerk Breda';
});

//Change email to HTML
add_filter('wp_mail_content_type', function ($content_type) {
    return 'text/html';
});

//Add signature to email
add_filter('wp_mail', function ($mail) {
    $mail['message'] .= '<br><br>Deze email is verstuurd vanuit <a href="mooiwerkbreda.nl">Mooiwerk Breda</a>';
    return $mail;
});

/**
 * In WP Admin filter Edit-Comments.php so it shows current users comments only
 * Runs only for the Author role.
 */
add_filter('pre_get_comments', function ($query) {
    
    if (!is_singular('vacancies')) {
        return $query;
    }
        
    if (get_current_user_id() !== get_the_author_meta('ID') || !current_user_can('administrator')) {
        $query->query_vars['post_author'] = get_current_user_id() ;
    }

    return $query;
});

//enable comments by default for vacancy post type
add_filter('wp_insert_post_data', function ($data) {
    if ($data['post_type'] == 'vacancies') {
        $data['comment_status'] = 1;
    }

    return $data;
});
