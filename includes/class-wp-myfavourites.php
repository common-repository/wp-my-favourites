<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_MyFavourites_
 * @subpackage Wp_MyFavourites_/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_MyFavourites_
 * @subpackage Wp_MyFavourites_/includes
 * @author     Your Name <email@example.com>
 */
class Wp_MyFavourites
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->plugin_name = 'wp-myfavourites';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
     * - Plugin_Name_i18n. Defines internationalization functionality.
     * - Plugin_Name_Admin. Defines all hooks for the admin area.
     * - Plugin_Name_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-myfavourites-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-myfavourites-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wp-myfavourites-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wp-myfavourites-public.php';

        $this->loader = new Wp_MyFavourites_Loader();

        /**
         * This class contains ajax methods
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-myfavourites-ajax.php';

        /**
         * This class contains settings methods
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-myfavourites-settings.php';
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Wp_MyFavourites_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Wp_MyFavourites_Admin($this->get_wp_myfavourites(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'wp_myfavourites_plugin_settings');

        $this->loader->add_action('transition_post_status', $plugin_admin, 'wp_mf_on_post_status_transitions', 10, 3);
        $this->loader->add_action('transition_comment_status', $plugin_admin, 'wp_mf_on_comment_status_transitions', 10, 3);
        $this->loader->add_action('delete_attachment', $plugin_admin, 'wp_mf_on_media_delete', 10, 3);


        //Ajax hooks
        $plugin_admin_ajax = new Wp_MyFavourites_Ajax($this->get_wp_myfavourites(), $this->get_version());
        $this->loader->add_action('wp_ajax_get_posts', $plugin_admin_ajax, 'wp_myfavourites_get_posts');
        $this->loader->add_action('wp_ajax_mark_favourite_post', $plugin_admin_ajax, 'wp_myfavourites_mark_favourite');
        $this->loader->add_action('wp_ajax_get_favourite_posts', $plugin_admin_ajax, 'wp_myfavourites_get_favourite_posts');

        $this->loader->add_action('wp_ajax_get_comments', $plugin_admin_ajax, 'wp_myfavourites_get_comments');
        $this->loader->add_action('wp_ajax_mark_favourite_comment', $plugin_admin_ajax, 'wp_myfavourites_mark_favourite_comment');
        $this->loader->add_action('wp_ajax_get_favourite_comments', $plugin_admin_ajax, 'wp_myfavourites_get_favourite_comments');

        $this->loader->add_action('wp_ajax_get_media', $plugin_admin_ajax, 'wp_myfavourites_get_media');
        $this->loader->add_action('wp_ajax_mark_favourite_media', $plugin_admin_ajax, 'wp_myfavourites_mark_favourite_media');
        $this->loader->add_action('wp_ajax_get_favourite_media', $plugin_admin_ajax, 'wp_myfavourites_get_favourite_media');

        $this->loader->add_action('wp_ajax_reorder_favourites', $plugin_admin_ajax, 'wp_myfavourites_reorder_favourites');

        //Plugin settings
        $plugin_admin_settings = new Wp_MyFavourites_Settings($this->get_wp_myfavourites(), $this->get_version());
        $this->loader->add_action('wp_ajax_save_posts_settings', $plugin_admin_settings, 'wp_myfavourites_save_posts_settings');
        $this->loader->add_action('wp_ajax_save_comments_settings', $plugin_admin_settings, 'wp_myfavourites_save_comments_settings');
        $this->loader->add_action('wp_ajax_save_media_settings', $plugin_admin_settings, 'wp_myfavourites_save_media_settings');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Wp_MyFavourites_Public($this->get_wp_myfavourites(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        //Shortcodes
        $this->loader->add_shortcode('show-favourite-posts', $plugin_public, 'wp_mf_display_favourite_posts');
        $this->loader->add_shortcode('show-favourite-comments', $plugin_public, 'wp_mf_display_favourite_comments');
        $this->loader->add_shortcode('show-favourite-media', $plugin_public, 'wp_mf_display_favourite_media');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_wp_myfavourites()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
