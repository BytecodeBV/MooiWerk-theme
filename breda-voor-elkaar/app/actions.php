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
            if ('hidden' == $field->get_type()) {
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
    
    if (strpos($_SERVER["REQUEST_URI"], 'volunteer') || strpos($_SERVER["REQUEST_URI"], 'organisation') || isset($_POST['type'])) {
        tml_add_form_field('register', 'firstname', array(
                'type'     => 'text',
                'label'    => __('Voornaam'),
                'value'    => tml_get_request_value('firstname', 'post'),
                'id'       => 'firstname',
                'priority' => 5,
                'class' => 'form-control',
        ));

        tml_add_form_field('register', 'initials', array(
            'type'     => 'text',
            'label'    => __('Tussenvoegsel'),
            'value'    => tml_get_request_value('initials', 'post'),
            'id'       => 'initials',
            'priority' => 5,
            'class' => 'form-control',
        ));

        tml_add_form_field('register', 'lastname', array(
                'type'     => 'text',
                'label'    => __('Achternaam'),
                'value'    => tml_get_request_value('lastname', 'post'),
                'id'       => 'lastname',
                'priority' => 5,
                'class' => 'form-control',
        ));

        tml_add_form_field('register', 'type', array(
            'type'     => 'hidden',
            'value'    => (strpos($_SERVER["REQUEST_URI"], 'volunteer') || $_POST['type'] == 'volunteer') ?'volunteer':'organisation',
            'priority' => 35,
        ));
    } else {
        tml_add_form_field('register', 'role', array(
            'type'     => 'dropdown',
            'label'    => 'Rol',
            'options'   => ['' => 'Standaard', 'volunteer' => 'Vrijwilliger','organisation' => 'Organisatie'],
            'id'       => 'role',
            'priority' => 15,
            'class' => 'form-control',
            'render_args' => [
                'before' => '<div class="form-group">',
                'after' => '</div>'
            ]
        ));
    }
});

//save theme-my-login fields: in this case set roles
add_action('user_register', function ($user_id) {
    if (! empty($_POST['type'])) {
        $user = new WP_User($user_id);
        if ($_POST['type'] == 'volunteer') {
            $user->set_role($_POST['type']);
        } elseif ($_POST['type'] == 'organisation') {
            $user->set_role($_POST['type']);
        }

        if (!empty($_POST['firstname'])) {
            update_field('first-name', sanitize_text_field($_POST['firstname']), 'user_'.$user_id);
        }

        if (!empty($_POST['lastname'])) {
            update_field('last-name', sanitize_text_field($_POST['lastname']), 'user_'.$user_id);
        }

        if (!empty($_POST['initials'])) {
            update_field('initials', sanitize_text_field($_POST['initals']), 'user_'.$user_id);
        }
    }
});


//remove acf fields from theme-my-login registration form
add_action('init', function () {
    tml_remove_form_field('register', 'register_form');
}, 10);


//change theme-my-login action
add_action('tml_registered_action', function ($action, $action_obj) {
    if ('lostpassword' == $action) {
        // This changes the page title
        $action_obj->set_title('Maak hier een veilig wachtwoord aan');

        // This changes the link text shown on other forms. Use any string value
        // to set the text directly, `true` to use the action title, or `false`
        // to hide.
        $action_obj->show_on_forms = false;
    }
}, 10, 2);


//set google map api key for acf
add_action('acf/init', function () {
    if (get_option('acf_google_map')) {
        acf_update_setting('google_api_key', get_option('acf_google_map'));
    }
});

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


