<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product', $product); ?>>
    <?php do_action('woocommerce_before_shop_loop_item'); ?>
    <div class="product-block-list">
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         *
         */
        do_action( 'woocommerce_before_shop_loop_item_title' );

        ?>

        <div class="product-transition">
            <?php
            organey_template_loop_product_thumbnail();
            woocommerce_show_product_loop_sale_flash();
            ?>
            <?php
            do_action('organey_woocommerce_product_loop_image');
            woocommerce_template_loop_product_link_open();
            woocommerce_template_loop_product_link_close();
            ?>
        </div>
        <div class="product-caption">
            <?php
            woocommerce_template_loop_product_title();
            woocommerce_template_loop_rating();
            woocommerce_template_loop_price();
            organey_woocommerce_get_product_description();
            ?>
            <div class="shop-action">
                <?php
                woocommerce_template_loop_add_to_cart();
                organey_wishlist_button();
                organey_quickview_button();
                organey_compare_button();
                do_action('organey_woocommerce_product_loop_action');
                ?>
            </div>
        </div>
    </div>
    <?php do_action('woocommerce_after_shop_loop_item'); ?>
</li>
