<ul class="wp-mf-public" id="wp-mf-public-media-list-wrapper">
    <?php
    if (isset($medias) && !empty($medias)):
        foreach ($medias as $media):
            ?>
            <li id="wp-mf-media-id-<?php echo $media->ID; ?>">
                <?php echo wp_get_attachment_image($media->ID, $image_size); ?>
            </li>
            <?php
        endforeach;
    endif;
    ?>
</ul>