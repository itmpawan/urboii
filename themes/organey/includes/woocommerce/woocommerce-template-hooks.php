<?php
/**
 * Organey WooCommerce hooks
 *
 * @package organey
 */

/**
 *
 * Layout Archive
 *
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
add_action( 'woocommerce_before_shop_loop', 'organey_button_shop_canvas', 30 );
add_action('wp_footer', 'organey_render_woocommerce_shop_canvas', 1);
add_action('woocommerce_before_shop_loop', 'organey_button_grid_list_layout', 35);

add_filter('woosc_button_position_archive','__return_false');
add_filter('woosq_button_position','__return_false');
add_filter('woosw_button_position_archive','__return_false');

/**
 * Cart fragment
 *
 * @see organey_cart_link_fragment()
 */

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');
add_filter('woocommerce_add_to_cart_fragments', 'organey_cart_link_fragment');

/**
 *
 * Product Single
 *
 */
add_action('woocommerce_single_product_summary', 'organey_woocommerce_single_brand', 1);
add_action('organey_single_product_video_360', 'organey_single_product_video_360', 10);
add_action('woocommerce_single_product_summary', 'organey_single_product_extra', 38);

add_filter( 'woosc_button_position_single', '__return_false' );
add_filter( 'woosw_button_position_single', '__return_false' );
add_action('woocommerce_single_product_summary', 'organey_wishlist_button', 35);
add_action('woocommerce_single_product_summary', 'organey_compare_button', 35);
add_action('woocommerce_share', 'organey_social_share', 10);

add_filter('woocommerce_gallery_thumbnail_size',function (){
   return [200,200];
});

$product_single_style = organey_get_theme_option('single_product_gallery_layout', 'horizontal');
switch ($product_single_style) {
    case 'sticky':
        add_theme_support('wc-product-gallery-lightbox');
        add_filter('woocommerce_single_product_image_thumbnail_html', 'organey_woocommerce_single_product_image_thumbnail_html', 10, 2);
        break;
    case 'gallery':

        add_theme_support('wc-product-gallery-lightbox');
        add_filter('woocommerce_single_product_image_thumbnail_html', 'organey_woocommerce_single_product_image_thumbnail_html', 10, 2);
        break;
    default :
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        break;
}

/**
 *
 * Product Block
 *
 */

if (!function_exists('organey_woocommerce_product_hooks')) {
    function organey_woocommerce_product_hooks() {
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
        add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
        // Remove product content link
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    }

    add_action('init', 'organey_woocommerce_product_hooks', 1000);
}
