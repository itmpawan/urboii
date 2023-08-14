<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!organey_is_woocommerce_activated()) {
    return;
}


use Elementor\Controls_Manager;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Organey_Elementor_Widget_Products_Single extends \Elementor\Widget_Base {


    public function get_categories() {
        return array('organey-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'organey-products-single';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Single Products', 'organey');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Settings', 'organey'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label'       => esc_html__('Product Name', 'organey'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_products_id(),
                'multiple'    => false,
            ]
        );


        $this->end_controls_section();

    }

    protected function get_products_id() {
        $args     = array(
            'limit' => -1,
        );
        $products = wc_get_products($args);
        $results  = array();
        if (!is_wp_error($products)) {
            foreach ($products as $product) {
                $results[$product->get_id()] = $product->get_name();
            }
        }

        return $results;

    }


    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        global $post, $product;

        add_action('organey_product_single_summary', 'woocommerce_template_loop_product_title', 5);
        add_action('organey_product_single_summary', 'organey_woocommerce_get_product_short_description', 5);
        add_action('organey_product_single_summary', 'woocommerce_template_single_price', 15);
        add_action('organey_product_single_summary', 'woocommerce_template_loop_add_to_cart', 20);
        add_action('organey_product_single_summary', 'organey_woocommerce_time_sale', 20);
        remove_action('woocommerce_after_add_to_cart_button', 'organey_wishlist_button', 20);
        $settings   = $this->get_settings_for_display();
        $product_id = absint($settings['product_id']);
        $product    = wc_get_product($product_id);

        if ($product) {
            $post = get_post($product_id);
            setup_postdata($post);

            ?>
            <div class="woocommerce single-product">
                <div <?php wc_product_class('organey-elementor-single-product', $product); ?>>
                    <div class="thumbnails">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="menu-thumb">
                            <?php echo wp_kses_post( $product->get_image('full') ); ?>
                        </a>
                    </div>
                    <div class="summary entry-summary">
                        <div class="summary-content">
                            <?php do_action('organey_product_single_summary', $product); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            wp_reset_postdata();
        } else {
            echo esc_html__('Please choose product to display !', 'organey');
        }
    }


}

$widgets_manager->register(new Organey_Elementor_Widget_Products_Single());
