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
    <h2><u>WP My Favourites - Favourite Media</u></h2>
    <br>
    <h3>Select any <?php echo WP_MYFAVOURITES_MEDIA_SELECT_LIMIT; ?> media<span data-toggle="tooltip" title="Put cursor on the image to read full title. Click on Thumbs up icon to add the media to your favourites. Blue thumbs up are already marked as favourite." class="dashicons dashicons-editor-help"></span></h3>
    <table id="wp-myfavourites-media-list-datatable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Actions</th>
                <th>Media</th>
                <th>Author</th>
                <th>Date/Time</th>
            </tr>
        </thead>
    </table>
    <h2>Selected favourite media<span data-toggle="tooltip" title="Put cursor on the image to read full title. Move cursor over the first cell and drag it to change its order. Click on the Thumbs down to remove it from your favourites." class="dashicons dashicons-editor-help"></span></h2>
    <table id="wp-myfavourites-favourite-media-list-datatable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Actions</th>
                <th>Media</th>
                <th>Author</th>
                <th>Date/Time</th>
            </tr>
        </thead>
    </table>
</div>