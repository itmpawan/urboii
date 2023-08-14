<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Organey_Elementor_Search extends Elementor\Widget_Base {
    public function get_name() {
        return 'organey-search';
    }

    public function get_title() {
        return esc_html__('Organey Search Form', 'organey');
    }

    public function get_icon() {
        return 'eicon-site-search';
    }

    public function get_categories() {
        return array('organey-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'search-form-settings',
            [
                'label' => esc_html__('Settings', 'organey'),
                'tab'   => Controls_Manager::TAB_LAYOUT,
            ]
        );

        $this->add_control('search_layout',
            [
                'label'   => esc_html__('Layout', 'organey'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Search Form', 'organey'),
                    'icon'    => esc_html__('Icon', 'organey'),
                ],
            ]
        );

        $this->add_control('search_layout_style',
            [
                'label'   => esc_html__('Style', 'organey'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1' => esc_html__('Style 1', 'organey'),
                    'style-2'    => esc_html__('Style 2', 'organey'),
                ],
                'prefix_class'	=> 'search-organey-layout-',
            ]
        );

        $this -> add_responsive_control(
            'search_layout_alignment',
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
                    '{{WRAPPER}} .elementor-search-form-wrapper' => 'text-align: {{VALUE}};'
                ],
                'condition' => [
                    'search_layout' => 'icon'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'search-form-style',
            [
                'label' => esc_html__('Style Form', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                        'search_layout' => 'default'
                ]
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => esc_html__('Border width', 'organey'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} form input[type=search]' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => esc_html__('Border Radius', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}}  form input[type=search]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => esc_html__('Border Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color_focus',
            [
                'label'     => esc_html__('Border Color Focus', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_form',
            [
                'label'     => esc_html__('Background', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_form',
            [
                'label'     => esc_html__('Color Icon', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .widget_product_search form:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'search-icon-form-style',
            [
                'label' => esc_html__('Style Icon', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_layout' => 'icon'
                ]
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color Icon', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .button-search-popup' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Icon', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .button-search-popup:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_controls_settings();
        $this->add_render_attribute('wrapper', 'class', 'elementor-search-form-wrapper');
        ?>
        <div <?php echo organey_get_render_attribute_string('wrapper', $this); ?>>
            <?php

            if ($settings['search_layout'] === 'icon') {
                organey_header_search_button();
            } else {
                organey_product_search();
            }
            ?>
        </div>
        <?php
    }
}

$widgets_manager->register(new Organey_Elementor_Search());
