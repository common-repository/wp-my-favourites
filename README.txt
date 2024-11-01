=== WP My Favourites ===
Contributors: Myself.Neelkanth
Tags: posts, comments, media, favourites, theme development
Requires at least: 3.0.1
Tested up to: 4.8.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Choose your favourite posts, pages, comments, media and reorder them to display anywhere on your website.

=== Description ===

WP My Favourites is a simple plugin which allows you to select the best posts, pages, comments and media from backend and fetch them as and when needed in your website. It also allows you to reorder your favourite as per your wish using Drag and Drop.

=== Some of the features ===

* AJAX-powered backend settings section.
* Tabular UI for easy management.
* Tooltips on hover and Help(?) icons in the backend for first time users.
* Choose your best items simple by clicking the Thumbs Up/Thumbs Down icons.
* Easily reorder by dragging and dropping the selected posts.
* No need to click save button etc. Selected items are saved in database as the selects or reorder them.
* Change the settings as per your need from the settings given in WP My Favourites - Configuration section in backend.

=== Installation ===

1. Upload `wp-myfavourites` folder to the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress


=== How to use - Backend ===

1. After activating the plugin you can see WP My Favourites menu item in the dashboard.
2. Under the menu name you can see two sub menus: Favourite posts, Favourite comments, Favourite media.
3. Click on any sub menu. Suppose you clicked on Favourite posts.
   (Note: Favourite posts also lets you select pages also.)
4. You can change the settings for the listing and selection of favourites by clicking on the Settings section.
4. On top you will see Select any 15 posts by default. This 15 can be changed in the `wp-myfavourites/wp-myfavourites.php` file.
5. Adjacent to Select any 15 posts in Help(?) icon. Hover it to get some help.
6. The first table shows all the posts and pages. You can also add custom posts be adding the custom post type slug in `const WP_MYFAVOURITES_POST_TYPE` array in `wp-myfavourites/wp-myfavourites.php` file.
7. To select a post simple click on the Black Thumbs Up which represents un-selected post. Once you click on the Black Thumbs Up that post will be added to the Favourite post section and the Black Thumbs Up will turn into Blue.
8. You can see your selected posts in the second table. To reorder the posts simple Drag the first cell in the second table and Drop on the desired position.
9. If you want to remove a selected post then simple Click on the Blue Thumbs Up on the first table or click on the Blue Thumbs Down in the second table.
10. The same applies to the Favourite comment and Favourite Media section as well.

=== How to use - Frontend ===

== Getting listing using shortcodes ==

==1. Get favourite posts with HTML==

`[show-favourite-posts post_types='post,page' no_of_posts=-1 no_of_chars='20']`

Shorcode arguments:

* post_types: Comma separated list of post types to fetch from selected posts in backend.
* no_of_posts: Number specifying the total number of posts to fetch from the selected posts.
* no_of_chars: Number specifying the number of characters to get from the post's content.

==2. Get favourite comments with HTML==

`[show-favourite-comments no_of_comments=10 no_of_chars=20]`

Shorcode arguments:

* no_of_comments: Number specifying the total number of comments to fetch from the selected comments.
* no_of_chars: Number specifying the number of characters to get from the comment's content.

==3. Get favourite media with HTML==

`[show-favourite-media no_of_media=20 image_size=120,120]`

Shorcode arguments:

* no_of_media: Number specifying the total number of media items to fetch from the selected media.
* image_size: Comma separated width and height respectively of the image. Default is 20,20.

== Getting WP_Post and WP_Comment Object ==

==1. Get array favourite posts==

Call `<?php Wp_MyFavourites_Public::wp_mf_favourite_posts($post_types,$number_of_posts ) ?>`

Method arguments:

* $post_types: Array of post types to fetch from the selected posts
* $number_of_posts: Number specifying the total number of posts to fetch from the selected posts.


==2. Get array of favourite comments==

Call `<?php Wp_MyFavourites_Public::wp_mf_favourite_comments($no_of_comments) ?>`

Method arguments:

* $number_of_comments: Number specifying the total number of comments to fetch from the selected posts.

==3. Get array of favourite media==

Call `<?php Wp_MyFavourites_Public::wp_mf_favourite_media($no_of_media) ?>`

Method arguments:

* $no_of_media: Number specifying the total number of media items to fetch from the selected media.

== 3. Getting array of selected ids ==

1. `<?php Wp_MyFavourites_Public::wp_mf_get_favourite_ids($type,$count) ?>`

Method arguments:

* $type: Accepts 'posts' or 'comments' or 'media' as parameters
* $count: Number of ids to fetch

== Screenshots ==

1. WP My Favourites - Favourite Posts/Pages
2. WP My Favourites - Favourite Comments
3. WP My Favourites - Favourite Media
4. WP My Favourites - Posts configuration
5. WP My Favourites - Comments configuration
6. WP My Favourites - Media configuration

== Changelog ==

= 1.0.0 =
First stable release.
= 1.1.0 =
Created backend configuration section.