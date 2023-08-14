<?php
if (!function_exists('organey_cart_link_fragment')) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param array $fragments Fragments to refresh via AJAX.
     *
     * @return array            Fragments to refresh via AJAX
     */
    function organey_cart_link_fragment($fragments) {
        ob_start();
        organey_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        ob_start();
//        organey_handheld_footer_bar_cart_link();
        $fragments['a.footer-cart-contents'] = ob_get_clean();

        return $fragments;
    }
}

if (!function_exists('organey_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function organey_cart_link() {
        $cart = WC()->cart;
        ?>
        <a class="cart-contents" data-toggle="button-side" data-target=".site-header-cart-side" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'organey'); ?>">
            <i class="organey-icon-cart"></i>
            <?php if ($cart): ?>
                <span class="count"><?php echo wp_kses_data(sprintf(_n('%d', '%d', WC()->cart->get_cart_contents_count(), 'organey'), WC()->cart->get_cart_contents_count())); ?></span>
                <?php echo WC()->cart->get_cart_subtotal(); ?>
            <?php endif; ?>
        </a>
        <?php
    }
}

if (!function_exists('organey_header_cart_side')) {
    function organey_header_cart_side() {
        if (organey_is_woocommerce_activated()) {
            ?>
            <div class="site-header-cart-side side-wrap">
                <a href="#" class="close-cart-side close-side"></a>
                <div class="cart-side-heading side-heading">
                    <span class="cart-side-title side-title"><?php echo esc_html__('Shopping cart', 'organey'); ?></span>
                </div>
                <?php the_widget('WC_Widget_Cart', 'title='); ?>
            </div>
            <div class="cart-side-overlay side-overlay"></div>
            <?php
        }
    }
}


if (!function_exists('organey_woocommerce_product_loop_image')) {
    function organey_woocommerce_product_loop_image() {
        ?>
        <div class="product-transition"><?php do_action('organey_woocommerce_product_loop_image') ?></div>
        <?php
    }
}

if (!function_exists('organey_template_loop_product_thumbnail')) {
    function organey_template_loop_product_thumbnail($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0) {
        echo organey_get_loop_product_thumbnail();

    }
}

if (!function_exists('organey_get_loop_product_thumbnail')) {
    function organey_get_loop_product_thumbnail($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0) {
        global $product;
        if (!$product) {
            return '';
        }
        $gallery    = $product->get_gallery_image_ids();
        $hover_skin = organey_get_theme_option('woocommerce_product_hover', 'none');
        if ($hover_skin == '0' || count($gallery) <= 0) {
            echo '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';

            return '';
        }
        $image_featured = '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';
        $image_featured .= '<div class="product-image second-image">' . wp_get_attachment_image($gallery[0], 'shop_catalog') . '</div>';

        echo <<<HTML
<div class="product-img-wrap {$hover_skin}">
    <div class="inner">
        {$image_featured}
    </div>
</div>
HTML;
    }
}

if (!function_exists('woocommerce_template_loop_product_title')) {

    /**
     * Show the product title in the product loop.
     */
    function woocommerce_template_loop_product_title() {
        echo '<h2 class="' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '"><a href="' . esc_url_raw(get_the_permalink()) . '">' . get_the_title() . '</a></h2>';
    }
}

if (!function_exists('organey_woocommerce_get_product_short_description')) {
    function organey_woocommerce_get_product_short_description() {
        global $post;
        if ($post->post_excerpt) {
            ?>
            <div class="short-description">
                <?php echo sprintf('%s', $post->post_excerpt); ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('organey_woocommerce_time_sale')) {
    function organey_woocommerce_time_sale() {
        /**
         * @var $product WC_Product
         */
        global $product;

        if (!$product->is_on_sale()) {
            return;
        }

        $time_sale = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        if ($time_sale) {
            wp_enqueue_script('organey-countdown');
            $time_sale += (get_option('gmt_offset') * HOUR_IN_SECONDS);
            ?>
            <div class="time-sale">
                <div class="deal-text"><span><?php echo esc_html__('Ends in: ', 'organey'); ?></span></div>
                <div class="organey-countdown" data-countdown="true" data-date="<?php echo esc_html($time_sale); ?>">
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-days"></span>
                        <span class="countdown-label"><?php echo esc_html__('d', 'organey') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-hours"></span>
                        <span class="countdown-label"><?php echo esc_html__('h', 'organey') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-minutes"></span>
                        <span class="countdown-label"><?php echo esc_html__('m', 'organey') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-seconds"></span>
                        <span class="countdown-label"><?php echo esc_html__('s', 'organey') ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('organey_woocommerce_time_sale_layout_2')) {
    function organey_woocommerce_time_sale_layout_2() {
        /**
         * @var $product WC_Product
         */
        global $product;

        if (!$product->is_on_sale()) {
            return;
        }

        $time_sale = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        if ($time_sale) {
            wp_enqueue_script('organey-countdown');
            $time_sale += (get_option('gmt_offset') * HOUR_IN_SECONDS);
            ?>
            <div class="time-sale">
                <div class="organey-countdown" data-countdown="true" data-date="<?php echo esc_html($time_sale); ?>">
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-days"></span>
                        <span class="countdown-label"><?php echo esc_html__('Days', 'organey') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-hours"></span>
                        <span class="countdown-label"><?php echo esc_html__('Hours', 'organey') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-minutes"></span>
                        <span class="countdown-label"><?php echo esc_html__('Mins', 'organey') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-seconds"></span>
                        <span class="countdown-label"><?php echo esc_html__('Secs', 'organey') ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('organey_woocommerce_deal_progress')) {
    function organey_woocommerce_deal_progress() {
        global $product;

        $limit = get_post_meta($product->get_id(), '_deal_quantity', true);
        $sold  = intval(get_post_meta($product->get_id(), '_deal_sales_counts', true));
        if (empty($limit)) {
            return;
        }

        ?>

        <div class="deal-sold">
            <div class="deal-progress">
                <div class="progress-bar">
                    <div class="progress-value" style="width: <?php echo trim($sold / $limit * 100) ?>%"></div>
                </div>
            </div>
            <div class="deal-sold-text">
                <div class="availavle">
                    <span><?php echo esc_html__('Already: ', 'organey'); ?></span><span><?php echo esc_html($sold); ?></span>
                </div>
                <div class="already-sold">
                    <span><?php echo esc_html__('Already Sold: ', 'organey'); ?></span><span><?php echo esc_html($limit); ?></span>
                </div>
            </div>
        </div>

        <?php
    }
}

if (!function_exists('organey_woocommerce_get_product_label_stock')) {
    function organey_woocommerce_get_product_label_stock() {
        /**
         * @var $product WC_Product
         */
        global $product;
        if ($product->get_stock_status() == 'outofstock') {
            echo '<span class="stock-label">' . esc_html__('Out Of Stock', 'organey') . '</span>';
        }
    }
}

if (!function_exists('organey_woocommerce_get_product_category')) {
    function organey_woocommerce_get_product_category() {
        global $product;
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="posted-in">', '</div>');
    }
}

if (!function_exists('organey_woocommerce_pagination_args')) {

    function organey_woocommerce_pagination_args($args) {
        $args['prev_text'] = '<i class="organey-icon-angle-left"></i>';
        $args['next_text'] = '<i class="organey-icon-angle-right"></i>';

        return $args;
    }

    add_filter('woocommerce_pagination_args', 'organey_woocommerce_pagination_args', 10, 1);
}


if (!function_exists('organey_quickview_button')) {
    function organey_quickview_button() {
        if (function_exists('woosq_init')) {
            echo do_shortcode('[woosq]');
        }
    }
}

if (!function_exists('organey_compare_button')) {
    function organey_compare_button() {
        if (function_exists('woosc_init')) {
            echo do_shortcode('[woosc]');
        }
    }
}

if (!function_exists('organey_wishlist_button')) {
    function organey_wishlist_button() {
        if (function_exists('woosw_init')) {
            echo do_shortcode('[woosw]');
        }
    }
}

if (!function_exists('organey_wishlist_canvas')) {
    function organey_wishlist_canvas() {
        if (function_exists('woosw_init')) {
            ?>
            <div class="site-wishlist-side side-wrap">
                <a href="#" class="close-wishlist-side close-side"></a>
                <div class="wishlist-side-heading side-heading">
                    <span class="wishlist-side-title side-title"><?php echo esc_html__('Wishlist', 'organey'); ?></span>
                </div>
                <div class="wishlist-side-wrap-content">

                    <div class="organey-wishlist-content-scroll">
                        <div class="organey-wishlist-content">

                        </div>
                    </div>
                    <div class="organey-wishlist-bottom">
                        <a class="button"
                           href="<?php echo esc_url(WPCleverWoosw::get_url(WPCleverWoosw::get_key())); ?>"><?php esc_html_e('Wishlist page', 'organey'); ?></a>
                    </div>
                </div>
            </div>
            <div class="wishlist-side-overlay side-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('organey_woocommerce_single_product_image_thumbnail_html')) {
    function organey_woocommerce_single_product_image_thumbnail_html($image, $attachment_id) {
        return wc_get_gallery_image_html($attachment_id, true);
    }
}

if (!function_exists('organey_button_shop_canvas')) {
    function organey_button_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop')) { ?>
            <a href="#" class="filter-toggle" aria-expanded="false">
                <i class="organey-icon-filter"></i><span><?php echo esc_html__('Show filter', 'organey'); ?></span></a>
            <?php
        }
    }
}


if (!function_exists('organey_render_woocommerce_shop_canvas')) {
    function organey_render_woocommerce_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop') && organey_is_product_archive()) {
            ?>
            <div id="organey-canvas-filter" class="organey-canvas-filter">
                <span class="filter-close"><?php esc_html_e('HIDE FILTER', 'organey'); ?></span>
                <div class="organey-canvas-filter-wrap">
                    <?php if (organey_get_theme_option('woocommerce_archive_layout') == 'canvas') {
                        dynamic_sidebar('sidebar-woocommerce-shop');
                    }
                    ?>
                </div>
            </div>
            <div class="organey-overlay-filter"></div>
            <?php
        }
    }
}


if (!function_exists('organey_single_product_video_360')) {
    function organey_single_product_video_360() {
        global $product;
        echo '<div class="product-video-360">';
        $images = get_post_meta($product->get_id(), '_product_360_image_gallery', true);
        $video  = get_post_meta($product->get_id(), '_video_select', true);
        if ($images) {
            $array      = explode(',', $images);
            $images_url = [];
            foreach ($array as $id) {
                $url          = wp_get_attachment_image_src($id, 'full');
                $images_url[] = $url[0];
            }

            echo '<a class="product-video-360__btn btn-360" href="#view-360"><i class="organey-icon-360"></i></a>';
            ?>
            <div id="view-360" class="view-360 zoom-anim-dialog mfp-hide">
                <div id="rotateimages" class="opal-loading"
                     data-images="<?php echo esc_attr(implode(',', $images_url)); ?>"></div>
                <div class="view-360-group">
                    <span class='view-360-button view-360-prev'><i class="organey-icon-chevron-left"></i></span>
                    <i class="organey-icon-360 view-360-svg"></i>
                    <span class='view-360-button view-360-next'><i class="organey-icon-chevron-right"></i></span>
                </div>
            </div>
            <?php
        }


        if ($video && wc_is_valid_url($video)) {

            echo '<a class="product-video-360__btn btn-video" href="' . $video . '"><i class="organey-icon-video"></i></a>';
        }

        echo '</div>';
    }
}

if (!function_exists('organey_woocommerce_single_brand')) {
    function organey_woocommerce_single_brand() {
        $id = get_the_ID();

        $terms = get_the_terms($id, 'product_brand');

        if (is_wp_error($terms)) {
            return $terms;
        }

        if (empty($terms)) {
            return false;
        }

        $links = array();

        foreach ($terms as $term) {
            $link = get_term_link($term, 'product_brand');
            if (is_wp_error($link)) {
                return $link;
            }

            $img = get_term_meta($term->term_id, 'product_brand_logo', true);
            $has_class = '';
            if ($img !== "") {
	            $has_class = ' brand-has-image';
                $src = wp_get_attachment_image_src($img, 'thumbnail');
                $links[] = '<a href="' . esc_url($link) . '" rel="tag"><img src="' . $src[0] . '" alt="' . $term->name . '"/></a>';
            } else {
                $links[] = '<a href="' . esc_url($link) . '" rel="tag">' . $term->name . '</a>';
            }

        }

        echo '<div class="product-brand'.esc_attr($has_class).'">' . join('', $links) . '</div>';
    }
}


if (!function_exists('organey_single_product_extra')) {
    function organey_single_product_extra() {
        global $product;
        $product_extra = organey_get_theme_option('single_product_content_meta', '');
        $product_extra = get_post_meta($product->get_id(), '_extra_info', true) !== '' ? get_post_meta($product->get_id(), '_extra_info', true) : $product_extra;
        if ($product_extra !== '') {
            echo '<div class="organey-single-product-extra">' . html_entity_decode($product_extra) . '</div>';
        }
    }
}

if (!function_exists('organey_single_product_pagination')) {

    function organey_single_product_pagination() {

        // Show only products in the same category?
        $in_same_term   = apply_filters('organey_single_product_pagination_same_category', true);
        $excluded_terms = apply_filters('organey_single_product_pagination_excluded_terms', '');
        $taxonomy       = apply_filters('organey_single_product_pagination_taxonomy', 'product_cat');

        $previous_product = organey_get_previous_product($in_same_term, $excluded_terms, $taxonomy);
        $next_product     = organey_get_next_product($in_same_term, $excluded_terms, $taxonomy);

        if ((!$previous_product && !$next_product) || !is_product()) {
            return;
        }

        ?>
        <div class="organey-product-pagination-wrap">
            <nav class="organey-product-pagination" aria-label="<?php esc_attr_e('More products', 'organey'); ?>">
                <?php if ($previous_product) : ?>
                    <a href="<?php echo esc_url($previous_product->get_permalink()); ?>" class="prev-product"
                       rel="prev">
                        <i class="organey-icon-chevron-left"></i>
                        <div class="product-item">
                            <?php echo wp_kses_post($previous_product->get_image()); ?>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if ($next_product) : ?>
                    <a href="<?php echo esc_url($next_product->get_permalink()); ?>" class="next-product" rel="next">
                        <i class="organey-icon-chevron-right"></i>
                        <div class="product-item">
                            <?php echo wp_kses_post($next_product->get_image()); ?>
                        </div>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
        <?php

    }
}

if (!function_exists('organey_product_search')) {
    /**
     * Display Product Search
     *
     * @return void
     * @uses  organey_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function organey_product_search() {
        if (organey_is_woocommerce_activated()) {
            static $index = 0;
            $index++;
            ?>
            <div class="site-search ajax-search">
                <div class="widget woocommerce widget_product_search">
                    <div class="ajax-search-result d-none"></div>
                    <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"><?php esc_html_e('Search for:', 'organey'); ?></label>
                        <input type="search" id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__('Search products&hellip;', 'organey'); ?>" autocomplete="off" value="<?php echo get_search_query(); ?>" name="s"/>
                        <button type="submit" value="<?php echo esc_attr_x('Search', 'submit button', 'organey'); ?>"><?php echo esc_html_x('Search', 'submit button', 'organey'); ?></button>
                        <input type="hidden" name="post_type" value="product"/>
                    </form>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('organey_ajax_live_search_template')) {
    function organey_ajax_live_search_template() {
        echo <<<HTML
        <script type="text/html" id="tmpl-ajax-live-search-template">
        <div class="product-item-search">
            <# if(data.url){ #>
            <a class="product-link" href="{{{data.url}}}" title="{{{data.title}}}">
            <# } #>
                <# if(data.img){#>
                <img src="{{{data.img}}}" alt="{{{data.title}}}">
                 <# } #>
                <div class="product-content">
                <h3 class="product-title">{{{data.title}}}</h3>
                <# if(data.price){ #>
                {{{data.price}}}
                 <# } #>
                </div>
                <# if(data.url){ #>
            </a>
            <# } #>
        </div>
        </script>
HTML;
    }
}
add_action('wp_footer', 'organey_ajax_live_search_template');

if (!function_exists('organey_quick_shop')) {
    function organey_quick_shop($id = false) {
        if (isset($_GET['id'])) {
            $id = sanitize_text_field((int)$_GET['id']);
        }
        if (!$id || !organey_is_woocommerce_activated()) {
            return;
        }

        global $post;

        $args = array('post__in' => array($id), 'post_type' => 'product');

        $quick_posts = get_posts($args);

        foreach ($quick_posts as $post) :
            setup_postdata($post);
            woocommerce_template_single_add_to_cart();
        endforeach;

        wp_reset_postdata();

        die();
    }

    add_action('wp_ajax_organey_quick_shop', 'organey_quick_shop');
    add_action('wp_ajax_nopriv_organey_quick_shop', 'organey_quick_shop');

}

if (!function_exists('organey_quick_shop_wrapper')) {
    function organey_quick_shop_wrapper() {
        global $product;
        ?>
        <div class="quick-shop-wrapper">
            <div class="quick-shop-close cross-button"><span><?php esc_html_e('Close', 'organey'); ?></span></div>
            <div class="quick-shop-form">
            </div>
        </div>
        <?php
    }
}

function organey_ajax_add_to_cart_handler() {
    WC_Form_Handler::add_to_cart_action();
    WC_AJAX::get_refreshed_fragments();
}

//add_action('wc_ajax_organey_add_to_cart', 'organey_ajax_add_to_cart_handler');
//add_action('wc_ajax_nopriv_organey_add_to_cart', 'organey_ajax_add_to_cart_handler');

// Remove WC Core add to cart handler to prevent double-add
//remove_action('wp_loaded', array('WC_Form_Handler', 'add_to_cart_action'), 20);

function organey_ajax_add_to_cart_add_fragments($fragments) {
    $all_notices  = WC()->session->get('wc_notices', array());
    $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

    ob_start();
    foreach ($notice_types as $notice_type) {
        if (wc_notice_count($notice_type) > 0) {
            wc_get_template("notices/{$notice_type}.php", array(
                'notices' => array_filter($all_notices[$notice_type]),
            ));
        }
    }
    $fragments['notices_html'] = ob_get_clean();

    wc_clear_notices();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'organey_ajax_add_to_cart_add_fragments');


function organey_add_quantity_field() {
    global $product;

    if (!$product->is_sold_individually() && 'variable' != $product->get_type() && $product->is_purchasable()) {
        woocommerce_quantity_input(array('min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity()));
    }

}

if (!function_exists('organey_product_label')) {
    function organey_product_label() {
        global $product;

        $output = array();

        if ($product->is_on_sale()) {

            $percentage = '';

            if ($product->get_type() == 'variable') {

                $available_variations = $product->get_variation_prices();
                $max_percentage       = 0;

                foreach ($available_variations['regular_price'] as $key => $regular_price) {
                    $sale_price = $available_variations['sale_price'][$key];

                    if ($sale_price < $regular_price) {
                        $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);

                        if ($percentage > $max_percentage) {
                            $max_percentage = $percentage;
                        }
                    }
                }

                $percentage = $max_percentage;
            } elseif (($product->get_type() == 'simple' || $product->get_type() == 'external')) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
            }

            if ($percentage) {
                $output[] = '<span class="onsale">-' . $percentage . '%' . '</span>';
            } else {
                $output[] = '<span class="onsale">' . esc_html__('Sale', 'organey') . '</span>';
            }
        }

        if (!$product->is_in_stock()) {
            $output[] = '<span class="out-of-stock">' . esc_html__('Out of stock', 'organey') . '</span>';
        }

        if ($output) {
            echo implode('', $output);
        }
    }
}
add_filter('woocommerce_sale_flash', 'organey_product_label', 10);

if (!function_exists('organey_get_review_counting')) {
    function organey_get_review_counting() {

        global $post;
        $output = array();

        for ($i = 1; $i <= 5; $i++) {
            $args       = array(
                'post_id'    => ($post->ID),
                'meta_query' => array(
                    array(
                        'key'   => 'rating',
                        'value' => $i
                    )
                ),
                'count'      => true
            );
            $output[$i] = get_comments($args);
        }
        return $output;
    }
}

if (!function_exists('organey_button_grid_list_layout')) {
    function organey_button_grid_list_layout() {
        $layout = isset($_GET['layout']) ? $_GET['layout'] : apply_filters('organey_shop_layout', 'grid');

        ?>
        <div class="gridlist-toggle desktop-hide-down">
            <a href="<?php echo esc_url(add_query_arg('layout', 'grid')); ?>"
               class="grid organey-icon-grid <?php echo 'grid' == $layout ? 'active' : ''; ?>"
               title="<?php echo esc_attr__('Grid View', 'organey'); ?>"><span class="screen-reader-text"><?php echo esc_html__('grid button', 'organey'); ?></span></a>
            <a href="<?php echo esc_url(add_query_arg('layout', 'list')); ?>"
               class="list organey-icon-list <?php echo 'list' == $layout ? 'active' : ''; ?>"
               title="<?php echo esc_attr__('List View', 'organey'); ?>"><span class="screen-reader-text"><?php echo esc_html__('list button', 'organey'); ?></span></a>
        </div>
        <?php
    }
}

if (!function_exists('organey_woocommerce_get_product_description')) {
    function organey_woocommerce_get_product_description() {
        global $post;

        $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

        if ($short_description) {
            ?>
            <div class="short-description">
                <?php echo wp_kses_post($short_description); ?>
            </div>
            <?php
        }
    }
}
