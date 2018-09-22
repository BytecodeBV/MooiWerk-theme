<?php

namespace App;

use Sober\Controller\Controller;

class Home extends Controller
{
    public function __construct()
    {
        $this->page_id = get_option('page_for_posts');
    }

    public function homeId()
    {
        return $this->page_id;
    }

    public function blocks()
    {
        $rows = get_field('content_blocks', $this->page_id);
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


    public function links()
    {
        $rows = get_field('links', $this->page_id);
        $return = [];
        if ($rows) {
            $return = array_map(function ($row) {
                return [
                    'title' => $row['location'],
                    'url' => $row['url'],
                ];
            }, $rows);
        }
       
        return $return;
    }

    public function teams()
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

    public function teamTitle()
    {
        return __('Teams', 'mooiwerk-breda-theme');
    }
    
    public function posts()
    {
        global $wp_query;
        $return = array_map(function ($post) {
            return [
                'title' => $post->post_title,
                'link' => get_permalink($post->ID),
                'image_link' => get_the_post_thumbnail_url($post->ID, array('500', '500')),
                'excerpt' => $post->post_excerpt,
            ];
        }, array_slice($wp_query->posts, 0, 10));

        wp_reset_postdata();
        return $return;
    }
}
