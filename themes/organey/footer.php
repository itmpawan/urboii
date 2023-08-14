<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package Organey
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (function_exists('hfe_init') && (hfe_footer_enabled() || hfe_is_before_footer_enabled())) {
    do_action('hfe_footer_before');
    do_action('hfe_footer');
} else {
    if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
        get_template_part('template-parts/footer');
    }
}
?>

<?php wp_footer(); ?>

</body>
</html>
