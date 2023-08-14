<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Organey_Elementor_Wishlist extends Elementor\Widget_Base {
    public function get_name() {
        return 'organey-wishlist';
    }

    public function get_title() {
        return esc_html__('Organey Wishlist', 'organey');
    }

    public function get_icon() {
        return 'eicon-product-add-to-cart';
    }

    public function get_categories() {
        return array('organey-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'wishlist-icon-style',
            [
                'label' => esc_html__('Icon', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_account_size',
            [
                'label'     => esc_html__('Size Icon', 'organey'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .site-header-wishlist .header-wishlist i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Icon Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-wishlist .header-wishlist:not(:hover) i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Icon Color Hover', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-wishlist .header-wishlist:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this -> add_responsive_control(
            'wishlist_alignment',
            [
                'label'       => esc_html__( 'Alignment', 'organey' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'left',
                'options'     => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'organey' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'organey' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'organey' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'label_block' => false,
                'selectors'   => [
                    '{{WRAPPER}} .site-header-wishlist' => 'text-align: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'wishlist-count-style',
            [
                'label' => esc_html__('Count', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label'     => esc_html__('Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-wishlist .header-wishlist .count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'count_background_color',
            [
                'label'     => esc_html__('Background', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-wishlist .header-wishlist .count' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'show_count',
            [
                'label'        => esc_html__('Hide Count', 'organey'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'hide-count-wishlist-'
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-wishlist-wrapper');
        ?>
        <div <?php echo organey_get_render_attribute_string('wrapper', $this); ?>>
            <?php
            if (function_exists('woosw_init')) {
                add_action('wp_footer', 'organey_wishlist_canvas', 1);
                $key = WPCleverWoosw::get_key();

                ?>
                <div class="site-header-wishlist woosw-check">
                    <a class="header-wishlist" data-toggle="button-side" data-target=".site-wishlist-side" href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)); ?>">
                        <i class="organey-icon-heart"></i>
                        <span class="count">0</span>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
}

$widgets_manager->register(new Organey_Elementor_Wishlist());



