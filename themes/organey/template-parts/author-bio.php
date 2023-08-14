<?php
if ((bool)get_the_author_meta('description')) :
    ?>
    <div class="author-wrapper">
        <div class="author-avatar">
            <img src="<?php echo esc_url(get_avatar_url(get_the_author_meta('ID'))); ?>"/>
        </div>

        <h6 class="about-heading"><?php esc_html_e("About author", 'organey') ?></h6>
        <div class="author-name">
            <div class="a-name"><?php echo get_the_author(); ?></div>
        </div>
        <div class="author-description">
            <?php the_author_meta('description'); ?>
            <a class="author-link" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
                <?php _e('View more posts', 'organey'); ?>
            </a>
        </div>
    </div>
<?php endif; ?>