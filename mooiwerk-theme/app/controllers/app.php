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
            return __('Laatste berichten', 'mooiwerk-breda-theme');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Resultaten voor %s', 'mooiwerk-breda-theme'), get_search_query());
        }
        if (is_404()) {
            return __('Niet gevonden', 'mooiwerk-breda-theme');
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

    public static function teams()
    {
        $args = array(
            'post_type' => array('team'),
        );
        $query = new \WP_Query($args);
        $return = array_map(function ($post) {
            $image = wp_get_attachment_image_src(get_field('avatar', $post->ID), 'thumbnail');
            return [
                'name' => $post->post_title,
                'avatar' => $image[0],
                'bio' => get_field('bio', $post->ID),
                'email' => get_field("email", $post->ID),
                'phone' => get_field("phone", $post->ID),
            ];
        }, $query->posts);
        wp_reset_postdata();
        
        return $return;
    }

    public static function getBlocks($id)
    {
        $rows = get_field('content_blocks', $id);
        $return = [];
        if ($rows) {
            $return = array_map(function ($row) {
                return [
                    'title' => $row['title'],
                    'content' => $row['description'],
                    'link' => $row['link'],
                ];
            }, $rows);
        }
       
        return $return;
    }

    

    /**
     * Get the first sentence of a string
     *
     * @link   https://www.winwar.co.uk/2015/01/how-to-grab-the-first-sentence-of-a-wordpress-post/?utm_source=codesnippet
     * 
     * @param  string 		$string 		The string in question
     * @return string 						The first setence of said string
     */
    public static function getSentence($string)
    {
        $sentence = preg_split('/(\.|!|\?)\s/', $string, 2, PREG_SPLIT_DELIM_CAPTURE);
        return $sentence['0'] . $sentence['1'];
    }
}
