<?php
if (!class_exists('Organey_Theme_WooCommerce')):
    class Organey_Theme_WooCommerce {

        public  $list_shortcodes;
        private $prefix = 'remove';

        public function __construct() {
            add_action('init', [$this, 'override_shortcode']);
            // Remove Shop Title
            add_filter('woocommerce_show_page_title', '__return_false');
            // Remove All Default Style
            add_filter('woocommerce_enqueue_styles', '__return_empty_array');
            add_action('wp_enqueue_scripts', array($this, 'scripts'), 20);

            add_filter('body_class', array($this, 'body_class'));
            add_action('widgets_init', array($this, 'widgets_init'));
            add_filter('organey_theme_sidebar', array($this, 'set_sidebar'), 20);

            add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
            add_filter('woocommerce_product_thumbnails_columns', array($this, 'thumbnail_columns'));

            add_filter('organey_register_nav_menus', [$this, 'add_location_menu']);
            add_filter('wp_nav_menu_items', [$this, 'add_extra_item_to_nav_menu'], 10, 2);

            add_filter('woocommerce_breadcrumb_defaults', [$this, 'breadcrumb_args']);

            add_action('woocommerce_product_options_pricing', array($this, 'add_deal_fields'));
            add_action('save_post', array($this, 'save_product_data'));

            add_action('woocommerce_before_template_part', [
                $this,
                'action_woocommerce_before_template_part'
            ], 10, 4);
            add_action('woocommerce_after_template_part', [$this, 'action_woocommerce_after_template_part'], 10, 4);
            add_filter('woocommerce_cross_sells_columns', [$this, 'woocommerce_cross_sells_columns']);
            add_filter('woocommerce_single_product_image_gallery_classes', function ($wrapper_classes) {
                $wrapper_classes[] = 'woocommerce-product-gallery-' . organey_get_theme_option('single_product_gallery_layout', 'horizontal');

                return $wrapper_classes;
            });

            if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
                add_filter('loop_shop_per_page', array($this, 'products_per_page'));
            }

            $this->list_shortcodes = array(
                'recent_products',
                'sale_products',
                'best_selling_products',
                'top_rated_products',
                'featured_products',
                'related_products',
                'product_category',
                'products',
            );
            $this->init_shortcodes();

	        // Remove Quick Table Compare in Detail
	        if(class_exists('WPCleverWoosc')){
		        remove_action( 'woocommerce_after_single_product_summary', array( WPCleverWoosc::instance(), 'show_quick_table' ), 19 );
		        remove_action( 'woocommerce_after_single_product_summary', array( WPCleverWoosc::instance(), 'show_quick_table' ), 20 );
		        remove_action( 'woocommerce_after_single_product_summary', array( WPCleverWoosc::instance(), 'show_quick_table' ), 21 );
	        }
        }

        /**
         * Add the sale quantity field
         */
        public function add_deal_fields() {
            global $thepostid;

            $quantity     = get_post_meta($thepostid, '_deal_quantity', true);
            $sales_counts = get_post_meta($thepostid, '_deal_sales_counts', true);
            $sales_counts = intval($sales_counts);
            $min          = $sales_counts > 0 ? $sales_counts + 1 : 0;
            ?>

            <p class="form-field _deal_quantity_field">
                <label for="_sale_quantity"><?php esc_html_e('Sale quantity', 'organey') ?></label>
                <?php echo wc_help_tip(__('Set this quantity will make the product to be a deal. The sale will end when this quantity is sold out.', 'organey')); ?>
                <input type="number" min="<?php echo esc_attr($min); ?>" class="short" name="_deal_quantity"
                       id="_deal_quantity" value="<?php echo esc_attr($quantity) ?>">

                <?php
                if ($quantity > 0) {
                    echo '<span class="deal-sold-counts"><strong>' . sprintf(_n('%s product is sold', '%s products are sold', max(1, $sales_counts), 'organey'), $sales_counts) . '</strong></span>';
                }
                ?>
            </p>

            <?php
        }

        /**
         * Save product data
         *
         * @param int $post_id
         */
        public function save_product_data($post_id) {
            if ('product' !== get_post_type($post_id)) {
                return;
            }

            if (isset($_POST['_deal_quantity'])) {
                $current_sales = get_post_meta($post_id, '_deal_sales_counts', true);

                // Reset sales counts if set the qty to 0
                if ($_POST['_deal_quantity'] <= 0) {
                    update_post_meta($post_id, '_deal_sales_counts', 0);
                    update_post_meta($post_id, '_deal_quantity', '');
                } elseif ($_POST['_deal_quantity'] < $current_sales) {
                    $this->end_deal($post_id);
                } else {
                    update_post_meta($post_id, '_deal_quantity', wc_clean($_POST['_deal_quantity']));
                }
            } else {
                // Reset sales counts and qty setting
                update_post_meta($post_id, '_deal_sales_counts', 0);
                update_post_meta($post_id, '_deal_quantity', '');
            }
        }

        /**
         * Remove deal data of a product.
         * Remove sale price
         * Remove sale schedule dates
         * Remove sale quantity
         * Reset sales counts
         *
         * @param int $post_id
         *
         * @return void
         */
        public function end_deal($post_id) {
            update_post_meta($post_id, '_deal_sales_counts', 0);
            update_post_meta($post_id, '_deal_quantity', '');

            // Remove sale price
            $product       = wc_get_product($post_id);
            $regular_price = $product->get_regular_price();
            $product->set_price($regular_price);
            $product->set_sale_price('');
            $product->set_date_on_sale_to('');
            $product->set_date_on_sale_from('');
            $product->save();

            delete_transient('wc_products_onsale');
        }

        public function woocommerce_cross_sells_columns() {
            return 4;
        }

        public function action_woocommerce_before_template_part($template_name, $template_path, $located, $args) {
            $product_style = organey_get_theme_option('wocommerce_block_style', 0);
            if ($product_style != 0 && ($template_name == 'single-product/up-sells.php' || $template_name == 'single-product/related.php' || $template_name == 'cart/cross-sells.php')) {
                $template_custom = 'content-product-' . $product_style . '.php';
                add_filter('wc_get_template_part', function ($template, $slug, $name) use ($template_custom) {
                    if ($slug == 'content' && $name == 'product') {
                        return get_theme_file_path('woocommerce/' . $template_custom);
                    } else {
                        return $template;
                    }
                }, 10, 3);
            }
        }

        public function action_woocommerce_after_template_part($template_name, $template_path, $located, $args) {
            $product_style = organey_get_theme_option('wocommerce_block_style', 0);
            if ($product_style != 0 && ($template_name == 'single-product/up-sells.php' || $template_name == 'single-product/related.php' || $template_name == 'cart/cross-sells.php')) {
                add_filter('wc_get_template_part', function ($template, $slug, $name) {
                    if ($slug == 'content' && $name == 'product') {
                        return get_theme_file_path('woocommerce/content-product.php');
                    } else {
                        return $template;
                    }
                }, 10, 3);
            }
        }

        public function override_shortcode() {
            global $shortcode_tags;
            $shortcode_tags['woosw_list'] = [$this, 'wishlist_list'];
        }

        public function wishlist_list($atts, $content) {
            ob_start();
            include get_theme_file_path('woocommerce/wishlist.php');

            return ob_get_clean();
        }

        private function check_clever_activate() {
            add_filter('woosc_button_position_archive', '__return_false');

            if (is_admin() && current_user_can('administrator')) {

                $check = get_option('clever_plugin_first_activate', false);
                if (!$check) {

                    update_option('woosq_button_position', '0');
                    update_option('_wooscp_button_archive', '0');
                    update_option('woosw_button_position_archive', '0');
                    update_option('woosw_button_position_single', '0');

                    update_option('clever_plugin_first_activate', true);
                }
            }
        }

        public function breadcrumb_args($args) {
            $args['delimiter'] = '<i aria-hidden="true" class="organey-icon-angle-right"></i>';

            return $args;
        }

        public function body_class($classes) {
            $classes[] = 'woocommerce-active';

            // Remove `no-wc-breadcrumb` body class.
            $key = array_search('no-wc-breadcrumb', $classes, true);

            if (false !== $key) {
                unset($classes[$key]);
            }

            $sidebar = organey_get_theme_option('woocommerce_archive_sidebar', 'left');
            $layout  = organey_get_theme_option('woocommerce_archive_layout', 'default');

            if (organey_is_product_archive()) {
                $classes[] = 'organey-archive-product';
                if (is_active_sidebar('sidebar-woocommerce-shop')) {
                    if ($layout == 'default') {
                        $classes[] = 'organey-content-has-sidebar';
                    } else {
                        $classes = array_diff($classes, array(
                            'organey-content-has-sidebar',
                        ));
                        if ($layout == 'canvas') {
                            $classes[] = 'shop_filter_canvas';
                        }
                    }

                    if ($sidebar == 'left') {
                        $classes[] = 'organey-sidebar-left';
                    } else {
                        $classes = array_diff($classes, array(
                            'organey-sidebar-left',
                        ));
                    }
                } else {
                    $key = array_search('organey-content-has-sidebar', $classes, true);
                    if (false !== $key) {
                        unset($classes[$key]);
                    }
                }
            }

            if (is_product()) {
                $classes[] = 'single-product-' . organey_get_theme_option('single_product_gallery_layout', 'horizontal');
                if (is_active_sidebar('sidebar-woocommerce-detail')) {
                    $classes[] = 'organey-content-has-sidebar';
                }
            }

            return $classes;
        }

        public function widgets_init() {
            register_sidebar(array(
                'name'          => esc_html__('WooCommerce Shop', 'organey'),
                'id'            => 'sidebar-woocommerce-shop',
                'description'   => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'organey'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span class="gamma widget-title">',
                'after_title'   => '</span>',
            ));
            register_sidebar(array(
                'name'          => esc_html__('WooCommerce Detail', 'organey'),
                'id'            => 'sidebar-woocommerce-detail',
                'description'   => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'organey'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span class="gamma widget-title">',
                'after_title'   => '</span>',
            ));
        }

        public function set_sidebar($name) {
            $layout = organey_get_theme_option('woocommerce_archive_layout', 'default');
            if (organey_is_product_archive()) {
                if (is_active_sidebar('sidebar-woocommerce-shop') && $layout == 'default') {
                    $name = 'sidebar-woocommerce-shop';
                } else {
                    $name = '';
                }
            }
            if (is_product()) {
                if (is_active_sidebar('sidebar-woocommerce-detail')) {
                    $name = 'sidebar-woocommerce-detail';
                } else {
                    $name = '';
                }
            }


            return $name;
        }

        public function scripts() {
            wp_enqueue_style('organey-woocommerce-css', get_theme_file_uri('woocommerce.css'), false, ORGANEY_VERSION);
            wp_style_add_data('organey-woocommerce-css', 'rtl', 'replace');

            // remove Css Quickview Default
            wp_dequeue_style('yith-quick-view');
            if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
                wp_enqueue_style('organey-woocommerce-legacy', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce-legacy.css', array(), ORGANEY_VERSION);
                wp_style_add_data('organey-woocommerce-legacy', 'rtl', 'replace');
            }

            wp_enqueue_script('organey-input-quantity', get_template_directory_uri() . '/assets/js/woocommerce/quantity.js', array('jquery'), ORGANEY_VERSION, true);
            wp_enqueue_script('organey-cart-canvas', get_template_directory_uri() . '/assets/js/woocommerce/cart-canvas.js', array('jquery'), ORGANEY_VERSION, true);

            if (organey_is_product_archive()) {
                wp_enqueue_script('organey-products-ajax-shop', get_template_directory_uri() . '/assets/js/woocommerce/ajax-shop.js', array('jquery'), ORGANEY_VERSION, true);

                if(is_active_sidebar('sidebar-woocommerce-shop')){
                    wp_enqueue_script('organey-off-canvas', get_template_directory_uri() . '/assets/js/woocommerce/off-canvas.js', array(), ORGANEY_VERSION, true);
                }
            }

            wp_enqueue_script('organey-products', get_template_directory_uri() . '/assets/js/woocommerce/main.js', array(
                'jquery',
                'tooltipster'
            ), ORGANEY_VERSION, true);

            wp_enqueue_script('organey-products-ajax-search', get_template_directory_uri() . '/assets/js/woocommerce/product-ajax-search.js', array(
                'jquery',
                'tooltipster'
            ), ORGANEY_VERSION, true);

            if (is_product()) {
                wp_enqueue_script('slick');
                wp_enqueue_script('magnific-popup');
                wp_enqueue_script('organey-single-product', get_template_directory_uri() . '/assets/js/woocommerce/single.js', array(
                    'jquery',
                    'sticky-kit',
                    'spritespin'
                ), ORGANEY_VERSION, true);
            }

            if (is_cart()) {
                wp_enqueue_script('organey-single-product', get_template_directory_uri() . '/assets/js/woocommerce/cart.js', array('jquery'), ORGANEY_VERSION, true);
            }

            if (function_exists('woosw_init')) {
                wp_enqueue_script('organey-wishlist', get_template_directory_uri() . '/assets/js/woocommerce/wishlist.js', array('jquery'), ORGANEY_VERSION, true);
            }
        }

        public function products_per_page() {
            return intval(apply_filters('organey_products_per_page', 12));
        }

        public function add_location_menu($locations) {
            $locations['my-account'] = esc_html__('My Account', 'organey');

            return $locations;
        }

        public function add_extra_item_to_nav_menu($items, $args) {
            if ($args->theme_location == 'my-account') {
                $items .= '<li><a href="' . esc_url(wp_logout_url(home_url())) . '">' . esc_html__("Logout", 'organey') . '</a></li>';
            }

            return $items;
        }

        private function init_shortcodes() {
            foreach ($this->list_shortcodes as $shortcode) {
                add_filter('shortcode_atts_' . $shortcode, array($this, 'set_shortcode_attributes'), 10, 3);

                add_action('woocommerce_shortcode_before_' . $shortcode . '_loop', array(
                    $this,
                    'shortcode_loop_start'
                ));
                add_action('woocommerce_shortcode_after_' . $shortcode . '_loop', array(
                    $this,
                    'shortcode_loop_end'
                ));
            }
            add_filter('woocommerce_shortcode_products_query', array($this, 'set_shortcode_products_query'), 10, 3);
        }

        public function set_shortcode_products_query($query_args, $attributes, $type) {
            $query_args = $this->set_brand_query_args($query_args, $attributes);
            return $query_args;
        }

        public function set_shortcode_attributes($out, $pairs, $atts) {
            $out = wp_parse_args($atts, $out);

            return $out;
        }

        public function shortcode_loop_end($atts = array()) {

            if (isset($atts['style'])) {
                if ($atts['style'] !== '') {

                    add_filter('wc_get_template_part', function ($template, $slug, $name) {
                        if ($slug == 'content' && $name == 'product') {
                            return get_theme_file_path('woocommerce/content-product.php');
                        } else {
                            return $template;
                        }
                    }, 10, 3);
                }
            }

            if (isset($atts['product_layout']) && $atts['product_layout'] === 'carousel') {
                echo '</div>';
            }
        }

        public function shortcode_loop_start($atts = array()) {

            if (isset($atts['style'])) {
                if ($atts['style'] !== '') {
                    $template_custom = 'content-product-' . $atts['style'] . '.php';
                    add_filter('wc_get_template_part', function ($template, $slug, $name) use ($template_custom) {
                        if ($slug == 'content' && $name == 'product') {
                            return get_theme_file_path('woocommerce/' . $template_custom);
                        } else {
                            return $template;
                        }
                    }, 10, 3);
                }
            }

            if (isset($atts['product_layout']) && $atts['product_layout'] === 'carousel') {
                echo '<div class="woocommerce-carousel" data-settings=\'' . $atts['carousel_settings'] . '\'>';
            }
        }

        protected function set_brand_query_args($query_args, $attributes) {
            if (!empty($attributes['brands'])) {
                $brands = array_map('sanitize_title', explode(',', $attributes['brands']));
                $field  = 'slug';

                if (is_numeric($brands[0])) {
                    $field  = 'term_id';
                    $brands = array_map('absint', $brands);
                    // Check numeric slugs.
                    foreach ($brands as $brand) {
                        $the_cat = get_term_by('slug', $brand, 'product_brand');
                        if (false !== $the_cat) {
                            $brands[] = $the_cat->term_id;
                        }
                    }
                }

                $query_args['tax_query'][] = array(
                    'taxonomy'         => 'product_brand',
                    'terms'            => $brands,
                    'field'            => $field,
                    'operator'         => $attributes['brands_operator'],

                    /*
                     * When cat_operator is AND, the children categories should be excluded,
                     * as only products belonging to all the children categories would be selected.
                     */
                    'include_children' => 'AND' === $attributes['brands_operator'] ? false : true,
                );
            }

            return $query_args;
        }

        public function related_products_args($args) {
            $product_items = 4;
            if (is_active_sidebar('sidebar-woocommerce-detail')) {
                $product_items = 3;
            }

            $args = apply_filters(
                'organey_related_products_args', array(
                    'posts_per_page' => $product_items,
                    'columns'        => $product_items,
                )
            );

            return $args;
        }

        public function thumbnail_columns() {
            $columns = organey_get_theme_option('single_product_gallery_column', 4);

            if (organey_get_theme_option('single_product_gallery_layout', 'horizontal') == 'vertical') {
                $columns = 1;
            }

            return intval(apply_filters('organey_product_thumbnail_columns', $columns));
        }
    }
endif;

return new Organey_Theme_WooCommerce();
