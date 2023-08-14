<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!organey_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;

class Organey_Elementor_Header_Cart extends Elementor\Widget_Base {
    public function get_name() {
        return 'organey-header-cart';
    }

    public function get_title() {
        return esc_html__('Organey Header Cart', 'organey');
    }

    public function get_icon() {
        return 'eicon-cart';
    }

    public function get_categories() {
        return array('organey-addons');
    }

    protected function register_controls() {


        $this->start_controls_section(
            'cart_icon_style',
            [
                'label' => esc_html__('Icon', 'organey'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this -> add_responsive_control(
            'cart_alignment',
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
                    '{{WRAPPER}} .site-header-cart' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_cart_size',
            [
                'label' => esc_html__('Size Icon', 'organey'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .site-header-cart .cart-contents i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'organey'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-cart .cart-contents i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'organey'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-cart .cart-contents:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_count_style',
            [
                'label' => esc_html__('Count', 'organey'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' => esc_html__( 'Hide Price', 'organey' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class'	=> 'hide-price-cart-'
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => esc_html__( 'Hide Count', 'organey' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class'	=> 'hide-count-cart-'
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => esc_html__('Color Count', 'organey'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-cart .cart-contents .count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__('Color Price', 'organey'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-cart .cart-contents .amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        ?>
        <div class="site-header-cart menu">
            <?php organey_cart_link(); ?>
            <?php
            if (!apply_filters('woocommerce_widget_cart_is_hidden', is_cart() || is_checkout())) {
                add_action('wp_footer', 'organey_header_cart_side');
            }
            ?>
        </div>
        <?php

    }
}

$widgets_manager->register(new Organey_Elementor_Header_Cart());



