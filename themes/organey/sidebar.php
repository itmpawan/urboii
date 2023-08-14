<?php
/**
 * The template for displaying sidebar.
 *
 * @package Organey
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * This file is here to avoid the Deprecated Message for sidebar by wp-includes/theme-compat/sidebar.php.
 */

$sidebar = apply_filters('organey_theme_sidebar', '');
if (!$sidebar) {
	return;
}

?>
<div id="secondary" class="widget-area" role="complementary">
	<?php
	ob_start();
	dynamic_sidebar($sidebar);
	organey_render_skeleton('skeleton-widget', 3);
	?>
</div><!-- #secondary -->
