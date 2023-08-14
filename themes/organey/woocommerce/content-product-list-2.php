<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product', $product); ?>>
    <?php ob_start(); ?>
    <div class="product-block-list product-block-list-2">
        <div class="product-caption">
            <div class="caption-left">
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="menu-thumb">
                    <?php echo wp_kses_post( $product->get_image('organey-product-list') ); ?>
                </a>
            </div>
            <div class="caption-right">
                <?php woocommerce_template_loop_product_title(); ?>
                <?php woocommerce_template_loop_rating(); ?>
                <?php woocommerce_template_loop_price(); ?>
            </div>
        </div>
    </div>
    <?php organey_render_skeleton('skeleton-product-list-2'); ?>
</li>
