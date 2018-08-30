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
    tml_add_form_field('register', 'role', array(
        'type'     => 'dropdown',
        'label'    => 'Role',
        'options'   => ['volunteer' => 'Vrijwilliger','organisation' => 'Organisatie'],
        'id'       => 'role',
        'priority' => 15,
        'class' => 'form-control',
        'render_args' => [
            'before' => '<div class="form-group">',
            'after' => '</div>'
        ]
    ));
});

//save theme-my-login fields: in this case set roles
add_action('user_register', function ($user_id) {
    if (! empty($_POST['role'])) {
        $user = new WP_User($user_id);
        $user->set_role($_POST['role']);
    }
});

//remove acf fields from theme-my-login registration form
add_action('init', function () {
    tml_remove_form_field('register', 'register_form');
});

//change theme-my-login action
add_action('tml_registered_action', function ($action, $action_obj) {
    if ('resetpass' == $action) {
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

/*
//add TOS to comment form
add_action('comment_form_logged_in_after', 'comment_tos_field');
add_action('comment_form_after_fields', 'comment_tos_field');
function comment_tos_field()
{
    if (is_singular('vacancies')) {
        ?>
            <div class="form-check">
                <input type="checkbox" name="tos" id="tos" class="form-check-input"  />
                <label class="form-check-label" for="tos">
                    <a href="<?php echo home_url('algemene-voorwaarden') ?>">Ik ga akkoord met de Algemene Voorwaarden</a>
                </label>       
            <div>
        <?php
    }
}
*/

/* Comment Validation Hooks */
add_action('pre_comment_on_post', function ($comment) {
    if (is_singular('vacancies')) {
        // See if the checkbox #ag_login_accept was checked
        if (!isset($_POST['acf']) || $_POST['acf']['field_5b86d5cc8043b'] !== 1) {
            // Did NOT check the box, do not allow comment
            wp_die(__("Je was het niet eens met onze algemene voorwaarden"));
        }
    }
}, 10, 2);
