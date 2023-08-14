<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product', $product); ?>>
    <div class="product-block-list product-block-list-1">
        <div class="product-caption">
            <div class="caption-left">
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="menu-thumb">
                    <?php echo wp_kses_post( $product->get_image() ); ?>
                </a>
            </div>
            <div class="caption-right">
                <?php woocommerce_template_loop_product_title(); ?>
                <?php woocommerce_template_loop_rating(); ?>
                <?php woocommerce_template_loop_price(); ?>
                <?php organey_woocommerce_get_product_short_description(); ?>
                <div class="product-caption-footer">
                    <div class="add-to-cart">
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                    <?php organey_woocommerce_time_sale(); ?>
                </div>

            </div>
        </div>
    </div>
</li>
