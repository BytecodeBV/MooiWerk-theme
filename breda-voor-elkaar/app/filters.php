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
    if (empty($_POST['firstname'])) {
        $errors->add('empty_first_name', '<strong>FOUT</strong>: Gelieve uw voornaam in te vullen..');
    }
    if (empty($_POST['lastname'])) {
        $errors->add('empty_last_name', '<strong>FOUT</strong>: Gelieve uw achternaam in te voeren.');
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
                $query->set('post_type', array( 'post', 'class', 'vacancies' ));
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
add_filter('acf/prepare_field/key=field_5b7ef21994886', function ($field) {
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

/*
//Change email to HTML
add_filter('wp_mail_content_type', function ($content_type) {
    return 'text/html';
});
*/


//Add signature to email
add_filter('wp_mail', function ($mail) {
    $mail['message'] .= '<br><br>Deze email is verstuurd vanuit <a href="mooiwerkbreda.nl">Mooiwerk Breda</a>';
    return $mail;
});


// Sets reply-to if it doesn't exist already.
add_filter('wp_mail', function ($args) {
    if (!isset($args['headers'])) {
        $args['headers'] = array();
    }
    
    $headers_ser = serialize($args['headers']);

    // Does it exist already?
    if (stripos($headers_ser, 'Reply-To:') !== false) {
        return $args;
    }

    $site_name = 'Mooiwerk Breda';
    $admin_email = get_option('admin_email');

    $reply_to_line = "Reply-To: $site_name <$admin_email>";

    if (is_array($args['headers'])) {
        $args['headers'][] = 'h:' . $reply_to_line;
        $args['headers'][] = $reply_to_line . "\r\n";
    } else {
        $args['headers'] .= 'h:' . $reply_to_line . "\r\n";
        $args['headers'] .= $reply_to_line . "\r\n";
    }

    return $args;
});


/**
 * In WP Admin filter Edit-Comments.php so it shows current users comments only
 * Runs only for the Author role.
 */
add_filter('pre_get_comments', function ($query) {
        
    if (!is_singular('vacancies')) {
        return $query;
    }
        
    if (get_current_user_id() !== get_the_author_meta('ID')) {
        $query->query_vars['author__in'] = [get_current_user_id(), get_the_author_meta('ID')] ;
    }

    return $query;
});

add_filter('login_redirect', function ($url, $query, $user) {
    $role = $user->roles[0];
    $custom_home = home_url('/mijn-account');
    $setup_page = get_page_by_title('Opstelling');
    $setup_home = home_url('/'.$setup_page->post_name);
    if (in_array('organisation', (array) $user->roles)) {
        if (get_field('logged-in', "user_".$user->ID) == false) {
            //Redirect users to mijn-account
            $url = $setup_home;
        } else {
            //Redirect users to mijn-account
            $url = $custom_home;
        }
    }

    return $url;
}, 10, 3);

/**
 * Redirect users to custom URL based on their role after login
 *
 * @param string $redirect
 * @param object $user
 * @return string
 */

add_filter('woocommerce_login_redirect', function ($redirect, $user) {
    // Get the first of all the roles assigned to the user
    $role = $user->roles[0];
    $dashboard = admin_url();
    $custom_home = home_url('/mijn-account');
    $setup_page = get_page_by_title('Opstelling');
    $setup_home = home_url('/'.$setup_page->post_name);
    $myaccount = get_permalink(wc_get_page_id('myaccount'));
    //in_array('volunteer', (array) $user->roles) ||
    if (in_array('organisation', (array) $user->roles)) {
        if (get_field('logged-in', "user_".$user->ID) == false) {
            //Redirect users to mijn-account
            $redirect = $setup_home;
        } else {
            //Redirect users to mijn-account
            $redirect = $custom_home;
        }
    } elseif ($role == 'customer' || $role == 'subscriber') {
        //Redirect customers and subscribers to the "My Account" page
        $redirect = $myaccount;
    } else {
        //Redirect authors and above to the dashboard
        $redirect = $dashboard;
    }
    return $redirect;
}, 10, 2);

//woocommerce login redirect hack
add_filter('woocommerce_prevent_admin_access', '__return_false');

// Add New Fields to woocommerce billing address
add_filter('woocommerce_checkout_fields', function ($fields) {
    $fields['billing']['interpolation'] = array(
        'label'     => __('Tussenvoeging', 'woocommerce'),
        'placeholder'   => _x('Tussenvoeging', 'placeholder', 'woocommerce'),
        'required'  => false,
        'clear'     => true
     );
 
    $fields['billing']['title'] = array(
        'label'     => __('Titel', 'woocommerce'),
        'placeholder'   => _x('Titel', 'placeholder', 'woocommerce'),
        'required'  => true,
        'clear'     => true
     );
    
     return $fields;
});

// Add Billing House # to Address Fields
add_filter('woocommerce_order_formatted_billing_address', function ($fields, $order) {
    $fields['billing_interpolation'] = get_post_meta($order->id, '_billing_interpolation', true);
    $fields['billing_title'] = get_post_meta($order->id, '_billing_title', true);
    return $fields;
}, 10, 2);

// acf/load_value/key={$field_key} - filter for a specific field based on it's name
add_filter('acf/load_value/key=field_5b7efba009d6d', function ($value, $post_id, $field) {
    // run the_content filter on all textarea values
    if (empty($value)) {
        $value = date('Y-m-d', strtotime("+3 months", strtotime("now")));
    }
    

    return $value;
}, 10, 3);

add_filter('tml_shortcode', function ($content, $form, $arg) {

    if ($form == 'login') {
        $organisation = get_page_by_title('Registreer Organisatie');
        $content = str_replace(
            '<li class="tml-register-link"><a href="'.home_url('/registreren/').'">Registreren</a></li>',
            '<li class="tml-register-link"><a href="'.home_url('/'.$organisation->post_name).'">Registreer Organisatie</a></li>',
            $content
        );
    }

    return $content;
}, 10, 3);

add_filter("page_template", function ($template) {
    global $post;
    if (in_array(
        $post->post_title,
        [
        'Opstelling',
        'Uitloggen',
        'Registreren',
        'Registreer Organisatie',
        'Registreer Vrijwilliger',
        'Maak hier een veilig wachtwoord aan',
        'Wachtwoord reset'
        ]
    )) {
        $template = get_template_directory() .'/views/template-centered.blade.php';
    }
    return $template;
});
