<?php
/**
 * Organey WooCommerce Settings Class
 *
 * @package  organey
 * @since    2.4.3
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Organey_WooCommerce_Settings')) :

    /**
     * The Organey WooCommerce Settings Class
     */
    class Organey_WooCommerce_Settings {

        public function __construct() {
            if (organey_is_elementor_activated()) {
                add_filter('woocommerce_product_data_tabs', array($this, 'settings_product_tabs'));
                add_filter('woocommerce_product_data_panels', array(
                    $this,
                    'settings_options_product_tab_content'
                ), 999);
                add_action('woocommerce_process_product_meta', array($this, 'save_settings_option_fields'));

                add_action('woocommerce_single_product_summary', array($this, 'render_sizechart_button'), 25);
                add_action('wp_footer', array($this, 'render_sizechart_template'), 1);
                add_action('wp_enqueue_scripts', [$this, 'add_css']);
            }
        }

        public function settings_product_tabs($tabs) {

            $tabs['sizechart'] = array(
                'label'    => esc_html__('Organey settings', 'organey'),
                'target'   => 'organey_options',
                'class'    => array(),
                'priority' => 80,
            );

            return $tabs;

        }

        private function check_chart($slug = '') {

            if ($slug) {

                $queried_post = get_page_by_path($slug, OBJECT, 'elementor_library');

                if (isset($queried_post->ID)) {
                    return $queried_post->ID;
                }
            }

            return false;
        }

        public function settings_options_product_tab_content() {

            global $post;

            ?>
            <div id='organey_options' class='panel woocommerce_options_panel'>
                <div class='options_group'><?php

                    $value = get_post_meta($post->ID, '_sizechart_select', true);
                    if (empty($value)) {
                        $value = '';
                    }
                    $options[''] = esc_html__('Select size chart', 'organey');

                    $args = array(
                        'post_type'   => 'elementor_library',
                        'numberposts' => -1,
                        'orderby'     => 'title',
                        'order'       => 'ASC',
                        'tax_query'   => [
                            [
                                'taxonomy'         => 'elementor_library_type',
                                'field'            => 'slug',
                                'terms'            => array('section'),
                                'include_children' => true,
                                'operator'         => 'IN'
                            ]
                        ]
                    );

                    $query1 = get_posts($args);
                    if ($query1) {
                        foreach ($query1 as $post) {
                            $options[$post->post_name] = $post->post_title;
                        }
                    }

                    woocommerce_wp_select(array(
                        'id'      => '_sizechart_select',
                        'label'   => esc_html__('Size chart', 'organey'),
                        'options' => $options,
                        'value'   => $value,
                    ));

                    ?>
                    <p class="form-field form-field-wide wc-customer-custom"><?php echo esc_html__('Size chart will take type of templates is section', 'organey'); ?></p>

                    <?php
                    woocommerce_wp_text_input(array(
                        'id'    => '_video_select',
                        'label' => esc_html__('Url Video', 'organey'),
                    ));
                    woocommerce_wp_textarea_input(array(
                        'id'    => '_extra_info',
                        'label' => esc_html__('Extra info', 'organey'),
                    ));
                    ?>
                </div>

            </div>
            <?php

        }

        public function save_settings_option_fields($post_id) {
            if (isset($_POST['_sizechart_select'])) {
                update_post_meta($post_id, '_sizechart_select', esc_attr($_POST['_sizechart_select']));
            }
            if (isset($_POST['_video_select'])) {
                update_post_meta($post_id, '_video_select', esc_attr($_POST['_video_select']));
            }
            if (isset($_POST['_extra_info'])) {
                update_post_meta($post_id, '_extra_info', esc_attr($_POST['_extra_info']));
            }
        }

        public function add_css() {
            global $post;
            if (!is_product()) {
                return;
            }
            $slug     = get_post_meta($post->ID, '_sizechart_select', true);
            $chart_id = $this->check_chart($slug);
            if (!empty($slug) && $chart_id) {
                Elementor\Core\Files\CSS\Post::create($chart_id)->enqueue();
            }
        }

        public function render_sizechart_button() {
            global $post;
            if (!is_product()) {
                return;
            }
            $slug     = get_post_meta($post->ID, '_sizechart_select', true);
            $chart_id = $this->check_chart($slug);
            if (!empty($slug) && $chart_id) {
                echo '<a href="#" class="sizechart-button">' . esc_html__('Size Guide', 'organey') . '</a>';
            }
        }

        public function render_sizechart_template() {
            global $post;
            if (!is_product()) {
                return;
            }
            $slug     = get_post_meta($post->ID, '_sizechart_select', true);
            $chart_id = $this->check_chart($slug);
            if (!empty($slug) && $chart_id) {
                echo '<div class="sizechart-popup"><a href="#" class="sizechart-close"><i class="organey-icon-times-circle"></i></a>' . Elementor\Plugin::instance()->frontend->get_builder_content($chart_id) . '</div><div class="sizechart-overlay"></div>';
            }
        }
    }

    return new Organey_WooCommerce_Settings();

endif;
