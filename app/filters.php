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
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'mooiwerk-breda-theme') . '</a>';
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
add_filter('registration_errors', function ($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['firstname'])) {
        $errors->add('empty_first_name', __('<strong>FOUT</strong>: Gelieve uw voornaam in te vullen.', 'mooiwerk-breda-theme'));
    }
    if (empty($_POST['lastname'])) {
        $errors->add('empty_last_name', __('<strong>FOUT</strong>: Gelieve uw achternaam in te voeren.', 'mooiwerk-breda-theme'));
    }

    if (!preg_match('/^[a-z0-9]+$/', $sanitized_user_login)) {
        $errors->add('invalid_username', __('<strong>FOUT</strong>: Gebruikersnaam mag alleen kleine letters en geen speciale tekens bevatten.', 'mooiwerk-breda-theme'));
    }

    return $errors;
}, 10, 3);

//alter main query
add_filter('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query()) {
        //alter query to include custom post types in search
        if ($query->is_search) {
            if (!empty($_GET['type']) && $_GET['type'] != '*') {
                $query->set('post_type', [$_GET['type']]);
            } else {
                $query->set('post_type', ['post', 'class', 'vacancies']);
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
    return __('Mooiwerk', 'mooiwerk-breda-theme');
});

//Change email from name
add_filter('wp_mail_from_name', function ($original_email_from) {
    return __('Mooiwerk Breda', 'mooiwerk-breda-theme');
});

//Add signature to email
add_filter('wp_mail', function ($mail) {
    $mail['message'] .= '<br><br>' . sprintf(__('Deze email is verstuurd vanuit <a href="%s">Mooiwerk Breda</a>', 'mooiwerk-breda-theme'), home_url());
    return $mail;
});

// Sets reply-to if it doesn't exist already.
add_filter('wp_mail', function ($args) {
    if (!isset($args['headers'])) {
        $args['headers'] = [];
    }

    $headers_ser = serialize($args['headers']);

    // Does it exist already?
    if (stripos($headers_ser, 'Reply-To:') !== false) {
        return $args;
    }

    $site_name = __('Mooiwerk Breda', 'mooiwerk-breda-theme');
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

    if (in_array('organisation', (array) $user->roles)) {
        $setup_page = get_page_by_title('Opstelling');
        if (get_field('logged-in', 'user_' . $user->ID) == false && !empty($setup_page)) {
            //Redirect users to to multipage setup page
            $url = home_url('/' . $setup_page->post_name);
        } else {
            //Redirect users to mijn-account
            $url = home_url('/mijn-account');
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
    //in_array('volunteer', (array) $user->roles) ||
    if (in_array('organisation', (array) $user->roles)) {
        $setup_page = get_page_by_title('Opstelling');
        if (get_field('logged-in', 'user_' . $user->ID) == false && !empty($setup_page)) {
            //Redirect users to multipage setup page
            $redirect = home_url('/' . $setup_page->post_name);
        } else {
            //Redirect users to mijn-account
            $redirect = home_url('/mijn-account');
        }
    } elseif ($role == 'customer' || $role == 'subscriber') {
        //Redirect customers and subscribers to the "My Account" page
        $redirect = get_permalink(wc_get_page_id('myaccount'));
    } else {
        //Redirect authors and above to the dashboard
        $redirect = admin_url();
    }
    return $redirect;
}, 10, 2);

//woocommerce login redirect hack
add_filter('woocommerce_prevent_admin_access', '__return_false');

// Add New Fields to woocommerce billing address
add_filter('woocommerce_checkout_fields', function ($fields) {
    $fields['billing']['billing_interpolation'] = [
        'label' => __('Tussenvoeging', 'mooiwerk-breda-theme'),
        'placeholder' => _x('Tussenvoeging', 'placeholder', 'mooiwerk-breda-theme'),
        'required' => false,
        'clear' => true,
        'priority' => 30
     ];

    $fields['billing']['billing_title'] = [
        'label' => __('Aanhef', 'mooiwerk-breda-theme'),
        'placeholder' => _x('Aanhef', 'placeholder', 'mooiwerk-breda-theme'),
        'required' => true,
        'clear' => true,
        'priority' => 5
     ];

    return $fields;
});

// set default value for vacancy expiry
add_filter('acf/load_value/key=field_5b7efba009d6d', function ($value, $post_id, $field) {
    if (empty($value)) {
        $value = date('Y-m-d', strtotime('+3 months', strtotime('now')));
    }

    return $value;
}, 10, 3);

// change status to expiry if vacancy has expired
add_filter('acf/load_value/key=field_5bc8a669b23c2', function ($value, $post_id, $field) {
    $expiry = get_field('date', $post_id);
    $date = date_create_from_format('d/m/Y', $expiry) ? date_create_from_format('d/m/Y', $expiry) : date_create_from_format('Y-m-d', $expiry);
    $date = date_format($date, 'm/d/Y');
    if ($value != 'verlopen' && isExpired($date)) {
        $value = 'verlopen';
    }
    return $value;
}, 10, 3);

add_filter('tml_shortcode', function ($content, $form, $arg) {
    if ($form == 'login' || $form == 'lostpassword') {
        $signup_landing = get_page_by_title(__('Account aanmaken', 'mooiwerk'));
        $content = str_replace(
            '<li class="tml-register-link"><a href="' . home_url('/registreren/') . '">' . __('Registreren', 'mooiwerk') . '</a></li>',
            '<li class="tml-register-link"><a href="' . home_url('/' . $signup_landing->post_name) . '/">' . __('Registreer', 'mooiwerk') . '</a></li>',
            $content
        );
    }
    return $content;
}, 10, 3);

add_filter('page_template', function ($template) {
    global $post;
    if (in_array(
        $post->post_title,
        [
            __('Uitloggen', 'mooiwerk'),
            __('Registreren', 'mooiwerk'),
            __('Registreer Organisatie', 'mooiwerk'),
            __('Registreer Vrijwilliger', 'mooiwerk'),
            __('Maak hier een veilig wachtwoord aan', 'mooiwerk'),
            __('Wachtwoord reset', 'mooiwerk')
        ]
    )) {
        $template = get_template_directory() . '/views/template-centered.blade.php';
    }
    return $template;
});

//disable payment
add_filter('woocommerce_cart_needs_payment', '__return_false');

/**
 * Add Bootstrap Class to WooCommerce Checkout Fields
 */
add_filter('woocommerce_form_field_args', function ($args, $key, $value) {
    array_push($args['input_class'], 'form-control');
    array_push($args['class'], 'form-group', 'mb-3');

    return $args;
}, 10, 3);

//ensure only one ticket is purchased per customer
add_filter('woocommerce_is_sold_individually', function ($return, $product) {
    if ($product->product_type == 'wcs_ticket') {
        return true;
    }
    return $return;
}, 10, 2);

//fallback if sold individually doesn't work
add_filter('woocommerce_quantity_input_args', function ($args, $product) {
    if ($product->product_type == 'wcs_ticket') {
        if (!is_cart()) {
            $args['max_value'] = 1;
        } else {
            // For product pages, tho not necessary (just caution)
            $args['max_value'] = 1;
        }
    }
    return $args;
}, 10, 2);

//Remove "successfully added to your cart" alert form checkout page
add_filter('wc_add_to_cart_message_html', '__return_null');

/**
 * Pre-populate Woocommerce checkout fields
 */
add_filter('woocommerce_checkout_get_value', function ($input, $key) {
    global $current_user;
    switch ($key) {
        case 'billing_first_name':
        case 'shipping_first_name':
            return $current_user->first_name;
            break;

        case 'billing_last_name':
        case 'shipping_last_name':
            return $current_user->last_name;
            break;
        case 'billing_email':
            return $current_user->user_email;
            break;
        case 'billing_phone':
            return $current_user->phone;
            break;
    }
}, 10, 2);

/**
 * Redirect users after add to cart.
 */
add_filter('woocommerce_add_to_cart_redirect', function ($url) {
    return wc_get_cart_url();
});

// acf/load_value - filter for every value load
add_filter('acf/load_value', function ($value, $post_id, $field) {
    if ($field['key'] == 'field_5bb48c0adb986' || $field['key'] == 'field_5bb48c0adb987') {
        $user = wp_get_current_user();
        if ($user->ID != 0) {
            if (empty($value) || $value != $user->user_email) {
                $value = $user->user_email;
            }
        }
    }
    return $value;
}, 10, 3);

//preserve login messages for use in redirection to the login page
add_filter('new_user_approve_pending_message', function ($message) {
    if (!empty($message)) {
        //use $_GET as a bus/global to store login messages for access by subsequent hooks
        $_GET['nua_message'] = base64_encode($message);
    }
    return $message;
});

//fix for the nua login page error: 
//redirect to login page instead of calling login_header funcion which is unavailable atm
add_filter('new_user_approve_registration_message', function ($message) {
    $arg = '';
    if (!empty($_GET['nua_userrole']) && $_GET['nua_userrole'] = 'volunteer') {
        $arg = 'checkemail=confirm';
    } elseif (!empty($_GET['nua_message'])) {
        $arg = 'registration_required='.$_GET['nua_message'];
    }
    $arg .= '&new_user_approve_registration_message='.base64_encode($message);
    $redirect_to = ! empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : site_url('wp-login.php?'.$arg);
    wp_safe_redirect($redirect_to);
    exit;
});

//Add nua error messages to login page
add_filter('wp_login_errors', function ($errors) {
    if (!empty($_GET['new_user_approve_registration_message'])) {
        $errors->add('new_user_approve_registration_message', base64_decode($_GET['new_user_approve_registration_message']), 'message');
    }
    if (!empty($_GET['registration_required'])) {
        $errors->add('registration_required', base64_decode($_GET['registration_required']), 'message');
    }
    return $errors;
});

//Replace nua approve message with wce registration message and swap wce tags with nua tags
add_filter('new_user_approve_approve_user_message_default', function ($message) {
    if (!empty($_GET['nua_userrole']) && $_GET['nua_userrole'] == 'volunteer') {
        $custom = '<p>Beste {username},</p>';
        $custom .= '<p>Welkom bij MOOIWERK. Wat leuk dat jij je als vrijwilliger hebt aangemeld.'
            .' Om je aanmelding compleet te maken ontvang je hier je wachtwoord. Dit kun je'
            .' zelf aanpassen als je ingelogd bent:</p>';
        $custom .= '<p>{password link}</p>';
        $custom .= '<p>Wist je dat je als Bredase vrijwilliger gratis kan leren en ontwikkelen?'
            .' Kijk hier voor ons actuele aanbod:'
            .' <a href="www.mooiwerkbreda.nl/vrijwilligersacademie">www.mooiwerkbreda.nl/vrijwilligersacademie</a></p>';
        $custom .= '<p>Heb je vragen? Neem een kijkje bij de veel gestelde vragen. Je kan ook'.
            ' een chat starten door te klikken op de groene balk rechts onder op de website.</p>';
        $custom .= '<p>Met vriendelijke groet,</p>';
        $custom .= '<p>Team MOOIWERK</p>';
    
        return $custom;
    }

    //TODO: get userrole in admin approval flow and store in $_GET for this to fire
    if (!empty($_GET['nua_userrole']) && $_GET['nua_userrole'] == 'organisation') {
        $custom = '<p>Beste {username},</p>';
        $custom .= '<p>Welkom bij MOOIWERK. De aanvraag is goedgekeurd!</p>';
        $custom .= '<p>Om je aanmelding compleet te maken ontvang je hier je wachtwoord.'
            .' Dit kun je zelf aanpassen als je ingelogd bent: </p>';
        $custom .= '<p>{password link}</p>';
        $custom .= '<p>Vul je profiel eerst zoveel mogelijk aan om het zo aantrekkelijk mogelijk te maken.'
            .' Vervolgens kun je aan de slag met het plaatsen van vacatures en reageren op reacties van vrijwilligers.</p>';
        $custom .= '<p>Heb je vragen? Neem een kijkje bij de veel gestelde vragen. Je kan ook een chat starten door te'
            .' klikken op de groene balk rechts onder op de website.</p>';
        $custom .= '<p>Met vriendelijke groet,</p>';
        $custom .= '<p>Team MOOIWERK</p>';
    
        return $custom;
    }

    return $message;
});

//replace reset_password_url tag handler with a custom function
add_filter('nua_email_tags', function ($email_tags) {
    $count = 0;
    foreach ($email_tags as $email_tag) {
        if ($email_tag['tag'] == 'reset_password_url') {
            $email_tags[$count]['function'] = function ($attributes) {
                $link = '';
                if (function_exists('nua_email_tag_reset_password_url')) {
                    $url = nua_email_tag_reset_password_url($attributes);
                    $link = '<a href="'.$url.'">Set New Password</a>';
                }
                return $link;
            };
        }
        $count++;
    }
    return $email_tags;
});

//Replace nua approve subject with wce registration subject and resolve tags
add_filter('new_user_approve_approve_user_subject', function ($subject) {
    if (!empty($_GET['nua_userrole'])) {
        $subject = ($_GET['nua_userrole'] == 'organisation')? 'MOOIWERK - aanvraag is goed gekeurd' : 'Welkom bij MOOIWERK ----';
    }
    return $subject;
});

//Use created wce email template to send user approval email
function use_wce_template ($message, $user)
{
    $settings = get_option('wce_email_settings');
    //check if wce is enabled for user approval email
    if (!empty($settings['allow_custom_registration_email']) && $settings['allow_custom_registration_email'] == 'true') {
        $info = array('emailbody' => $message);
        //check if template processor exists
        if (class_exists('WCE_TEMPLATE_PROCESSOR')) {
            $obj = new \WCE_TEMPLATE_PROCESSOR();
            //use wce template
            $message = $obj->get_email_content($info);
            //Use $_GET as a bus/global to store $user info for access by subsequent hooks that need it
            if (!empty($user)) {
                $_GET['user'] = $user;
            }
        }
    }
    return $message;
}
add_filter('new_user_approve_approve_user_message', __NAMESPACE__ . '\\use_wce_template', 10, 2);

/**
 * The default email message that will be sent to users as they are denied.
 *
 * @return string
 */
add_filter('new_user_approve_deny_user_message_default', function ($message) {
    //TODO: get userrole in admin approval flow and store in $_GET for this to fire
    if (!empty($_GET['nua_userrole']) && $_GET['nua_userrole'] == 'organisation') {
        $custom = '<p>Beste {username},</p>';
        $custom .= '<p>Helaas is je aanvraag afgekeurd.</p>';
        $custom .= '<p>Hiervoor kunnen verschillende redenen zijn:</p>';
        $custom .= '</ul>';
        $custom .= '<li>Ben je geen organisatie, maar heb je een individuele hulpvraag? Dan kun je'
            .'terecht bij Zorg voor elkaar Breda (076 – 525 15 15).</li>';
        $custom .= '<li>Een organisatie mag maar één profiel hebben, maar misschien heeft iemand'
            .'anders van jullie organisatie al een profiel aangemaakt.</li>';
        $custom .= '</ul>';
        $custom .= '<p>Wil je graag duidelijkheid over de reden waarom je account geweigerd is? Start dan'
            .'een chat via de groene balk rechts onder op de website.</p>';
        $custom .= '<p>Met vriendelijke groet,</p>';
        $custom .= '<p>Team MOOIWERK</p>';

        return $custom;
    }

    return $message;
});

//Custom nua deny mail subject
add_filter('new_user_approve_deny_user_subject', function ($subject) {
    $subject = 'MOOIWERK - aanvraag is afgekeurd';
    return $subject;
});

//use wce email template for user deny message
add_filter('new_user_approve_deny_user_message', __NAMESPACE__ . '\\use_wce_template', 10, 2);


/**
 * The default message that will be shown to the user after registration has completed.
 *
 * @return string
 */
add_filter('new_user_approve_pending_message_default_x', function ($message) {
    if (!empty($_GET['nua_userrole']) && $_GET['nua_userrole'] == 'organisation') {
        $custom = '<p>Beste {username},</p>';
        $custom .= '<p>Wat leuk dat jij jouw organisatie hebt aangemeld. Wij gaan met jouw aanvraag aan de slag.</p>';
        $custom .= '<pHeb je in de tussentijd vragen? Neem een kijkje bij de veel gestelde vragen. Je kan ook een'
            .' chat starten door te klikken op de groene balk rechts onder op de website</p>';
        $custom .= '<p>Met vriendelijke groet,</p>';
        $custom .= '<p>Team MOOIWERK</p>';

        return $custom;
    }

    return $message;
});

//Custom nua pending mail subject
add_filter('new_user_approve_deny_user_subject', function ($subject) {
    $subject = 'MOOIWERK - aanvraag is geplaatst';
    return $subject;
});

//use wce email template for user pending message
add_filter('new_user_approve_deny_user_message', 'use_wce_template', 10, 2);

//Allow comment reply on Yeost SEO
add_filter('wpseo_remove_reply_to_com', function ($bool) {
    return false;
});
