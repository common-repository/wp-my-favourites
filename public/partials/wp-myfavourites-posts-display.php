<ul class="wp-mf-public" id="wp-mf-public-post-list-wrapper">
    <?php
    if (isset($posts) && !empty($posts)):
        foreach ($posts as $post):
            ?>
            <li id="wp-mf-post-id-<?php echo $post->ID; ?>">
                <span id="wp-mf-post-title">
                    <a href="<?php echo get_the_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
                </span>
                <p id="wp-mf-post-content"><?php echo substr(strip_tags($post->post_content), 0, $character_limit); ?></p>
            </li>
            <?php
        endforeach;
    endif;
    ?>
</ul>