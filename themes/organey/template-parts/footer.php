<?php
/**
 * The template for displaying footer.
 *
 * @package Organey
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<footer id="site-footer" class="site-footer" role="contentinfo">
    <div class="copyright-bar">
        <div class="container">
            <div class="site-info">
	            <?php echo apply_filters('organey_copyright_text', $content = esc_html__('Coppyright', 'organey') . ' &copy; ' . date('Y') . ' ' . '<a class="site-url" href="' . esc_url(site_url()) . '">' . esc_html(get_bloginfo('name')) . '</a>' . esc_html__('. All Rights Reserved.', 'organey')); ?>
            </div>
        </div>
    </div>
</footer>
