<div class="column-item post-style-1">
    <div class="post-inner">
		<?php if ( has_post_thumbnail() && '' !== get_the_post_thumbnail() ) : ?>
            <div class="post-thumbnail">
				<?php ob_start(); ?>
                <a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'organey-post-grid' ); ?>
                </a>
				<?php organey_render_skeleton( 'skeleton-blog-thumbnail' ); ?>
				<?php
				if ( get_the_category_list( '' ) ) : ?>
                    <div class="meta-categories">
						<?php echo get_the_category_list( ' ' ); ?>
                    </div>
				<?php endif; ?>
            </div><!-- .post-thumbnail -->
		<?php endif; ?>
        <div class="post-caption">
            <div class="post-meta">
				<?php
				organey_post_meta();
				?>
            </div>
			<?php
			the_title( '<h3 class="entry-title"><a href="' . get_the_permalink() . '">', '</a></h3>' );
			?>
        </div>
    </div>
</div>
