<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_MyFavourites
 * @subpackage Wp_MyFavourites/admin/partials
 */
?>
<div class="wrap">
    <h2><u>WP My Favourites - Favourite Comments</u></h2>
    <br>
    <h3>Select any <?php
        $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("comments");
        echo $settings['wpmf_comments_max'];
        ?> comments<span data-toggle="tooltip" title="Put cursor on the title to read full title. Click on Thumbs up to add the comment to your favourites. Blue thumbs up are already marked as favourite." class="dashicons dashicons-editor-help"></span></h3>
    <table id="wp-myfavourites-comment-list-datatable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Comment ID</th>
                <th>Actions</th>
                <th>Comment</th>
                <th>Commented by</th>
                <th>Date/Time</th>
                <th>Post Title</th>
            </tr>
        </thead>
    </table>
    <h2>Selected favourite comments<span data-toggle="tooltip" title="Put cursor on the title to read full title. Move cursor over the first cell and drag it to change its order. Click on the thumbs down to remove it from your favourites." class="dashicons dashicons-editor-help"></span></h2>
    <table id="wp-myfavourites-favourite-comments-list-datatable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Comment ID</th>
                <th>Actions</th>
                <th>Comment</th>
                <th>Commented by</th>
                <th>Date/Time</th>
                <th>Post Title</th>
            </tr>
        </thead>
    </table>
</div>