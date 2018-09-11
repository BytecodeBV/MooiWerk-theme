<?php

namespace App;

use Sober\Controller\Controller;

class Index extends Controller
{
    public function items()
    {
        global $wp_query;
        $return = array_map(function ($post) {
            return [
                'title' => $post->post_title,
                'link' => get_permalink($post->ID),
                'image_link' => get_the_post_thumbnail_url($post->ID, array('500', '500')),
                'excerpt' => $post->post_excerpt,
            ];
        }, $wp_query->posts);

        wp_reset_postdata();
        return $return;
    }

    public function title()
    {
        return get_the_title();
    }
}
