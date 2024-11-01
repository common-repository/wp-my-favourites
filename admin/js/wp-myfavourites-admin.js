(function($) {
    'use strict';
    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(document).ready(initScript);
    function initScript() {
        settingsInitialization();
        //defing global ajax post url
        window.ajaxPostUrl = ajax_object.ajax_url;
        //Loading all the posts
        wpMyFavouritesPostListingDataTable();
        //Loading all the comments
        wpMyFavouritesCommentListingDataTable();
        //Loading all the media
        wpMyFavouritesMediaListingDataTable();
        //Loading all the favourite posts
        wpMyFavouritesFavouritePostsDataTable();
        //Loading all the favourite comments
        wpMyFavouritesFavouriteCommentsDataTable();
        //Loading all the favourite media
        wpMyFavouritesFavouriteMediaDataTable();
        //Capture make favourite post click
        $(document).on('click', '#wp-myfavourites-post-list-datatable .dashicons-thumbs-up,#wp-myfavourites-favourite-posts-list-datatable .dashicons-thumbs-up,#wp-myfavourites-favourite-posts-list-datatable .dashicons-thumbs-down', function(e) {

            wpMyFavouritesMarkAsFavourite(this);
        });
        //Capture make favourite comment click
        $(document).on('click', '#wp-myfavourites-comment-list-datatable .dashicons-thumbs-up,#wp-myfavourites-favourite-comments-list-datatable .dashicons-thumbs-up,#wp-myfavourites-favourite-comments-list-datatable .dashicons-thumbs-down', function(e) {

            wpMyFavouritesMarkAsFavouriteComment(this);
        });
        //Capture make favourite media click
        $(document).on('click', '#wp-myfavourites-media-list-datatable .dashicons-thumbs-up,#wp-myfavourites-favourite-media-list-datatable .dashicons-thumbs-up,#wp-myfavourites-favourite-media-list-datatable .dashicons-thumbs-down', function(e) {

            wpMyFavouritesMarkAsFavouriteMedia(this);
        });
    }

    function wpMyFavouritesMarkAsFavourite(obj)
    {
//get id attribute of clicked element as post id
        var id = $(obj).attr('id');
        var op = $(obj).hasClass("already-favourite") ? "remove" : "add";
        var content = {
            post_id: id, operation: op
        };
        //Send Ajax to Check and Save Faourite Post
        var mark_as_favourite = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            dataType: 'json',
            data: {
                action: "mark_favourite_post",
                content: content
            },
            success: function(data) {
                if (op == "add" && data.error === 1) {
                    alert('You can only add ' + data.status + ' favourites.');
                }
                else if (op == "remove" && data.error === 2) {
                    alert('There is some error in removing this item.');
                }
                else
                {
                    var oPostListTable = $("#wp-myfavourites-post-list-datatable").dataTable();
                    //Pass false to stay on same page after table reload/refresh
                    oPostListTable.fnDraw(false);
                    var oFavouritePostListTable = $("#wp-myfavourites-favourite-posts-list-datatable").dataTable();
                    oFavouritePostListTable.fnDraw();
                }
            },
            error: function(data) {

            }
        });
    }

    function wpMyFavouritesMarkAsFavouriteComment(obj)
    {
//get id attribute of clicked element as post id
        var id = $(obj).attr('id');
        var op = $(obj).hasClass("already-favourite") ? "remove" : "add";
        var content = {
            comment_id: id, operation: op
        };
        //Send Ajax to Check and Save Faourite Post
        var mark_as_favourite = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            dataType: 'json',
            data: {
                action: "mark_favourite_comment",
                content: content
            },
            success: function(data) {
                if (op == "add" && data.error === 3) {
                    alert('You can only add ' + data.status + ' favourites.');
                }
                else if (op == "remove" && data.error === 4) {
                    alert('There is some error in removing this item.');
                }
                else {
                    var oPostListTable = $("#wp-myfavourites-comment-list-datatable").dataTable();
                    //Pass false to stay on same page after table reload/refresh
                    oPostListTable.fnDraw(false);
                    var oFavouritePostListTable = $("#wp-myfavourites-favourite-comments-list-datatable").dataTable();
                    oFavouritePostListTable.fnDraw();
                }
            },
            error: function(data) {

            }
        });
    }
    function wpMyFavouritesPostListingDataTable()
    {
        var tb = $('#wp-myfavourites-post-list-datatable').DataTable(
                {
                    "bPaginate": true,
                    "bLengthChange": true,
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "responsive": true,
                    "pageLength": 5,
                    "serverSide": true,
                    "processing": true,
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    "language": {
                        "processing": "Loading blogs....",
                        "emptyTable": "There are no posts to show.",
                    },
                    "ajax": {
                        "url": ajaxPostUrl,
                        "type": "POST",
                        "data": function(data) {
                            data.action = "get_posts";
                        }
                    },
                    "columns": [
                        {"data": "id", "name": "id", "orderable": false},
                        {"data": "action", "name": "action", "orderable": false},
                        {"data": "title", "name": "title", "orderable": false},
                        {"data": "type", "name": "type", "orderable": false},
                        {"data": "categories.[].name", "name": "categories", "orderable": false},
                        {"data": "author", "name": "categories", "orderable": false},
                        {"data": "dated", "name": "dated", "orderable": false}
                    ]
                }
        );
    }
    function wpMyFavouritesFavouritePostsDataTable()
    {
        var tb = $('#wp-myfavourites-favourite-posts-list-datatable').DataTable(
                {
                    "bPaginate": false,
                    "bLengthChange": false,
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "responsive": true,
                    "pageLength": 25,
                    "serverSide": true,
                    "processing": true,
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    "rowReorder": {
                        update: false
                    },
                    "language": {
                        "processing": "Refreshing.....",
                        "emptyTable": "There are 0 posts selected",
                    },
                    "ajax": {
                        "url": ajaxPostUrl,
                        "type": "POST",
                        "data": function(data) {
                            data.action = "get_favourite_posts";
                        }
                    },
                    "columns": [
                        {"data": "id", "name": "id", "orderable": false},
                        {"data": "action", "name": "action", "orderable": false},
                        {"data": "title", "name": "title", "orderable": false},
                        {"data": "type", "name": "type", "orderable": false},
                        {"data": "categories.[].name", "name": "categories", "orderable": false},
                        {"data": "author", "name": "categories", "orderable": false},
                        {"data": "dated", "name": "dated", "orderable": false}
                    ]
                }
        );
        tb.on('row-reorder', function(e, details, edit) {
            var reordered_ids = [];
            $('.favourite-post-id').each(function() {
                reordered_ids.push(this.id);
            });
            wpMyFavouritesReorderFavourites(reordered_ids, 'posts');
        });
    }

    function wpMyFavouritesCommentListingDataTable()
    {
        $('#wp-myfavourites-comment-list-datatable').DataTable(
                {
                    "bPaginate": true,
                    "bLengthChange": true,
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "responsive": true,
                    "pageLength": 5,
                    "serverSide": true,
                    "processing": true,
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    "language": {
                        "processing": "Loading comments....",
                        "emptyTable": "There are no comments to show.",
                    },
                    "ajax": {
                        "url": ajaxPostUrl,
                        "type": "POST",
                        "data": function(data) {
                            data.action = "get_comments";
                        }
                    },
                    "columns": [
                        {"data": "id", "name": "id", "orderable": false},
                        {"data": "action", "name": "action", "orderable": false},
                        {"data": "comment", "name": "comment", "orderable": false},
                        {"data": "comment_by", "name": "comment_by", "orderable": false},
                        {"data": "dated", "name": "dated", "orderable": false},
                        {"data": "post_title", "name": "post_title", "orderable": false},
                    ]
                }
        );
    }

    function wpMyFavouritesFavouriteCommentsDataTable()
    {
        var tb = $('#wp-myfavourites-favourite-comments-list-datatable').DataTable(
                {
                    "bPaginate": false,
                    "bLengthChange": false,
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "responsive": true,
                    "pageLength": 25,
                    "serverSide": true,
                    "processing": true,
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    "rowReorder": {
                        update: false
                    },
                    "language": {
                        "processing": "Refreshing.....",
                        "emptyTable": "There are 0 comments selected",
                    },
                    "ajax": {
                        "url": ajaxPostUrl,
                        "type": "POST",
                        "data": function(data) {
                            data.action = "get_favourite_comments";
                        }
                    },
                    "columns": [
                        {"data": "id", "name": "id", "orderable": false},
                        {"data": "action", "name": "action", "orderable": false},
                        {"data": "comment", "name": "comment", "orderable": false},
                        {"data": "comment_by", "name": "comment_by", "orderable": false},
                        {"data": "dated", "name": "dated", "orderable": false},
                        {"data": "post_title", "name": "post_title", "orderable": false},
                    ]
                }
        );
        tb.on('row-reorder', function(e, details, edit) {
            var reordered_ids = [];
            $('.favourite-comment-id').each(function() {
                reordered_ids.push(this.id);
            });
            wpMyFavouritesReorderFavourites(reordered_ids, 'comments');
        });
    }

    function wpMyFavouritesReorderFavourites(ids, type)
    {
        var content = {
            reordered_ids: ids, entity_type: type
        };
        //Send Ajax to Check and Save Faourite Post
        var reorder_favourites = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            dataType: 'json',
            data: {
                action: "reorder_favourites",
                content: content
            },
            success: function(data) {
                console.log(data);
            },
            error: function(data) {

            }
        });
    }

    function wpMyFavouritesMediaListingDataTable()
    {
        var tb = $('#wp-myfavourites-media-list-datatable').DataTable(
                {
                    "bPaginate": true,
                    "bLengthChange": true,
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "responsive": true,
                    "pageLength": 5,
                    "serverSide": true,
                    "processing": true,
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    "language": {
                        "processing": "Loading media....",
                        "emptyTable": "There are no media to show.",
                    },
                    "ajax": {
                        "url": ajaxPostUrl,
                        "type": "POST",
                        "data": function(data) {
                            data.action = "get_media";
                        }
                    },
                    "columns": [
                        {"data": "id", "name": "id", "orderable": false},
                        {"data": "action", "name": "action", "orderable": false},
                        {"data": "media", "name": "media", "orderable": false},
                        {"data": "author", "name": "author", "orderable": false},
                        {"data": "dated", "name": "dated", "orderable": false}
                    ]
                }
        );
    }

    function wpMyFavouritesMarkAsFavouriteMedia(obj)
    {
//get id attribute of clicked element as post id
        var id = $(obj).attr('id');
        var op = $(obj).hasClass("already-favourite") ? "remove" : "add";
        var content = {
            media_id: id, operation: op
        };
        //Send Ajax to Check and Save Faourite Post
        var mark_as_favourite = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            dataType: 'json',
            data: {
                action: "mark_favourite_media",
                content: content
            },
            success: function(data) {
                if (op == "add" && data.error === 5) {
                    alert('You can only add ' + data.status + ' favourites.');
                }
                else if (op == "remove" && data.error === 6) {
                    alert('There is some error in removing this item.');
                }
                else
                {
                    var oMediaListTable = $("#wp-myfavourites-media-list-datatable").dataTable();
                    //Pass false to stay on same page after table reload/refresh
                    oMediaListTable.fnDraw(false);
                    var oFavouriteMediaListTable = $("#wp-myfavourites-favourite-media-list-datatable").dataTable();
                    oFavouriteMediaListTable.fnDraw();
                }
            },
            error: function(data) {

            }
        });
    }

    function wpMyFavouritesFavouriteMediaDataTable()
    {
        var tb = $('#wp-myfavourites-favourite-media-list-datatable').DataTable(
                {
                    "bPaginate": false,
                    "bLengthChange": false,
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "responsive": true,
                    "pageLength": 25,
                    "serverSide": true,
                    "processing": true,
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    "rowReorder": {
                        update: false
                    },
                    "language": {
                        "processing": "Refreshing.....",
                        "emptyTable": "There are 0 media selected",
                    },
                    "ajax": {
                        "url": ajaxPostUrl,
                        "type": "POST",
                        "data": function(data) {
                            data.action = "get_favourite_media";
                        }
                    },
                    "columns": [
                        {"data": "id", "name": "id", "orderable": false},
                        {"data": "action", "name": "action", "orderable": false},
                        {"data": "media", "name": "media", "orderable": false},
                        {"data": "author", "name": "author", "orderable": false},
                        {"data": "dated", "name": "dated", "orderable": false}
                    ]
                }
        );
        tb.on('row-reorder', function(e, details, edit) {
            var reordered_ids = [];
            $('.favourite-media-id').each(function() {
                reordered_ids.push(this.id);
            });
            wpMyFavouritesReorderFavourites(reordered_ids, 'media');
        });
    }

    function settingsInitialization()
    {
        //Posts settings start
        $(function() {
            var dateFormat = "dd-mm-yy",
                    from = $("#wpmf-posts-min-date")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 2,
                        dateFormat: "dd-mm-yy"
                    })
                    .on("change", function() {
                        to.datepicker("option", "minDate", getDate(this, dateFormat));
                    }),
                    to = $("#wpmf-posts-max-date")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 2,
                        dateFormat: "dd-mm-yy"
                    })
                    .on("change", function() {
                        from.datepicker("option", "maxDate", getDate(this, dateFormat));
                    });

            function getDate(element, dateFormat) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }
        });
        $("#wpmf-posts-max-slider").slider({
            value: $("#wpmf-posts-max").val(),
            min: 5,
            max: 100,
            step: 1,
            slide: function(event, ui) {
                $("#wpmf-posts-max").val(ui.value);
            }
        });
        $("#wpmf-posts-max").val($("#wpmf-posts-max-slider").slider("value"));
        $("#wpmf-posts-order-field, #wpmf-posts-inclusive-dates, #wpmf-posts-ignore-sticky").select2(
                {
                    width: "25%"
                }
        );
        $("#wpmf-post-types").select2(
                {
                    placeholder: 'Click to select post types',
                    width: "50%"
                }
        );
        $("#wpmf-posts-status").select2(
                {
                    placeholder: 'Click to select post status',
                    width: "50%"
                }
        );

        $("#wpmf-posts-save-settings").click(function() {

            var selected_post_types = [];
            $.each($('#wpmf-post-types').select2('data'), function(index, value) {
                selected_post_types.push(value.id);
            });
            var selected_post_statuses = [];
            $.each($('#wpmf-posts-status').select2('data'), function(index, value) {
                selected_post_statuses.push(value.id);
            });
            var content = {
                wpmf_posts_max: $("#wpmf-posts-max").val(),
                wpmf_posts_min_date: $("#wpmf-posts-min-date").val(),
                wpmf_posts_max_date: $("#wpmf-posts-max-date").val(),
                wpmf_posts_inclusive_dates: $("#wpmf-posts-inclusive-dates").val(),
                wpmf_posts_ignore_sticky: $("#wpmf-posts-ignore-sticky").val(),
                wpmf_posts_order_field: $("#wpmf-posts-order-field").val(),
                wpmf_posts_listinig_order: $("input[name=wpmf-posts-listing-order]:checked").val(),
                wpmf_posts_post_types: selected_post_types,
                wpmf_posts_post_statuses: selected_post_statuses
            };
            //Send Ajax
            jQuery.ajax({
                type: 'POST',
                url: ajaxPostUrl,
                dataType: 'json',
                data: {
                    action: "save_posts_settings",
                    content: content
                },
                beforeSend: function() {
                    $("#posts_settings_save_spinner").show();
                },
                complete: function() {
                    $("#posts_settings_save_spinner").hide();
                },
                success: function(data) {
                    $("#posts_settings_save_success_icon").show().delay(2000).fadeOut();
                },
                error: function(data) {
                }
            });
        });
        //Posts settings - end

        //Comments settings start
        $(function() {
            var dateFormat = "dd-mm-yy",
                    from = $("#wpmf-comments-min-date")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 2,
                        dateFormat: "dd-mm-yy"
                    })
                    .on("change", function() {
                        to.datepicker("option", "minDate", getDate(this, dateFormat));
                    }),
                    to = $("#wpmf-comments-max-date")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 2,
                        dateFormat: "dd-mm-yy"
                    })
                    .on("change", function() {
                        from.datepicker("option", "maxDate", getDate(this, dateFormat));
                    });

            function getDate(element, dateFormat) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }
        });
        $("#wpmf-comments-max-slider").slider({
            value: $("#wpmf-comments-max").val(),
            min: 5,
            max: 100,
            step: 1,
            slide: function(event, ui) {
                $("#wpmf-comments-max").val(ui.value);
            }
        });
        $("#wpmf-comments-max").val($("#wpmf-comments-max-slider").slider("value"));
        $("#wpmf-comments-order-field, #wpmf-comments-inclusive-dates").select2(
                {
                    width: "25%"
                }
        );
        $("#wpmf-comments-post-types").select2(
                {
                    placeholder: 'Click to select comment\'s post types',
                    width: "50%"
                }
        );
        $("#wpmf-comments-status").select2(
                {
                    placeholder: 'Click to select comment status',
                    width: "50%"
                }
        );
        $("#wpmf-comments-post-status").select2(
                {
                    placeholder: 'Click to select comment\'s post status',
                    width: "50%"
                }
        );

        $("#wpmf-comments-save-settings").click(function() {

            var selected_comments_post_types = [];
            $.each($('#wpmf-comments-post-types').select2('data'), function(index, value) {
                selected_comments_post_types.push(value.id);
            });
            var selected_comments_statuses = [];
            $.each($('#wpmf-comments-status').select2('data'), function(index, value) {
                selected_comments_statuses.push(value.id);
            });
            var selected_comments_post_statuses = [];
            $.each($('#wpmf-comments-post-status').select2('data'), function(index, value) {
                selected_comments_post_statuses.push(value.id);
            });
            var content = {
                wpmf_comments_max: $("#wpmf-comments-max").val(),
                wpmf_comments_min_date: $("#wpmf-comments-min-date").val(),
                wpmf_comments_max_date: $("#wpmf-comments-max-date").val(),
                wpmf_comments_inclusive_dates: $("#wpmf-comments-inclusive-dates").val(),
                wpmf_comments_order_field: $("#wpmf-comments-order-field").val(),
                wpmf_comments_listinig_order: $("input[name=wpmf-comments-listing-order]:checked").val(),
                wpmf_comments_post_types: selected_comments_post_types,
                wpmf_comments_statuses: selected_comments_statuses,
                wpmf_comments_post_statuses: selected_comments_post_statuses
            };
            //Send Ajax
            jQuery.ajax({
                type: 'POST',
                url: ajaxPostUrl,
                dataType: 'json',
                data: {
                    action: "save_comments_settings",
                    content: content
                },
                beforeSend: function() {
                    $("#comments_settings_save_spinner").show();
                },
                complete: function() {
                    $("#comments_settings_save_spinner").hide();
                },
                success: function(data) {
                    $("#comments_settings_save_success_icon").show().delay(2000).fadeOut();
                },
                error: function(data) {
                }
            });
        });
        //Comments settings - end

        //Media settings start
        $(function() {
            var dateFormat = "dd-mm-yy",
                    from = $("#wpmf-media-min-date")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 2,
                        dateFormat: "dd-mm-yy"
                    })
                    .on("change", function() {
                        to.datepicker("option", "minDate", getDate(this, dateFormat));
                    }),
                    to = $("#wpmf-media-max-date")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 2,
                        dateFormat: "dd-mm-yy"
                    })
                    .on("change", function() {
                        from.datepicker("option", "maxDate", getDate(this, dateFormat));
                    });

            function getDate(element, dateFormat) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }
        });
        $("#wpmf-media-max-slider").slider({
            value: $("#wpmf-media-max").val(),
            min: 5,
            max: 100,
            step: 1,
            slide: function(event, ui) {
                $("#wpmf-media-max").val(ui.value);
            }
        });
        $("#wpmf-media-max").val($("#wpmf-media-max-slider").slider("value"));
        $("#wpmf-media-order-field").select2(
                {
                    width: "25%"
                }
        );

        $("#wpmf-media-save-settings").click(function() {
            var content = {
                wpmf_media_max: $("#wpmf-media-max").val(),
                wpmf_media_min_date: $("#wpmf-media-min-date").val(),
                wpmf_media_max_date: $("#wpmf-media-max-date").val(),
                wpmf_media_order_field: $("#wpmf-media-order-field").val(),
                wpmf_media_listinig_order: $("input[name=wpmf-media-listing-order]:checked").val(),
            };
            //Send Ajax
            jQuery.ajax({
                type: 'POST',
                url: ajaxPostUrl,
                dataType: 'json',
                data: {
                    action: "save_media_settings",
                    content: content
                },
                beforeSend: function() {
                    $("#media_settings_save_spinner").show();
                },
                complete: function() {
                    $("#media_settings_save_spinner").hide();
                },
                success: function(data) {
                    $("#media_settings_save_success_icon").show().delay(2000).fadeOut();
                },
                error: function(data) {
                }
            });
        });
    }
})(jQuery);

