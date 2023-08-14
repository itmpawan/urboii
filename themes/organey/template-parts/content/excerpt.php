<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
        <div class="post-thumbnail">
	        <?php ob_start(); ?>
			<?php the_post_thumbnail( 'post-thumbnail' ); ?>
			<?php organey_render_skeleton('skeleton-blog-thumbnail'); ?>
			<?php if ( get_the_category_list( '' ) ) : ?>
                <div class="meta-categories">
					<?php echo get_the_category_list( ' ' ); ?>
                </div>
			<?php endif;
			?>
        </div>
	<?php } else {
		if ( get_the_category_list( '' ) ) : ?>
            <div class="meta-categories">
				<?php echo get_the_category_list( ' ' ); ?>
            </div>
		<?php endif;
	} ?>

    <header class="entry-header">
		<?php if ( 'product' !== get_post_type() ): ?>
            <div class="post-meta">
				<?php
				organey_post_meta();
				?>
            </div>
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
    </header><!-- .entry-header -->
    <div class="entry-content">
		<?php

		the_excerpt();

		printf( '<p><a href="%s" class="more-link">%s <i class="organey-icon-long-arrow-alt-right"></i></a></p>', esc_url( get_permalink() ), esc_html__( 'Read More', 'organey' ) );

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'organey' ),
				'after'  => '</div>',
			)
		);
		?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
