<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Wp_MyFavourites
 *
 * @wordpress-plugin
 * Plugin Name:       WP My Favourites
 * Plugin URI:        http://daffodilsw.com/
 * Description:       Choose your favourite posts, pages, comments, media and reorder them to display anywhere on your website.
 * Version:           1.1.0
 * Author:            Neelkanth Kaushik
 * Author URI:        https://profiles.wordpress.org/myselfneelkanth
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-myfavourites
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_wp_myfavourites()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-myfavourites-activator.php';
    Wp_MyFavourites_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_wp_myfavourites()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-myfavourites-deactivator.php';
    Wp_MyFavourites_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_myfavourites');
register_deactivation_hook(__FILE__, 'deactivate_wp_myfavourites');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-myfavourites.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_myfavourites()
{

    $plugin = new Wp_MyFavourites();
    $plugin->run();
}

run_wp_myfavourites();

/**
 * WP My Favourites Default Configuration
 */
        const WP_MYFAVOURITES_POST_SELECT_LIMIT = 5;
        const WP_MYFAVOURITES_POSTS_AFTER_DATE = '-1 year';
        const WP_MYFAVOURITES_POSTS_BEFORE_DATE = "today";
        const WP_MYFAVOURITES_POSTS_INCLUSIVE_OF_DATES = true;
        const WP_MYFAVOURITES_POSTS_ORDER_BY_FIELD = 'post_date';
        const WP_MYFAVOURITES_POSTS_ORDER = 'DESC';
        const WP_MYFAVOURITES_POSTS_SUPPRESS_FILTER = true;
        const WP_MYFAVOURITES_IGNORE_STICKY_POSTS = true;
        const WP_MYFAVOURITES_POST_TYPE = array('post', 'page');
        const WP_MYFAVOURITES_POST_STATUS = array('publish');
//Note: If you rename the option name then you have to re-activate the plugin and you will lose your favourite items
        const WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME = 'wp_myfavourites_favourite_posts';
        const WP_MYFAVOURITES_FAVOURITE_POSTS_SETTINGS = 'wpmf_posts_settings';

        const WP_MYFAVOURITES_COMMENT_SELECT_LIMIT = 5;
        const WP_MYFAVOURITES_COMMENTS_AFTER_DATE = '-1 year';
        const WP_MYFAVOURITES_COMMENTS_BEFORE_DATE = 'today';
        const WP_MYFAVOURITES_COMMENTS_INCLUSIVE_OF_DATES = true;
        const WP_MYFAVOURITES_COMMENTS_ORDER_BY_FIELD = 'comment_date';
        const WP_MYFAVOURITES_COMMENTS_ORDER = 'DESC';
        const WP_MYFAVOURITES_COMMENTS_POST_TYPE = array('post');
        const WP_MYFAVOURITES_COMMENTS_POST_STATUS = array('publish');
        const WP_MYFAVOURITES_COMMENTS_STATUS = array('approve');
//Note: If you rename the option name then you have to re-activate the plugin and you will lose your favourite items
        const WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME = 'wp_myfavourites_favourite_comments';
        const WP_MYFAVOURITES_FAVOURITE_COMMENTS_SETTINGS = 'wpmf_comments_settings';

        const WP_MYFAVOURITES_MEDIA_SELECT_LIMIT = 10;
        const WP_MYFAVOURITES_MEDIA_AFTER_DATE = '-1 year';
        const WP_MYFAVOURITES_MEDIA_BEFORE_DATE = '+1 day';
        const WP_MYFAVOURITES_MEDIA_INCLUSIVE_OF_DATES = true;
        const WP_MYFAVOURITES_MEDIA_ORDER_BY_FIELD = 'post_date';
        const WP_MYFAVOURITES_MEDIA_ORDER = 'DESC';
        const WP_MYFAVOURITES_MEDIA_SUPPRESS_FILTER = true;
        const WP_MYFAVOURITES_MEDIA_STATUS = array('inherit');
//Note: If you rename the option name then you have to re-activate the plugin and you will lose your favourite items
        const WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME = 'wp_myfavourites_favourite_media';
        const WP_MYFAVOURITES_FAVOURITE_MEDIA_SETTINGS = 'wpmf_media_settings';
//Error codes
        const NO_ERR = 0;
        const ERR_NOT_ABLE_TO_MARK_FAVOURITE_POST = 1;
        const ERR_NOT_ABLE_TO_REMOVE_FAVOURITE_POST = 2;
        const ERR_NOT_ABLE_TO_MARK_FAVOURITE_COMMENT = 3;
        const ERR_NOT_ABLE_TO_REMOVE_FAVOURITE_COMMENT = 4;
        const ERR_NOT_ABLE_TO_MARK_FAVOURITE_MEDIA = 5;
        const ERR_NOT_ABLE_TO_REMOVE_FAVOURITE_MEDIA = 6;
