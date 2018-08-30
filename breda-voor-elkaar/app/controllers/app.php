<?php

namespace App;

use Sober\Controller\Controller;

class App extends Controller
{
    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Laatste berichten', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Resultaten voor %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Niet gevonden', 'sage');
        }
        return get_the_title();
    }

    public function primarymenu()
    {
        $args = array(
            'theme_location' => 'primary_navigation',
            'menu_class' => 'navbar-nav ml-auto',
            'container_id' => 'main-nav',
            'container_class' => 'collapse navbar-collapse primary-menu',
            'walker' => new wp_bootstrap4_navwalker(),
        );
        return $args;
    }
   
    public function share()
    {
        global $post;
        global $wp;

        $url = home_url(add_query_arg(array(), $wp->request));
        $title = str_replace(' ', '+', $post->post_title);
        $language = get_locale();
        $summary = str_replace(' ', '+', $post->post_excerpt);
        return [
            'twitter' => "https://twitter.com/intent/tweet?url={$url}&text={$summary}",
            'facebook' => "https://www.facebook.com/sharer.php?u={$url}",
            'linkedin' => "https://www.linkedin.com/shareArticle?mini=true&".
                "url={$url}&title={$title}&summary={$summary}",
            'gplus' => "https://plus.google.com/share?url={$url}&text={$summary}&hl={$language}",
        ];
    }
}
