<ul class="wp-mf-public" id="wp-mf-public-comment-list-wrapper">
    <?php
    if (isset($comments) && !empty($comments)):
        foreach ($comments as $comment):
            ?>
            <li id="wp-mf-comment-id-<?php echo $comment->comment_ID; ?>">
                <span id="wp-mf-comment-author">
                    <a href="<?php echo get_comment_link($comment); ?>"><?php echo $comment->comment_author; ?> </a> 
                    <span>commented on</span> 
                    <span id="wp-mf-comment-post"><?php echo substr(get_the_title($comment->comment_post_ID), 0, 15) . '...'; ?>
                    </span>
                </span>
                <p id="wp-mf-comment-content"><?php echo substr(strip_tags($comment->comment_content), 0, $character_limit); ?>...</p>
            </li>
            <?php
        endforeach;
    endif;
    ?>
</ul>