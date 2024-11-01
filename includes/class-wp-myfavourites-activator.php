<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_MyFavourites_
 * @subpackage Wp_MyFavourites_/includes
 * @author     Neelkanth <email@example.com>
 */
class Wp_MyFavourites_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $posts = get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME);
        if (is_array($posts) && count($posts) > 0) {
            foreach ($posts as $post) {
                $post_status = get_post_status($post);
                if ($post_status != 'publish' || $post_status != 'inherit') {
                    //Post is not published
                    $favourites = array_diff($posts, [$post]);
                    update_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME, $favourites);
                }
            }
        }

        $comments = get_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME);
        if (is_array($comments) && count($comments) > 0) {
            foreach ($comments as $comment) {
                $comment_status = wp_get_comment_status($comment);
                if ($comment_status != 'approved') {
                    //Comment is not approved
                    $favourites = array_diff($comments, [$comment]);
                    update_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME, $favourites);
                }
            }
        }

        $medias = get_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME);
        if (is_array($medias) && count($medias) > 0) {
            foreach ($medias as $media) {
                $media_status = get_post_status($media);
                if ($media_status == false) {
                    //Media has been deleted
                    $favourites = array_diff($medias, [$media]);
                    update_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME, $favourites);
                }
            }
        }

        //Initialize Settings
        if (!get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_SETTINGS)) {
            $settings_obj = new Wp_MyFavourites_Settings();
            $settings_obj->wp_myfavourites_save_posts_settings();
        }
        if (!get_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_SETTINGS)) {
            $settings_obj = new Wp_MyFavourites_Settings();
            $settings_obj->wp_myfavourites_save_comments_settings();
        }
        if (!get_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_SETTINGS)) {
            $settings_obj = new Wp_MyFavourites_Settings();
            $settings_obj->wp_myfavourites_save_media_settings();
        }
    }

}
