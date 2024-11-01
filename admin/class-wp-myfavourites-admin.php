<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_MyFavourites
 * @subpackage Wp_MyFavourites/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_MyFavourites
 * @subpackage Wp_MyFavourites/admin
 * @author     Neelkanth Kaushik <neelkanth.kaushik@daffodilsw.com>
 */
class Wp_MyFavourites_Admin
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
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
        wp_enqueue_style($this->plugin_name . '-admin-stylesheet', plugin_dir_url(__FILE__) . 'css/wp-myfavourites-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-admin-datatable-stylesheet', plugin_dir_url(__FILE__) . 'css/dataTables.bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-admin-datatable-row-reorder', plugin_dir_url(__FILE__) . 'css/rowReorder.dataTables.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-admin-jquery-ui-stylesheet', plugin_dir_url(__FILE__) . 'css/jqueryui/jquery-ui.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-admin-select-2-stylesheet', plugin_dir_url(__FILE__) . 'css/select2.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
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
        wp_enqueue_script($this->plugin_name . '-admin-script', plugin_dir_url(__FILE__) . 'js/wp-myfavourites-admin.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-admin-datatable-script', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery', $this->plugin_name . '-admin-script'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-admin-datatable-row-reorder', plugin_dir_url(__FILE__) . 'js/dataTables.rowReorder.min.js', array('jquery', $this->plugin_name . '-admin-script'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-admin-jquery-ui-script', plugin_dir_url(__FILE__) . 'js/jquery-ui.min.js', array('jquery', $this->plugin_name . '-admin-script'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-admin-select-2-script', plugin_dir_url(__FILE__) . 'js/select2.min.js', array('jquery', $this->plugin_name . '-admin-script'), $this->version, true);
        // localizing global js objects
        wp_localize_script($this->plugin_name . '-admin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    /*
     * Adds the plugin settings menu item to dashboard
     * @since    1.0.0
     */

    public function wp_myfavourites_plugin_settings()
    {

        $method_prefix = 'wp_myfavourites_';
        $menu_slug_prefix = 'wp-myfavourites-';

        $settings_page = add_menu_page('WP My Favourites', 'WP My Favourites', 'administrator', $menu_slug_prefix . 'favourite-posts', array($this, $method_prefix . 'favourite_posts'), 'dashicons-thumbs-up');

        add_submenu_page($menu_slug_prefix . 'favourite-posts', 'Favourite posts', 'Favourite posts', 'administrator', $menu_slug_prefix . 'favourite-posts', array($this, $method_prefix . 'favourite_posts'));
        add_submenu_page($menu_slug_prefix . 'favourite-posts', 'Favourite comments', 'Favourite comments', 'administrator', $menu_slug_prefix . 'favourite-comments', array($this, $method_prefix . 'favourite_comments'));
        add_submenu_page($menu_slug_prefix . 'favourite-posts', 'Favourite media', 'Favourite media', 'administrator', $menu_slug_prefix . 'favourite-media', array($this, $method_prefix . 'favourite_media'));
        add_submenu_page($menu_slug_prefix . 'favourite-posts', 'Settings', 'Settings', 'administrator', $menu_slug_prefix . 'settings', array($this, $method_prefix . 'settings'));

        //add_action("load-{$settings_page}", array($this, 'wp_myfavourites_load_settings_page'));
    }

    public function wp_myfavourites_favourite_posts()
    {
        global $pagenow;
        if ($pagenow == 'admin.php' && $_GET['page'] == 'wp-myfavourites-favourite-posts') {
            include 'partials/settings/wp-myfavourites-favourite-posts-settings.php';
        }
    }

    public function wp_myfavourites_favourite_comments()
    {
        global $pagenow;
        if ($pagenow == 'admin.php' && $_GET['page'] == 'wp-myfavourites-favourite-comments') {
            include 'partials/settings/wp-myfavourites-favourite-comments-settings.php';
        }
    }

    public function wp_myfavourites_favourite_media()
    {
        global $pagenow;
        if ($pagenow == 'admin.php' && $_GET['page'] == 'wp-myfavourites-favourite-media') {
            include 'partials/settings/wp-myfavourites-favourite-media-settings.php';
        }
    }

    public function wp_myfavourites_settings()
    {
        global $pagenow;
        if ($pagenow == 'admin.php' && $_GET['page'] == 'wp-myfavourites-settings') {
            include 'partials/settings/wp-myfavourites-plugin-settings.php';
        }
    }

    /**
     * Callback function called upon change in post status
     */
    function wp_mf_on_post_status_transitions($new_status, $old_status, $post)
    {
        $allowed_status = array('publish', 'inherit');
        $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME);
        if (!in_array($new_status, $allowed_status) && in_array($post->ID, $favourites)) {
            //Remove the post from favourites also
            $favourites = array_diff($favourites, [$post->ID]);
            //Update the option
            update_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME, $favourites);
        }
    }

    /**
     * Callback function called upon change in comment status
     */
    function wp_mf_on_comment_status_transitions($new_status, $old_status, $comment)
    {
        $allowed_status = array('approved');
        $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME);
        if (!in_array($new_status, $allowed_status) && in_array($comment->comment_ID, $favourites)) {
            //Remove the comment from favourites also
            $favourites = array_diff($favourites, [$comment->comment_ID]);
            //Update the option
            update_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME, $favourites);
        }
    }

    /**
     * Callback function called upon media delete
     */
    function wp_mf_on_media_delete($media_id)
    {
        $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME);
        if (in_array($media_id, $favourites)) {
            //Remove the media from favourites also
            $favourites = array_diff($favourites, [$media_id]);
            //Update the option
            update_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME, $favourites);
        }
    }

}
