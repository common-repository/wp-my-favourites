<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */
?>
<div class="wrap">
    <h2><u>WP My Favourites - Favourite Posts</u></h2>
    <br>
    <h3>Select any <?php $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("posts");
echo $settings['wpmf_posts_max'];
?> posts<span data-toggle="tooltip" title="Put cursor on the title to read full title. Click on thumbs up to add it to your favourites. Blue thumbs up are already marked as favourite." class="dashicons dashicons-editor-help"></span></h3>
    <table id="wp-myfavourites-post-list-datatable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Actions</th>                
                <th>Title</th>
                <th>Type</th>
                <th>Categories</th>
                <th>Author</th>
                <th>Date/Time</th>
            </tr>
        </thead>
    </table>
    <h2>Selected favourite posts<span data-toggle="tooltip" title="Put cursor on the title to read full title. Move cursor over the first cell and drag it to change its order. Click on the thumbs down to remove it from your favourites." class="dashicons dashicons-editor-help"></span></h2>
    <table id="wp-myfavourites-favourite-posts-list-datatable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Actions</th>                
                <th>Title</th>
                <th>Type</th>
                <th>Categories</th>
                <th>Author</th>
                <th>Date/Time</th>
            </tr>
        </thead>
    </table>

</div>