<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *  Methods for Plugin Settings
 *
 * @since      1.0.0
 * @package    Wp_MyFavourites
 * @subpackage Wp_MyFavourites/includes
 * @author     Your Name <email@example.com>
 */
class Wp_MyFavourites_Settings
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $wp_myfavourites    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $wp_myfavourites       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function wp_myfavourites_save_posts_settings()
    {
        $posted_data = array();
        $posted_data['wpmf_posts_max'] = !empty($_POST['content']['wpmf_posts_max']) ? (int) trim($_POST['content']['wpmf_posts_max']) : WP_MYFAVOURITES_POST_SELECT_LIMIT;
        $posted_data['wpmf_posts_min_date'] = !empty($_POST['content']['wpmf_posts_min_date']) ? date('Y-m-d', strtotime($_POST['content']['wpmf_posts_min_date'])) : WP_MYFAVOURITES_POSTS_AFTER_DATE;
        $posted_data['wpmf_posts_max_date'] = !empty($_POST['content']['wpmf_posts_max_date']) ? date('Y-m-d', strtotime($_POST['content']['wpmf_posts_max_date'])) : WP_MYFAVOURITES_POSTS_BEFORE_DATE;
        $posted_data['wpmf_posts_inclusive_dates'] = !empty($_POST['content']['wpmf_posts_inclusive_dates']) ? trim($_POST['content']['wpmf_posts_inclusive_dates']) : WP_MYFAVOURITES_POSTS_INCLUSIVE_OF_DATES;
        $posted_data['wpmf_posts_order_field'] = !empty($_POST['content']['wpmf_posts_order_field']) ? trim($_POST['content']['wpmf_posts_order_field']) : WP_MYFAVOURITES_POSTS_ORDER_BY_FIELD;
        $posted_data['wpmf_posts_ignore_sticky'] = !empty($_POST['content']['wpmf_posts_ignore_sticky']) ? trim($_POST['content']['wpmf_posts_ignore_sticky']) : WP_MYFAVOURITES_IGNORE_STICKY_POSTS;
        $posted_data['wpmf_posts_listinig_order'] = !empty($_POST['content']['wpmf_posts_listinig_order']) ? trim($_POST['content']['wpmf_posts_listinig_order']) : WP_MYFAVOURITES_POSTS_ORDER;
        $posted_data['wpmf_posts_post_types'] = !empty($_POST['content']['wpmf_posts_post_types']) ? $_POST['content']['wpmf_posts_post_types'] : WP_MYFAVOURITES_POST_TYPE;
        $posted_data['wpmf_posts_post_statuses'] = !empty($_POST['content']['wpmf_posts_post_statuses']) ? $_POST['content']['wpmf_posts_post_statuses'] : WP_MYFAVOURITES_POST_STATUS;

        update_option(WP_MYFAVOURITES_FAVOURITE_POSTS_SETTINGS, $posted_data);
    }

    public function wp_myfavourites_save_comments_settings()
    {
        $posted_data = array();
        $posted_data['wpmf_comments_max'] = !empty($_POST['content']['wpmf_comments_max']) ? (int) trim($_POST['content']['wpmf_comments_max']) : WP_MYFAVOURITES_COMMENT_SELECT_LIMIT;
        $posted_data['wpmf_comments_min_date'] = !empty($_POST['content']['wpmf_comments_min_date']) ? date('Y-m-d', strtotime($_POST['content']['wpmf_comments_min_date'])) : WP_MYFAVOURITES_COMMENTS_AFTER_DATE;
        $posted_data['wpmf_comments_max_date'] = !empty($_POST['content']['wpmf_comments_max_date']) ? date('Y-m-d', strtotime($_POST['content']['wpmf_comments_max_date'])) : WP_MYFAVOURITES_COMMENTS_BEFORE_DATE;
        $posted_data['wpmf_comments_inclusive_dates'] = !empty($_POST['content']['wpmf_comments_inclusive_dates']) ? trim($_POST['content']['wpmf_comments_inclusive_dates']) : WP_MYFAVOURITES_COMMENTS_INCLUSIVE_OF_DATES;
        $posted_data['wpmf_comments_order_field'] = !empty($_POST['content']['wpmf_comments_order_field']) ? trim($_POST['content']['wpmf_comments_order_field']) : WP_MYFAVOURITES_COMMENTS_ORDER_BY_FIELD;
        $posted_data['wpmf_comments_listinig_order'] = !empty($_POST['content']['wpmf_comments_listinig_order']) ? trim($_POST['content']['wpmf_comments_listinig_order']) : WP_MYFAVOURITES_COMMENTS_ORDER;
        $posted_data['wpmf_comments_post_types'] = !empty($_POST['content']['wpmf_comments_post_types']) ? $_POST['content']['wpmf_comments_post_types'] : WP_MYFAVOURITES_COMMENTS_POST_TYPE;
        $posted_data['wpmf_comments_post_statuses'] = !empty($_POST['content']['wpmf_comments_post_statuses']) ? $_POST['content']['wpmf_comments_post_statuses'] : WP_MYFAVOURITES_COMMENTS_POST_STATUS;
        $posted_data['wpmf_comments_statuses'] = !empty($_POST['content']['wpmf_comments_statuses']) ? $_POST['content']['wpmf_comments_statuses'] : WP_MYFAVOURITES_COMMENTS_STATUS;

        update_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_SETTINGS, $posted_data);
    }

    public function wp_myfavourites_save_media_settings()
    {

        $posted_data = array();
        $posted_data['wpmf_media_max'] = !empty($_POST['content']['wpmf_media_max']) ? (int) trim($_POST['content']['wpmf_media_max']) : WP_MYFAVOURITES_MEDIA_SELECT_LIMIT;
        $posted_data['wpmf_media_min_date'] = !empty($_POST['content']['wpmf_media_min_date']) ? date('Y-m-d', strtotime($_POST['content']['wpmf_media_min_date'])) : WP_MYFAVOURITES_MEDIA_AFTER_DATE;
        $posted_data['wpmf_media_max_date'] = !empty($_POST['content']['wpmf_media_max_date']) ? date('Y-m-d', strtotime($_POST['content']['wpmf_media_max_date'])) : WP_MYFAVOURITES_MEDIA_BEFORE_DATE;
        $posted_data['wpmf_media_order_field'] = !empty($_POST['content']['wpmf_media_order_field']) ? trim($_POST['content']['wpmf_media_order_field']) : WP_MYFAVOURITES_MEDIA_ORDER_BY_FIELD;
        $posted_data['wpmf_media_listinig_order'] = !empty($_POST['content']['wpmf_media_listinig_order']) ? trim($_POST['content']['wpmf_media_listinig_order']) : WP_MYFAVOURITES_MEDIA_ORDER;

        update_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_SETTINGS, $posted_data);
    }

    public static function wp_myfavourites_get_settings($type = "")
    {
        if (in_array($type, array('posts', 'comments', 'media'))) {
            $settings = get_option("wpmf_" . $type . "_settings");
            return $settings;
        }
    }

}
