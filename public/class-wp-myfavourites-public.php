<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_MyFavourites
 * @subpackage Wp_MyFavourites/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_MyFavourites
 * @subpackage Wp_MyFavourites/public
 * @author     Your Name <email@example.com>
 */
class Wp_MyFavourites_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-myfavourites-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-myfavourites-public.js', array('jquery'), $this->version, false);
    }

    /**
     * Return array of selected favourite ids
     * @param string $type
     * @param int $limit
     * @return array
     */
    public static function wp_mf_get_favourite_ids($type = 'posts', $limit = 0)
    {
        $fav_ids = array();
        if ($type == 'posts') {
            $fav_ids = get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME);
        }
        if ($type == 'comments') {
            $fav_ids = get_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME);
        }
        if ($type == 'media') {
            $fav_ids = get_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME);
        }
        return count($fav_ids > 0) && $limit > 0 ? array_slice($fav_ids, 0, $limit) : $fav_ids;
    }

    /**
     * Return the favourite posts
     * @param array $post_types
     * @return WP_Post Object
     */
    public static function wp_mf_favourite_posts($post_types = array('post'), $no_of_posts = -1)
    {
        $ids = Wp_MyFavourites_Public::wp_mf_get_favourite_ids('posts');
        $fav_posts = get_posts(array(
            'numberposts' => $no_of_posts,
            'post__in' => $ids,
            'post_type' => $post_types,
            'orderby' => 'post__in'
        ));
        return $fav_posts;
    }

    /**
     * Return the favourite comments
     * @return WP_Comment Object
     */
    public static function wp_mf_favourite_comments($no_of_comments = null)
    {
        $ids = Wp_MyFavourites_Public::wp_mf_get_favourite_ids('comments');
        $fav_comments = get_comments(array(
            'number' => $no_of_comments,
            'comment__in' => $ids,
            'orderby' => 'comment__in',
        ));
        return $fav_comments;
    }

    /**
     * Return the favourite media
     * @param array $post_types
     * @return WP_Post Object
     */
    public static function wp_mf_favourite_media($no_of_media = -1)
    {
        $ids = Wp_MyFavourites_Public::wp_mf_get_favourite_ids('media');
        $fav_posts = get_posts(array(
            'numberposts' => $no_of_media,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post__in' => $ids,
            'orderby' => 'post__in'
        ));
        return $fav_posts;
    }

    /**
     * Shortcode method to diplay listing of posts
     */
    public function wp_mf_display_favourite_posts($args = array())
    {
        $post_types = !empty($args['post_types']) ? explode(',', $args['post_types']) : 'post';
        $no_of_posts = !empty($args['no_of_posts']) ? $args['no_of_posts'] : -1;
        $character_limit = !empty($args['no_of_chars']) ? $args['no_of_chars'] : 20;
        $posts = Wp_MyFavourites_Public::wp_mf_favourite_posts($post_types, $no_of_posts);
        include 'partials/wp-myfavourites-posts-display.php';
    }

    /**
     * Shortcode method to display comments
     */
    public function wp_mf_display_favourite_comments($args = array())
    {
        $no_of_comments = !empty($args['no_of_comments']) ? $args['no_of_comments'] : null;
        $character_limit = !empty($args['no_of_chars']) ? $args['no_of_chars'] : 20;
        $comments = Wp_MyFavourites_Public::wp_mf_favourite_comments($no_of_comments);
        include 'partials/wp-myfavourites-comments-display.php';
    }

    /**
     * Shortcode method to diplay listing of media
     */
    public function wp_mf_display_favourite_media($args = array())
    {
        $no_of_media = !empty($args['no_of_media']) ? $args['no_of_media'] : -1;
        $image_size = !empty($args['image_size']) ? explode(',', $args['image_size']) : array(100, 100);
        $medias = Wp_MyFavourites_Public::wp_mf_favourite_media($no_of_media);
        include 'partials/wp-myfavourites-media-display.php';
    }

}
