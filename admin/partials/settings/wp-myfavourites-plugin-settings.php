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
    <h1><?php _e('WP My Favourites - Configuration', $this->plugin_name); ?></h1>
    <div id="poststuff" class="">

        <div id="post-body">
            <div id="post-body-content">
                <div class="tab">
                    <button class="tablinks active" onclick="openCity(event, 'Posts')"><?php _e("Posts", $this->plugin_name); ?></button>
                    <button class="tablinks" onclick="openCity(event, 'Comments')"><?php _e("Comments", $this->plugin_name); ?></button>
                    <button class="tablinks" onclick="openCity(event, 'Media')"><?php _e("Media", $this->plugin_name); ?></button>
                </div>
                <div id="Posts" class="tabcontent" style="display: block;">
                    <?php $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("posts");
                    ?>
                    <table class="form-table wpmf-posts-settings">
                        <tbody>
                            <tr>
                                <td><?php _e("Max no. posts that can be selected", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly id="wpmf-posts-max" type="text" value="<?php echo!empty($settings['wpmf_posts_max']) ? $settings['wpmf_posts_max'] : WP_MYFAVOURITES_POST_SELECT_LIMIT ?>"/>
                                    <div id="wpmf-posts-max-slider"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get posts from date", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly="" id="wpmf-posts-min-date" type="text" value="<?php echo!empty($settings['wpmf_posts_min_date']) ? date("d-m-Y", strtotime($settings['wpmf_posts_min_date'])) : "" ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get posts to date", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly="" id="wpmf-posts-max-date" type="text" value="<?php echo!empty($settings['wpmf_posts_min_date']) ? date("d-m-Y", strtotime($settings['wpmf_posts_max_date'])) : "" ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get posts inclusive of dates in range", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-posts-inclusive-dates" name="select">
                                        <option <?php echo $settings['wpmf_posts_inclusive_dates'] == "yes" ? "selected='selected'" : "" ?> value="yes"><?php _e("Yes", $this->plugin_name); ?></option>
                                        <option <?php echo $settings['wpmf_posts_inclusive_dates'] == "no" ? "selected='selected'" : "" ?> value="no"><?php _e("No", $this->plugin_name); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Ignore sticky posts", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-posts-ignore-sticky" name="select">
                                        <option <?php echo $settings['wpmf_posts_ignore_sticky'] == "yes" ? "selected='selected'" : "" ?> value="yes"><?php _e("Yes", $this->plugin_name); ?></option>
                                        <option <?php echo $settings['wpmf_posts_ignore_sticky'] == "no" ? "selected='selected'" : "" ?> value="no"><?php _e("No", $this->plugin_name); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get posts ordered by field", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-posts-order-field" name="select">
                                        <option <?php echo $settings['wpmf_posts_order_field'] == "post_date" ? "selected='selected'" : "" ?> value="post_date"><?php _e("Post date", $this->plugin_name); ?></option>
                                        <option <?php echo $settings['wpmf_posts_order_field'] == "post_title" ? "selected='selected'" : "" ?> value="post_title"><?php _e("Post title", $this->plugin_name); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Posts order", $this->plugin_name); ?></td>
                                <td>
                                    <input id="wpmf-posts-order-asc" <?php echo $settings['wpmf_posts_listinig_order'] == "ASC" ? "checked" : "" ?> type="radio" name="wpmf-posts-listing-order" value="ASC" /> <?php _e("Ascending", $this->plugin_name); ?>
                                    <input id="wpmf-posts-order-desc" <?php echo $settings['wpmf_posts_listinig_order'] == "DESC" ? "checked" : "" ?> type="radio" name="wpmf-posts-listing-order" value="DESC" /> <?php _e("Descending", $this->plugin_name); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Post types", $this->plugin_name); ?></td>
                                <td>

                                    <select id="wpmf-post-types" name="select" multiple="multiple">
                                        <option <?php echo in_array("post", $settings['wpmf_posts_post_types']) ? "selected='selected'" : "" ?> value="post"><?php _e("Post", $this->plugin_name); ?></option>
                                        <option <?php echo in_array("page", $settings['wpmf_posts_post_types']) ? "selected='selected'" : "" ?> value="page"><?php _e("Page", $this->plugin_name); ?></option>
                                        <?php
                                        $post_types = get_post_types(
                                                array(
                                                    'public' => true,
                                                    '_builtin' => false
                                                )
                                        );
                                        if (isset($post_types) && !empty($post_types)) {
                                            foreach ($post_types as $post_key => $post_val) {
                                                ?>
                                                <option <?php echo in_array($post_key, $settings['wpmf_posts_post_types']) ? "selected='selected'" : "" ?> value="<?php echo $post_key; ?>"><?php echo ucfirst($post_val); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Post status", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-posts-status" name="select" multiple="multiple">
                                        <?php
                                        $post_statuses = get_post_statuses();
                                        if (isset($post_statuses) && !empty($post_statuses)) {
                                            foreach ($post_statuses as $status_key => $status_val) {
                                                ?>
                                                <option <?php echo in_array($status_key, $settings['wpmf_posts_post_statuses']) ? "selected='selected'" : "" ?> value="<?php echo $status_key; ?>"><?php echo ucfirst($status_val); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" id="wpmf-posts-save-settings" class="button-primary"><?php _e("Save settings", $this->plugin_name); ?></button>
                                    <span id="posts_settings_save_spinner"><img src="<?php echo get_admin_url() . 'images/spinner.gif'; ?>"/></span>
                                    <span id="posts_settings_save_success_icon" class="dashicons dashicons-yes"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="Comments" class="tabcontent">
                    <?php $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("comments");
                    ?>
                    <table class="form-table wpmf-comments-settings">
                        <tbody>
                            <tr>
                                <td><?php _e("Max no. comments that can be selected", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly id="wpmf-comments-max" type="text" value="<?php echo!empty($settings['wpmf_comments_max']) ? $settings['wpmf_comments_max'] : WP_MYFAVOURITES_COMMENT_SELECT_LIMIT ?>"/>
                                    <div id="wpmf-comments-max-slider"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get comments from date", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly="" id="wpmf-comments-min-date" type="text" value="<?php echo!empty($settings['wpmf_comments_min_date']) ? date("d-m-Y", strtotime($settings['wpmf_comments_min_date'])) : "" ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get comments to date", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly="" id="wpmf-comments-max-date" type="text" value="<?php echo!empty($settings['wpmf_comments_min_date']) ? date("d-m-Y", strtotime($settings['wpmf_comments_max_date'])) : "" ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get comments inclusive of dates in range", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-comments-inclusive-dates" name="select">
                                        <option <?php echo $settings['wpmf_comments_inclusive_dates'] == "yes" ? "selected='selected'" : "" ?> value="yes"><?php _e("Yes", $this->plugin_name); ?></option>
                                        <option <?php echo $settings['wpmf_comments_inclusive_dates'] == "no" ? "selected='selected'" : "" ?> value="no"><?php _e("No", $this->plugin_name); ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td><?php _e("Get comments ordered by field", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-comments-order-field" name="select">
                                        <option <?php echo $settings['wpmf_comments_order_field'] == "post_date" ? "selected='selected'" : "" ?> value="comment_date"><?php _e("Comment date", $this->plugin_name); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Comments order", $this->plugin_name); ?></td>
                                <td>
                                    <input id="wpmf-comments-order-asc" <?php echo $settings['wpmf_comments_listinig_order'] == "ASC" ? "checked" : "" ?> type="radio" name="wpmf-comments-listing-order" value="ASC" /> <?php _e("Ascending", $this->plugin_name); ?>
                                    <input id="wpmf-comments-order-desc" <?php echo $settings['wpmf_comments_listinig_order'] == "DESC" ? "checked" : "" ?> type="radio" name="wpmf-comments-listing-order" value="DESC" /> <?php _e("Descending", $this->plugin_name); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Comments Post types", $this->plugin_name); ?></td>
                                <td>

                                    <select id="wpmf-comments-post-types" name="select" multiple="multiple">
                                        <option <?php echo in_array("post", $settings['wpmf_comments_post_types']) ? "selected='selected'" : "" ?> value="post"><?php _e("Post", $this->plugin_name); ?></option>
                                        <option <?php echo in_array("page", $settings['wpmf_comments_post_types']) ? "selected='selected'" : "" ?> value="page"><?php _e("Page", $this->plugin_name); ?></option>
                                        <?php
                                        $post_types = get_post_types(
                                                array(
                                                    'public' => true,
                                                    '_builtin' => false
                                                )
                                        );
                                        if (isset($post_types) && !empty($post_types)) {
                                            foreach ($post_types as $post_key => $post_val) {
                                                ?>
                                                <option <?php echo in_array($post_key, $settings['wpmf_comments_post_types']) ? "selected='selected'" : "" ?> value="<?php echo $post_key; ?>"><?php echo ucfirst($post_val); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Comment's Post's status", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-comments-post-status" name="select" multiple="multiple">
                                        <?php
                                        $post_statuses = get_post_statuses();
                                        if (isset($post_statuses) && !empty($post_statuses)) {
                                            foreach ($post_statuses as $status_key => $status_val) {
                                                ?>
                                                <option <?php echo in_array($status_key, $settings['wpmf_comments_post_statuses']) ? "selected='selected'" : "" ?> value="<?php echo $status_key; ?>"><?php echo ucfirst($status_val); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Comment status", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-comments-status" name="select" multiple="multiple">
                                        <?php
                                        $comment_statuses = get_comment_statuses();
                                        if (isset($comment_statuses) && !empty($comment_statuses)) {
                                            foreach ($comment_statuses as $status_key => $status_val) {
                                                ?>
                                                <option <?php echo in_array($status_key, $settings['wpmf_comments_statuses']) ? "selected='selected'" : "" ?> value="<?php echo $status_key; ?>"><?php echo ucfirst($status_val); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" id="wpmf-comments-save-settings" class="button-primary"><?php _e("Save settings", $this->plugin_name); ?></button>
                                    <span id="comments_settings_save_spinner"><img src="<?php echo get_admin_url() . 'images/spinner.gif'; ?>"/></span>
                                    <span id="comments_settings_save_success_icon" class="dashicons dashicons-yes"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="Media" class="tabcontent">
                    <?php $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("media");
                    ?>
                    <table class="form-table wpmf-media-settings">
                        <tbody>
                            <tr>
                                <td><?php _e("Max no. media items that can be selected", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly id="wpmf-media-max" type="text" value="<?php echo!empty($settings['wpmf_media_max']) ? $settings['wpmf_media_max'] : WP_MYFAVOURITES_MEDIA_SELECT_LIMIT ?>"/>
                                    <div id="wpmf-media-max-slider"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get media from date", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly="" id="wpmf-media-min-date" type="text" value="<?php echo!empty($settings['wpmf_media_min_date']) ? date("d-m-Y", strtotime($settings['wpmf_media_min_date'])) : "" ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get media to date", $this->plugin_name); ?></td>
                                <td>
                                    <input readonly="" id="wpmf-media-max-date" type="text" value="<?php echo!empty($settings['wpmf_media_min_date']) ? date("d-m-Y", strtotime($settings['wpmf_media_max_date'])) : "" ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Get media ordered by field", $this->plugin_name); ?></td>
                                <td>
                                    <select id="wpmf-media-order-field" name="select">
                                        <option <?php echo $settings['wpmf_media_order_field'] == "post_date" ? "selected='selected'" : "" ?> value="post_date"><?php _e("Post date", $this->plugin_name); ?></option>
                                        <option <?php echo $settings['wpmf_media_order_field'] == "post_title" ? "selected='selected'" : "" ?> value="post_title"><?php _e("Post title", $this->plugin_name); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e("Media order", $this->plugin_name); ?></td>
                                <td>
                                    <input id="wpmf-media-order-asc" <?php echo $settings['wpmf_media_listinig_order'] == "ASC" ? "checked" : "" ?> type="radio" name="wpmf-media-listing-order" value="ASC" /> <?php _e("Ascending", $this->plugin_name); ?>
                                    <input id="wpmf-media-order-desc" <?php echo $settings['wpmf_media_listinig_order'] == "DESC" ? "checked" : "" ?> type="radio" name="wpmf-media-listing-order" value="DESC" /> <?php _e("Descending", $this->plugin_name); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" id="wpmf-media-save-settings" class="button-primary"><?php _e("Save settings", $this->plugin_name); ?></button>
                                    <span id="media_settings_save_spinner"><img src="<?php echo get_admin_url() . 'images/spinner.gif'; ?>"/></span>
                                    <span id="media_settings_save_success_icon" class="dashicons dashicons-yes"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>