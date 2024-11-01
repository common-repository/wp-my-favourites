<?php

/**
 * Ajax methods for Plugin
 *
 * @since      1.0.0
 * @package    Wp_MyFavourites
 * @subpackage Wp_MyFavourites/includes
 * @author     Your Name <email@example.com>
 */
class Wp_MyFavourites_Ajax
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

    private function trim_title($title = '', $limit = 19)
    {
        return strlen($title) > $limit ? substr($title, 0, $limit + 1) . '...' : $title;
    }

    public function wp_myfavourites_get_posts()
    {
        $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("posts");

        //Get $_POST parameters
        $params = [
            'draw' => $_POST['draw'],
            'start' => $_POST['start'],
            'length' => $_POST['length'],
            'search_val' => $_POST['search']['value']
        ];

        $count_query = new WP_Query(array(
            'date_query' => array(
                array(
                    'after' => $settings['wpmf_posts_min_date'],
                    'before' => $settings['wpmf_posts_max_date'],
                    'inclusive' => $settings['wpmf_posts_inclusive_dates'] == "yes" ? true : false,
                ),
            ),
            'ignore_sticky_posts' => $settings['wpmf_posts_ignore_sticky'] == "yes" ? true : false,
            'orderby' => $settings['wpmf_posts_order_field'],
            'order' => $settings['wpmf_posts_listinig_order'],
            'post_status' => $settings['wpmf_posts_post_statuses'],
            'post_type' => $settings['wpmf_posts_post_types'],
            'suppress_filters' => WP_MYFAVOURITES_POSTS_SUPPRESS_FILTER,
            's' => $params['search_val'],
                )
        );

        //Count total number of posts for DT pagination
        $total_posts = $count_query->found_posts;
        wp_reset_postdata();

        //Get paginated results
        $list_query = new WP_Query(array(
            'posts_per_page' => $params['length'],
            'offset' => $params['start'],
            'date_query' => array(
                array(
                    'after' => $settings['wpmf_posts_min_date'],
                    'before' => $settings['wpmf_posts_max_date'],
                    'inclusive' => $settings['wpmf_posts_inclusive_dates'] == "yes" ? true : false,
                ),
            ),
            'ignore_sticky_posts' => $settings['wpmf_posts_ignore_sticky'] == "yes" ? true : false,
            'orderby' => $settings['wpmf_posts_order_field'],
            'order' => $settings['wpmf_posts_listinig_order'],
            'post_status' => $settings['wpmf_posts_post_statuses'],
            'post_type' => $settings['wpmf_posts_post_types'],
            'suppress_filters' => WP_MYFAVOURITES_POSTS_SUPPRESS_FILTER,
            's' => $params['search_val'],
                )
        );

        $posts = $list_query->get_posts();
        wp_reset_postdata();

        $posts_array = array();

        if (isset($posts) && !empty($posts)) {
            //Get the already marked post ids to set the heart color
            $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME);

            foreach ($posts as $post) {
                //Prepare HTML for actions
                $space = "&nbsp;&nbsp;&nbsp;&nbsp;";
                $edit_action = "$space<a href='" . get_edit_post_link($post->ID) . "'><span class='dashicons dashicons-edit'></span></a>$space";
                $view_action = "<a href='" . get_permalink($post->ID) . "'><span class='dashicons dashicons-visibility'></span></a>$space";

                $heart_class = '';
                if (in_array($post->ID, $favourites)) {
                    $heart_class = 'already-favourite';
                }
                $mark_favourite_action = "<span id='$post->ID' class='$heart_class dashicons dashicons-thumbs-up'></span>";

                $data = [
                    'action' => $edit_action . $view_action . $mark_favourite_action,
                    'id' => $post->ID,
                    'title' => "<strong data-toggle='tooltip' title='$post->post_title'>" . $this->trim_title($post->post_title, 20) . "</strong>",
                    'type' => !empty($post->post_type) ? ucfirst($post->post_type) : '',
                    'categories' => get_the_category($post->ID),
                    'author' => get_author_name($post->post_author),
                    'dated' => $post->post_date,
                    'views' => get_post_meta($post->ID, '_count-views_all', true),
                ];
                array_push($posts_array, $data);
            }
        }

        $data = [
            'draw' => $params['draw'],
            "recordsTotal" => $total_posts,
            "recordsFiltered" => $total_posts,
            'data' => $posts_array
        ];
        wp_send_json($data);
    }

    public function wp_myfavourites_mark_favourite()
    {
        $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("posts");
        //Get the post id
        $postid = $_POST['content']['post_id'];
        //Get the operation
        $operation = !empty($_POST['content']['operation']) ? $_POST['content']['operation'] : '';
        //Set option name
        $option = WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME;
        //Create option if not exists
        if (!get_option($option)) {
            add_option($option);
        }

        //Get the option values
        $favourites = get_option($option);

        switch ($operation) {
            case "add":
                if ($favourites == "" || count($favourites) < $settings['wpmf_posts_max'] && !in_array($postid, $favourites)) {
                    if (!is_array($favourites)) {
                        //If there are no value in the option then initialize it
                        $favourites = array($postid);
                        $status = update_option($option, $favourites);
                        $error_code = NO_ERR;
                    } else {
                        //append the new value to option and update it
                        array_push($favourites, $postid);
                        $status = update_option($option, $favourites);
                        $error_code = NO_ERR;
                    }
                } else {
                    $status = count($favourites);
                    $error_code = ERR_NOT_ABLE_TO_MARK_FAVOURITE_POST;
                }
                break;

            case "remove" :
                if (is_array($favourites) && in_array($postid, $favourites)) {
                    //Remove the id and update option

                    $key = array_search($postid, $favourites);

                    if ($key >= 0) {
                        unset($favourites[$key]);
                    }

                    $status = update_option($option, $favourites);
                    $error_code = NO_ERR;
                } else {
                    $status = -1;
                    $error_code = ERR_NOT_ABLE_TO_REMOVE_FAVOURITE_POST;
                }
                break;
            default:
        }
        $response = array(
            'status' => $status, 'error' => $error_code
        );
        wp_send_json($response);
    }

    public function wp_myfavourites_get_favourite_posts()
    {
        //Get $_POST parameters
        $params = [
            'draw' => $_POST['draw'],
            'start' => $_POST['start'],
            'length' => $_POST['length'],
            'search_val' => $_POST['search']['value']
        ];
        $ids = get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME);

        if ($ids != false && count($ids) > 0) {
            $count_query = new WP_Query(array(
                'post__in' => $ids,
                's' => $params['search_val'],
                    )
            );
            //Count total number of posts for DT pagination
            $total_posts = $count_query->found_posts;
            wp_reset_postdata();
            //Get paginated results
            $list_query = new WP_Query(array(
                'posts_per_page' => $params['length'],
                'offset' => $params['start'],
                'post__in' => $ids,
                'orderby' => 'post__in',
                's' => $params['search_val'],
                    )
            );

            $posts = $list_query->get_posts();
            wp_reset_postdata();
            $posts_array = array();

            if (isset($posts) && !empty($posts)) {
                //Get the already marked post ids to set the heart color
                $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME);

                foreach ($posts as $post) {

                    //Prepare HTML for actions
                    $space = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $edit_action = "$space<a href='" . get_edit_post_link($post->ID) . "'><span class='dashicons dashicons-edit'></span></a>$space";
                    $view_action = "<a href='" . get_permalink($post->ID) . "'><span class='dashicons dashicons-visibility'></span></a>$space";

                    $heart_class = '';
                    if (in_array($post->ID, $favourites)) {
                        $heart_class = 'already-favourite';
                    }
                    $mark_favourite_action = "<span id='$post->ID' class='$heart_class dashicons dashicons-thumbs-down'></span>";

                    $data = [
                        'action' => $edit_action . $view_action . $mark_favourite_action,
                        'id' => "<span class='favourite-post-id' id='$post->ID'>$post->ID</span>",
                        'title' => "<strong data-toggle='tooltip' title='$post->post_title'>" . $this->trim_title($post->post_title, 20) . "</strong>",
                        'type' => !empty($post->post_type) ? ucfirst($post->post_type) : '',
                        'categories' => get_the_category($post->ID),
                        'author' => get_author_name($post->post_author),
                        'dated' => $post->post_date,
                        'views' => get_post_meta($post->ID, '_count-views_all', true),
                    ];
                    array_push($posts_array, $data);
                }
            }
            //Prepare data for datatable
            $data = [
                'draw' => $params['draw'],
                "recordsTotal" => $total_posts,
                "recordsFiltered" => $total_posts,
                'data' => $posts_array
            ];
        } else {
            $data = [
                'draw' => $params['draw'],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                'data' => array()
            ];
        }
        wp_send_json($data);
    }

    public function wp_myfavourites_get_comments()
    {
        $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("comments");
        //Get $_POST parameters
        $params = [
            'draw' => $_POST['draw'],
            'start' => $_POST['start'],
            'length' => $_POST['length'],
            'search_val' => $_POST['search']['value']
        ];

        $count_query = get_comments(array(
            'post_type' => $settings['wpmf_comments_post_types'],
            'post_status' => $settings['wpmf_comments_post_statuses'],
            'status' => $settings['wpmf_comments_statuses'],
            'date_query' => array(
                array(
                    'after' => $settings['wpmf_comments_min_date'],
                    'before' => $settings['wpmf_comments_max_date'],
                    'inclusive' => $settings['wpmf_comments_inclusive_dates'] == "yes" ? true : false,
                )
            ),
            'search' => $params['search_val'],
                )
        );

        //Count total number of posts for DT pagination
        $total_comments = count($count_query);

        wp_reset_query();

        //Get paginated results
        $comments = get_comments(array(
            'number' => $params['length'],
            'offset' => $params['start'],
            'post_type' => $settings['wpmf_comments_post_types'],
            'post_status' => $settings['wpmf_comments_post_statuses'],
            'status' => $settings['wpmf_comments_statuses'],
            'date_query' => array(
                array(
                    'after' => $settings['wpmf_comments_min_date'],
                    'before' => $settings['wpmf_comments_max_date'],
                    'inclusive' => $settings['wpmf_comments_inclusive_dates'] == "yes" ? true : false,
                ),
            ),
            'orderby' => $settings['wpmf_comments_order_field'],
            'order' => $settings['wpmf_comments_listinig_order'],
            'search' => $params['search_val'],
                )
        );

        wp_reset_query();
        //echo "<pre>";print_r($posts);wp_die();
        $comments_array = array();
        if (isset($comments) && !empty($comments)) {
            //Get the already marked post ids to set the heart color
            $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME);

            foreach ($comments as $comment) {
                //wp_send_json($comment);
                //Prepare HTML for actions
                $space = "&nbsp;&nbsp;&nbsp;&nbsp;";
                //$edit_action = "$space<a href='" . get_edit_post_link($post->ID) . "'><span class='dashicons dashicons-edit'></span></a>$space";
                //$view_action = "<a href='" . get_comment_link($comment->comment_ID) . "'><span class='dashicons dashicons-visibility'></span></a>$space";

                $heart_class = '';
                if (in_array($comment->comment_ID, $favourites)) {
                    $heart_class = 'already-favourite';
                }
                $mark_favourite_action = "<span id='$comment->comment_ID' class='$heart_class dashicons dashicons-thumbs-up'></span>";

                $data = [
                    'id' => $comment->comment_ID,
                    'action' => $view_action . $mark_favourite_action,
                    'comment' => "<strong data-toggle='tooltip' title='$comment->comment_content'>" . $this->trim_title($comment->comment_content, 20) . "</strong>",
                    'comment_by' => $comment->comment_author . "($comment->comment_author_email)",
                    'dated' => $comment->comment_date,
                    'post_title' => "<a href='" . get_the_permalink($comment->comment_post_ID) . "'>" . get_the_title($comment->comment_post_ID) . "</a>"
                ];
                array_push($comments_array, $data);
            }
        }
        //Prepare data for datatable
        $data = [
            'draw' => $params['draw'],
            "recordsTotal" => $total_comments,
            "recordsFiltered" => $total_comments,
            'data' => $comments_array
        ];
        wp_send_json($data);
    }

    public function wp_myfavourites_mark_favourite_comment()
    {
        $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("comments");
        //Get the post id
        $postid = $_POST['content']['comment_id'];
        //Get the operation
        $operation = !empty($_POST['content']['operation']) ? $_POST['content']['operation'] : '';
        //Set option name
        $option = WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME;
        //Create option if not exists
        if (!get_option($option)) {
            add_option($option);
        }

        //Get the option values
        $favourites = get_option($option);

        switch ($operation) {
            case "add":
                if ($favourites == "" || count($favourites) < $settings['wpmf_comments_max'] && !in_array($postid, $favourites)) {
                    if (!is_array($favourites)) {
                        //If there are no value in the option then initialize it
                        $favourites = array($postid);
                        $status = update_option($option, $favourites);
                        $error_code = NO_ERR;
                    } else {
                        //append the new value to option and update it
                        array_push($favourites, $postid);
                        $status = update_option($option, $favourites);
                        $error_code = NO_ERR;
                    }
                } else {
                    $status = count($favourites);
                    $error_code = ERR_NOT_ABLE_TO_MARK_FAVOURITE_COMMENT;
                }
                break;

            case "remove" :
                if (is_array($favourites) && in_array($postid, $favourites)) {
                    //Remove the id and update option

                    $key = array_search($postid, $favourites);

                    if ($key >= 0) {
                        unset($favourites[$key]);
                    }

                    $status = update_option($option, $favourites);
                } else {
                    $status = -1;
                    $error_code = ERR_NOT_ABLE_TO_REMOVE_FAVOURITE_COMMENT;
                }
                break;
            default:
        }
        $response = array(
            'status' => $status, 'error' => $error_code
        );
        wp_send_json($response);
    }

    public function wp_myfavourites_get_favourite_comments()
    {
        //Get $_POST parameters
        $params = [
            'draw' => $_POST['draw'],
            'start' => $_POST['start'],
            'length' => $_POST['length'],
            'search_val' => $_POST['search']['value']
        ];
        $ids = get_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME);

        if ($ids != false && count($ids) > 0) {
            $count_query = get_comments(array(
                'comment__in' => $ids,
                'search' => $params['search_val'],
                    )
            );

            //Count total number of posts for DT pagination
            $total_comments = count($count_query);
            wp_reset_query();

            //Get paginated results
            $comments = get_comments(array(
                'offset' => $params['start'],
                'comment__in' => $ids,
                'orderby' => 'comment__in',
                'search' => $params['search_val'],
                    )
            );

            wp_reset_query();
            $comments_array = array();

            if (isset($comments) && !empty($comments)) {
                //Get the already marked post ids to set the heart color
                $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME);

                foreach ($comments as $comment) {
                    //Prepare HTML for actions
                    $space = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    //$edit_action = "$space<a href='" . get_edit_post_link($post->ID) . "'><span class='dashicons dashicons-edit'></span></a>$space";
                    //$view_action = "<a href='" . get_comment_link($comment->comment_ID) . "'><span class='dashicons dashicons-visibility'></span></a>$space";

                    $heart_class = '';
                    if (in_array($comment->comment_ID, $favourites)) {
                        $heart_class = 'already-favourite';
                    }
                    $mark_favourite_action = "<span id='$comment->comment_ID' class='$heart_class dashicons dashicons-thumbs-down'></span>";

                    $data = [
                        'id' => "<span class='favourite-comment-id' id='$comment->comment_ID'>$comment->comment_ID</span>",
                        'action' => $view_action . $mark_favourite_action,
                        'comment' => "<strong data-toggle='tooltip' title='$comment->comment_content'>" . $this->trim_title($comment->comment_content, 20) . "</strong>",
                        'comment_by' => $comment->comment_author . "($comment->comment_author_email)",
                        'dated' => $comment->comment_date,
                        'post_title' => "<a href='" . get_the_permalink($comment->comment_post_ID) . "'>" . get_the_title($comment->comment_post_ID) . "</a>"
                    ];
                    array_push($comments_array, $data);
                }
            }
            //Prepare data for datatable
            $data = [
                'draw' => $params['draw'],
                "recordsTotal" => $total_comments,
                "recordsFiltered" => $total_comments,
                'data' => $comments_array
            ];
        } else {
            $data = [
                'draw' => $params['draw'],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                'data' => array()
            ];
        }
        wp_send_json($data);
    }

    public function wp_myfavourites_get_media()
    {
        $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("media");
        //Get $_POST parameters
        $params = [
            'draw' => $_POST['draw'],
            'start' => $_POST['start'],
            'length' => $_POST['length'],
            'search_val' => $_POST['search']['value']
        ];

        $count_query = new WP_Query(array(
            'date_query' => array(
                array(
                    'after' => $settings['wpmf_media_min_date'],
                    'before' => $settings['wpmf_media_max_date'],
                    'inclusive' => WP_MYFAVOURITES_MEDIA_INCLUSIVE_OF_DATES,
                ),
            ),
            'orderby' => $settings['wpmf_media_order_field'],
            'order' => $settings['wpmf_media_listinig_order'],
            'post_status' => WP_MYFAVOURITES_MEDIA_STATUS,
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            's' => $params['search_val'],
                )
        );

        //Count total number of posts for DT pagination
        $total_posts = $count_query->found_posts;
        wp_reset_postdata();

        //Get paginated results
        $list_query = new WP_Query(array(
            'posts_per_page' => $params['length'],
            'offset' => $params['start'],
            'date_query' => array(
                array(
                    'after' => $settings['wpmf_media_min_date'],
                    'before' => $settings['wpmf_media_max_date'],
                    'inclusive' => WP_MYFAVOURITES_MEDIA_INCLUSIVE_OF_DATES,
                ),
            ),
            'orderby' => $settings['wpmf_media_order_field'],
            'order' => $settings['wpmf_media_listinig_order'],
            'post_status' => WP_MYFAVOURITES_MEDIA_STATUS,
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            's' => $params['search_val'],
                )
        );

        $medias = $list_query->get_posts();
        wp_reset_postdata();

        $medias_array = array();
        //wp_send_json($posts);
        if (isset($medias) && !empty($medias)) {
            //Get the already marked post ids to set the heart color
            $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME);

            foreach ($medias as $media) {
                //Prepare HTML for actions
                $space = "&nbsp;&nbsp;&nbsp;&nbsp;";
                $edit_action = "$space<a href='" . get_edit_post_link($media->ID) . "'><span class='dashicons dashicons-edit'></span></a>$space";
                $view_action = "<a href='" . get_permalink($media->ID) . "'><span class='dashicons dashicons-visibility'></span></a>$space";

                $heart_class = '';
                if (in_array($media->ID, $favourites)) {
                    $heart_class = 'already-favourite';
                }
                $mark_favourite_action = "<span id='$media->ID' class='$heart_class dashicons dashicons-thumbs-up'></span>";

                $data = [
                    'action' => $edit_action . $view_action . $mark_favourite_action,
                    'id' => $media->ID,
                    'media' => wp_get_attachment_image($media->ID, array('50', '50'), false, array('title' => $media->post_name)),
                    'author' => get_author_name($media->post_author),
                    'dated' => $media->post_date,
                ];
                array_push($medias_array, $data);
            }
        }

        $data = [
            'draw' => $params['draw'],
            "recordsTotal" => $total_posts,
            "recordsFiltered" => $total_posts,
            'data' => $medias_array
        ];
        wp_send_json($data);
    }

    public function wp_myfavourites_mark_favourite_media()
    {
        $settings = Wp_MyFavourites_Settings::wp_myfavourites_get_settings("media");
        //Get the post id
        $mediaid = $_POST['content']['media_id'];
        //Get the operation
        $operation = !empty($_POST['content']['operation']) ? $_POST['content']['operation'] : '';
        //Set option name
        $option = WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME;

        //Create option if not exists
        if (!get_option($option)) {
            add_option($option);
        }

        //Get the option values
        $favourites = get_option($option);

        switch ($operation) {
            case "add":
                if ($favourites == "" || count($favourites) < $settings['wpmf_media_max'] && !in_array($mediaid, $favourites)) {
                    if (!is_array($favourites)) {
                        //If there are no value in the option then initialize it
                        $favourites = array($mediaid);
                        $status = update_option($option, $favourites);
                        $error_code = NO_ERR;
                    } else {
                        //append the new value to option and update it
                        array_push($favourites, $mediaid);
                        $status = update_option($option, $favourites);
                        $error_code = NO_ERR;
                    }
                } else {
                    $status = count($favourites);
                    $error_code = ERR_NOT_ABLE_TO_MARK_FAVOURITE_MEDIA;
                }
                break;

            case "remove" :
                if (is_array($favourites) && in_array($mediaid, $favourites)) {
                    //Remove the id and update option

                    $key = array_search($mediaid, $favourites);

                    if ($key >= 0) {
                        unset($favourites[$key]);
                    }

                    $status = update_option($option, $favourites);
                    $error_code = NO_ERR;
                } else {
                    $status = -1;
                    $error_code = ERR_NOT_ABLE_TO_REMOVE_FAVOURITE_MEDIA;
                }
                break;
            default:
        }
        $response = array(
            'status' => $status, 'error' => $error_code
        );
        wp_send_json($response);
    }

    public function wp_myfavourites_get_favourite_media()
    {
        //Get $_POST parameters
        $params = [
            'draw' => $_POST['draw'],
            'start' => $_POST['start'],
            'length' => $_POST['length'],
            'search_val' => $_POST['search']['value']
        ];
        $ids = get_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME);

        if ($ids != false && count($ids) > 0) {
            $count_query = new WP_Query(array(
                'post__in' => $ids,
                'post_status' => WP_MYFAVOURITES_MEDIA_STATUS,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                's' => $params['search_val'],
                    )
            );
            //Count total number of posts for DT pagination
            $total_medias = $count_query->found_posts;
            wp_reset_postdata();
            //Get paginated results
            $list_query = new WP_Query(array(
                'posts_per_page' => $params['length'],
                'offset' => $params['start'],
                'post_status' => WP_MYFAVOURITES_MEDIA_STATUS,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post__in' => $ids,
                'orderby' => 'post__in',
                's' => $params['search_val'],
                    )
            );

            $medias = $list_query->get_posts();

            wp_reset_postdata();
            $medias_array = array();

            if (isset($medias) && !empty($medias)) {
                //Get the already marked post ids to set the heart color
                $favourites = get_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME);

                foreach ($medias as $media) {

                    //Prepare HTML for actions
                    $space = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $edit_action = "$space<a href='" . get_edit_post_link($media->ID) . "'><span class='dashicons dashicons-edit'></span></a>$space";
                    $view_action = "<a href='" . get_permalink($media->ID) . "'><span class='dashicons dashicons-visibility'></span></a>$space";

                    $heart_class = '';
                    if (in_array($media->ID, $favourites)) {
                        $heart_class = 'already-favourite';
                    }
                    $mark_favourite_action = "<span id='$media->ID' class='$heart_class dashicons dashicons-thumbs-down'></span>";

                    $data = [
                        'action' => $edit_action . $view_action . $mark_favourite_action,
                        'id' => "<span class='favourite-media-id' id='$media->ID'>$media->ID</span>",
                        'media' => wp_get_attachment_image($media->ID, array('50', '50'), false, array('title' => $media->post_name)),
                        'author' => get_author_name($media->post_author),
                        'dated' => $media->post_date,
                    ];
                    array_push($medias_array, $data);
                }
            }
            //Prepare data for datatable
            $data = [
                'draw' => $params['draw'],
                "recordsTotal" => $total_medias,
                "recordsFiltered" => $total_medias,
                'data' => $medias_array
            ];
        } else {
            $data = [
                'draw' => $params['draw'],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                'data' => array()
            ];
        }
        wp_send_json($data);
    }

    public function wp_myfavourites_reorder_favourites()
    {
        $ids = $_POST['content']['reordered_ids'];
        $type = $_POST['content']['entity_type'];
        if ($type == 'posts') {
            $status = update_option(WP_MYFAVOURITES_FAVOURITE_POSTS_OPTION_NAME, $ids);
            wp_send_json($status);
        }
        if ($type == 'comments') {
            $status = update_option(WP_MYFAVOURITES_FAVOURITE_COMMENTS_OPTION_NAME, $ids);
            wp_send_json($status);
        }
        if ($type == 'media') {
            $status = update_option(WP_MYFAVOURITES_FAVOURITE_MEDIA_OPTION_NAME, $ids);
            wp_send_json($status);
        }
    }

}
