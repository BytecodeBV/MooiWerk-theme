<?php
//prevent volunteers/organisations access to wp-admin
add_action('admin_init', function () {
    if (!(current_user_can('edit_posts')) && !(defined('DOING_AJAX') && DOING_AJAX)) {
        wp_redirect(home_url('/mijn-account'));
        exit;
    }
});

//style theme-my-login with bootstrap
add_action('init', function () {
    foreach (tml_get_forms() as $form) {
        foreach (tml_get_form_fields($form) as $field) {
            if ('hidden' == $field->get_type() || 'radio-group' == $field->get_type()) {
                continue;
            }

            $field->render_args['before'] = '<div class="form-group">';
            $field->render_args['after'] = '</div>';

            if ('submit' == $field->get_type()) {
                $field->add_attribute('class', 'btn btn-block');
            } elseif ('checkbox' != $field->get_type()) {
                $field->add_attribute('class', 'form-control');
            }
        }
    }
});

//add roles to theme-my-login fields register option
add_action('init', function () {
    tml_add_form_field('register', 'firstname', [
            'type' => 'text',
            'label' => __('Voornaam', 'mooiwerk-breda-theme'),
            'value' => tml_get_request_value('firstname', 'post'),
            'id' => 'firstname',
            'priority' => 5,
            'class' => 'form-control',
    ]);

    tml_add_form_field('register', 'initials', [
        'type' => 'text',
        'label' => __('Tussenvoegsel', 'mooiwerk-breda-theme'),
        'value' => tml_get_request_value('initials', 'post'),
        'id' => 'initials',
        'priority' => 5,
        'class' => 'form-control',
    ]);

    tml_add_form_field('register', 'lastname', [
            'type' => 'text',
            'label' => __('Achternaam', 'mooiwerk-breda-theme'),
            'value' => tml_get_request_value('lastname', 'post'),
            'id' => 'lastname',
            'priority' => 5,
            'class' => 'form-control',
    ]);
    $organisation = get_page_by_title('Registreer Organisatie');
    $volunteer = get_page_by_title('Registreer Vrijwilliger');

    if (!is_null($organisation) && strpos($_SERVER['REQUEST_URI'], $organisation->post_name)) {
        tml_add_form_field('register', 'type', [
            'type' => 'hidden',
            'value' => 'organisation',
            'priority' => 35,
        ]);
    } elseif (!is_null($volunteer) && strpos($_SERVER['REQUEST_URI'], $volunteer->post_name)) {
        tml_add_form_field('register', 'type', [
            'type' => 'hidden',
            'value' => 'volunteer',
            'priority' => 35,
        ]);
    } elseif (isset($_POST['type'])) {
        tml_add_form_field('register', 'type', [
            'type' => 'hidden',
            'value' => $_POST['type'],
            'priority' => 35,
        ]);
    } else {
        tml_add_form_field('register', 'type', [
            'type' => 'dropdown',
            'label' => 'Rol',
            'options' => ['' => __('Standaard', 'mooiwerk-breda-theme'), 'volunteer' => __('Vrijwilliger', 'mooiwerk-breda-theme'), 'organisation' => __('Organisatie', 'mooiwerk-breda-theme')],
            'id' => 'type',
            'priority' => 15,
            'class' => 'form-control',
            'render_args' => [
                'before' => '<div class="form-group">',
                'after' => '</div>'
            ]
        ]);
    }
});

//save theme-my-login fields: in this case set roles
add_action('user_register', function ($user_id) {
    if (!empty($_POST['type'])) {
        $user = new WP_User($user_id);
        if ($_POST['type'] == 'volunteer') {
            $user->set_role($_POST['type']);
        } elseif ($_POST['type'] == 'organisation') {
            $user->set_role($_POST['type']);
        }
    }

    if (!empty($_POST['firstname'])) {
        update_field('first-name', sanitize_text_field($_POST['firstname']), 'user_' . $user_id);
    }

    if (!empty($_POST['lastname'])) {
        update_field('last-name', sanitize_text_field($_POST['lastname']), 'user_' . $user_id);
    }

    if (!empty($_POST['initials'])) {
        update_field('position', sanitize_text_field($_POST['initials']), 'user_' . $user_id);
    }

    update_field('logged-in', false, 'user_' . $user_id);
});

//remove acf fields from theme-my-login registration form
add_action('init', function () {
    tml_remove_form_field('register', 'register_form');
}, 10);

//change theme-my-login action
add_action('tml_registered_action', function ($action, $action_obj) {
    if ('lostpassword' == $action) {
        // This changes the page title
        $action_obj->set_title(__('Wachtwoord vergeten', 'mooiwerk-breda-theme'));

        // This changes the link text shown on other forms. Use any string value
        // to set the text directly, `true` to use the action title, or `false`
        // to hide.
        $action_obj->show_on_forms = true;
    }
}, 10, 2);

//set google map api key for acf
add_action('acf/init', function () {
    if (get_option('acf_google_map')) {
        acf_update_setting('google_api_key', get_option('acf_google_map'));
    }
});

add_action('acf/submit_form', function ($form, $post_id) {
    $setup_page = get_page_by_title('Opstelling');
    //error prone, page might be empty
    $setup_url = home_url('/' . $setup_page->post_name);
    $redirect = false;
    switch ($form['id']) {
        case 'stage-1':
            $redirect = add_query_arg('stage', 2, $setup_url);
            break;
        case 'stage-2':
            $redirect = add_query_arg('stage', 3, $setup_url);
            break;
        case 'stage-3':
            $redirect = add_query_arg('stage', 4, $setup_url);
            break;
        case 'stage-4':
            //error prone, page might not exist
            $redirect = home_url('/mijn-account');
            break;
        case 'new-vacancy-1':
            $redirect = add_query_arg(
                [
                    'stage' => '2',
                    'post' => $post_id,
                ],
                home_url('/nieuwe-vacature')
            );
            break;
        case 'new-vacancy-2':
            $redirect = add_query_arg(
                [
                    'stage' => '3',
                    'post' => $post_id,
                ],
                home_url('/nieuwe-vacature')
            );
            break;
        case 'new-vacancy-3':
            $redirect = add_query_arg(
                [
                    'stage' => '4',
                    'post' => $post_id,
                ],
                home_url('/nieuwe-vacature')
            );
            break;
        case 'new-vacancy-4':
            $post = get_post($post_id);
            //error prone, object might be null
            $redirect = home_url('/vacatures/') . $post->post_name;
            break;
    }

    if ($redirect) {
        // redirect
        wp_redirect($redirect);
        exit;
    }
}, 10, 2);

//change wp-login logo
add_action('login_enqueue_scripts', function () {
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo App\asset_path('images/logo.png') ?>);
            height:27px;
            width:207px;
            background-size: 207px 27px;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
    <?php
});

remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

//Show custom checkout fields in admin
add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
    echo '<p><strong>' . __('Titel', 'mooiwerk-breda-theme') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_title', true) . '</p>';
    echo '<p><strong>' . __('Tussenvoeging', 'mooiwerk-breda-theme') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_interpolation', true) . '</p>';
}, 20, 1);

//swap update user email
add_action('acf/save_post', function ($post_id) {
    // bail early if no ACF data
    if (empty($_POST['acf'])) {
        return;
    }

    $fields = $_POST['acf'];

    if (isset($fields['field_5bb48c0adb986']) && filter_var($fields['field_5bb48c0adb986'], FILTER_VALIDATE_EMAIL)) {
        $user = wp_get_current_user();
        if ($user->user_email != $fields['field_5bb48c0adb986']) {
            $user->user_email = $fields['field_5bb48c0adb986'];
            wp_update_user($user);
        }
    }

    if (isset($fields['field_5bb48c0adb986']) && filter_var($fields['field_5bb48c0adb987'], FILTER_VALIDATE_EMAIL)) {
        $user = wp_get_current_user();
        if ($user->user_email != $fields['field_5bb48c0adb987']) {
            $user->user_email = $fields['field_5bb48c0adb987'];
            wp_update_user($user);
        }
    }
}, 20, 1);

/**
 * Add CSS to the admin pages to adjust user-edit.php form
 */
add_action('admin_head', function () {
    echo '<style>
	/** 
	 * Hide ACF email fileds
	 */
    #profile-page .acf-field-5bb4920ddb986, #profile-page .acf-field-5bb4920ddb987 {
    	display: none;
    }
  	</style>';
});

/**
 * Hook into wp_ajax_ to save post ids, then display those posts using get_posts() function
 */

add_action('wp_ajax_subscribe', 'newsletter_subscription');
add_action('wp_ajax_nopriv_subscribe', 'newsletter_subscription');

function newsletter_subscription()
{
    // By default, this sample code is designed to get the result from your ActiveCampaign installation and print out the result
    $url = get_option('ac_token');

    $params = [
        'api_key' => get_option('ac_url'),
        'api_action' => 'contact_sync',
        'api_output' => 'serialize',
    ];

    $post = [
        'email' => $_POST['email'],
    ];

    if ($form = get_option('ac_form')) {
        $post['form'] = $form;
    }

    if ($list = get_option('ac_list')) {
        $post['p[' . $list . ']'] = $list;
    }

    // This section takes the input fields and converts them to the proper format
    $query = '';
    foreach ($params as $key => $value) {
        $query .= urlencode($key) . '=' . urlencode($value) . '&';
    }
    $query = rtrim($query, '& ');

    // This section takes the input data and converts it to the proper format
    $data = '';
    foreach ($post as $key => $value) {
        $data .= urlencode($key) . '=' . urlencode($value) . '&';
    }
    $data = rtrim($data, '& ');

    // clean up the url
    $url = rtrim($url, '/ ');

    // This sample code uses the CURL library for php to establish a connection,
    // submit your request, and show (print out) the response.
    if (!function_exists('curl_init')) {
        wp_send_json_error('CURL not supported. (introduced in PHP 4.0.2)');
    }

    // If JSON is used, check if json_decode is present (PHP 5.2.0+)
    if ($params['api_output'] == 'json' && !function_exists('json_decode')) {
        wp_send_json_error('JSON not supported. (introduced in PHP 5.2.0)');
    }

    // define a final API request - GET
    $api = $url . '/admin/api.php?' . $query;

    $request = curl_init($api); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
    //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

        $response = (string)curl_exec($request); // execute curl post and store results in $response

        // additional options may be required depending upon your server configuration
    // you can find documentation on curl options at http://www.php.net/curl_setopt
    curl_close($request); // close curl object

    if (!$response) {
        wp_send_json_error('Nothing was returned. Do you have a connection to Email Marketing server?');
    }

    // This line takes the response and breaks it into an array using:
    // JSON decoder
    //$result = json_decode($response);
    // unserializer
    $result = unserialize($response);
    // XML parser...
    // ...

    wp_send_json_success($result);
}
