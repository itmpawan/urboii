<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package Organey
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php
while ( have_posts() ) : the_post();
	?>
    <main <?php post_class( 'site-main' ); ?> role="main">
        <div class="container clearfix">
            <div id="primary" class="content-area">
                <header class="page-header">
					<?php
					if ( 'post' === get_post_type() ) {
						if ( get_the_category_list( ', ' ) ) : ?>
                            <span class="meta-categories"><?php echo get_the_category_list( ' ' ); ?></span>
						<?php endif;
					}
					if ( 'post' === get_post_type() ):
						?>
                        <div class="post-meta">
							<?php
							organey_post_meta();
							?>
                        </div>
					<?php
					endif;

					if ( apply_filters( 'organey_page_title', true ) || is_singular( 'post' ) ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					endif; ?>
                </header>
				<?php if ( has_post_thumbnail() ) { ?>
                    <div class="post-thumbnail">
						<?php the_post_thumbnail( 'post-thumbnail' ); ?>
                    </div>
				<?php } ?>
                <div class="page-content">
                    <div class="entry-content">
						<?php the_content(); ?>
                    </div>
					<?php if ( has_tag() ): ?>
                        <div class="post-tags">
							<?php the_tags( '<span class="tag-links"><span class="screen-reader-text">' . esc_html__( 'Tagged ', 'organey' ) . '</span>', null, '</span>' ); ?>
                        </div>
					<?php endif; ?>
					<?php wp_link_pages();

					?>

                </div>

				<?php
				comments_template();
				?>

				<?php
				if ( is_singular( 'post' ) ):

					$args = array(
						'next_text' => '<span class="nav-content"><span class="reader-text">' . esc_html__( 'NEXT POST', 'organey' ) . ' <i class="organey-icon-angle-right"></i></span><span class="title">%title</span></span> ',
						'prev_text' => '<span class="nav-content"><span class="reader-text"><i class="organey-icon-angle-left"></i>' . esc_html__( 'PREVIOUS POST', 'organey' ) . ' </span><span class="title">%title</span></span> ',
					);

					the_post_navigation( $args );

				endif;
				?>
            </div>
			<?php get_sidebar(); ?>
        </div>
    </main>
<?php
endwhile;
