<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product-style-2', $product); ?>>
	<?php ob_start(); ?>
    <?php do_action('woocommerce_before_shop_loop_item'); ?>
    <div class="product-block">
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook
         *
         */
        do_action('woocommerce_before_shop_loop_item_title');
        ?>
        <div class="product-transition">
            <?php
            woocommerce_show_product_loop_sale_flash();
            organey_woocommerce_get_product_label_stock();
            organey_template_loop_product_thumbnail();
            ?>
            <div class="shop-action">
                <?php
                organey_compare_button();
                organey_quickview_button();
                organey_wishlist_button();
                ?>
                <div class="add-to-cart-wrap">
                    <?php
                    woocommerce_template_loop_add_to_cart();
                    ?>
                </div>
                <?php
                do_action('organey_woocommerce_product_loop_action');
                ?>
            </div>
            <?php
            organey_quick_shop_wrapper();
            woocommerce_template_loop_product_link_open();
            woocommerce_template_loop_product_link_close();
            ?>
        </div>
        <div class="product-caption">
            <?php woocommerce_template_loop_rating(); ?>
            <?php woocommerce_template_loop_product_title(); ?>
            <?php woocommerce_template_loop_price(); ?>
            <?php organey_woocommerce_deal_progress(); ?>
        </div>
    </div>
    <?php do_action('woocommerce_after_shop_loop_item'); ?>
	<?php organey_render_skeleton('skeleton-product'); ?>
</li>
