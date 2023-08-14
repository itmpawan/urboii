<?php
if ( 'div' === $args['style'] ) {
	$tag       = 'div';
	$add_below = 'comment';
} else {
	$tag       = 'li';
	$add_below = 'div-comment';
}
?>
    <<?php echo esc_attr( $tag ) . ' '; ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
    <div class="comment-body">
    <div class="comment-meta commentmetadata">
        <div class="comment-author vcard">
			<?php echo get_avatar( $comment, 128 ); ?>
			<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>
        </div>
		<?php if ( '0' === $comment->comment_approved ) : ?>
            <em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'organey' ); ?></em>
            <br/>
		<?php endif; ?>

        <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>"
           class="comment-date">
			<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
        </a>
    </div>
<?php if ( 'div' !== $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
<?php endif; ?>
    <div class="comment-text">
		<?php comment_text(); ?>
    </div>
    <div class="reply">
		<?php
		comment_reply_link(
			array_merge(
				$args, array(
					'add_below' => $add_below,
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
				)
			)
		);
		?>
		<?php edit_comment_link( esc_html__( 'Edit', 'organey' ), '  ', '' ); ?>
    </div>
    </div>
<?php if ( 'div' !== $args['style'] ) : ?>
    </div>
<?php endif; ?>
