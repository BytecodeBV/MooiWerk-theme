<?php

namespace App;

use Sober\Controller\Controller;

class FrontPage extends Controller
{
    public function blocks()
    {
        $rows = get_field('content_blocks');
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
        $rows = get_field('links');
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

    public function courses()
    {
        $args = array(
            'post_type' => array('class'),
            'posts_per_page' => 3,
        );
        $query = new \WP_Query($args);
        $return = array_map(function ($post) {
            return [
                'title' => $post->post_title,
                'link' => get_permalink($post->ID),
                'excerpt' => wp_kses_post(wp_trim_words($post->post_content, 40, '...')),
                'date' =>  date_i18n("j M", strtotime(get_field("date", $post->ID))),
                'lesson' => get_field("sub_title", $post->ID),
            ];
        }, $query->posts);
        wp_reset_postdata();
        return $return;
    }

    public function courseLink()
    {
        $class_page = get_page_by_title(__('Vrijwilligersacademie', 'mooiwerk'));
        if (!empty($class_page)) {
            return home_url('/'.$class_page->page_name);
        }
        return home_url('/Vrijwilligersacademie');
    }

    public function courseIntro()
    {
        return get_field('course_subtitle');
    }

    public function courseDescription()
    {
        return get_field('course_description');
    }

    public function courseTitle()
    {
        return get_field('course_title');
    }

    public function news()
    {
        $args = array(
            'post_type' => array('post'),
            'posts_per_page' => 6,
            'category_name' => 'news',
        );

        $query = new \WP_Query($args);
        $return = array_map(function ($post) {
            return [
                'title' => $post->post_title,
                'excerpt' => $post->post_content,
                'link' => get_permalink($post->ID),
                'image_link' => get_the_post_thumbnail_url($post->ID, [500, 500]),
            ];
        }, $query->posts);
        wp_reset_postdata();
        return $return;
    }

    public function newsTitle()
    {
        return __('Nieuws', 'mooiwerk-breda-theme');
    }
}
