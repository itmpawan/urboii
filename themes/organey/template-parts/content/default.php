<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (has_post_thumbnail()) { ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('post-thumbnail'); ?>
        </div>
    <?php } ?>
    <header class="entry-header">
        <div class="post-meta">
            <?php
            organey_post_meta();
            ?>
        </div>
        <?php the_title(sprintf('<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
    </header><!-- .entry-header -->
    <div class="entry-content">
        <?php
        the_content(
            sprintf(
            /* translators: %s: post title */
                esc_html__('Read More', 'organey') . ' %s',
                '<span class="screen-reader-text">' . get_the_title() . '</span>'
            )
        );

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'organey'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
