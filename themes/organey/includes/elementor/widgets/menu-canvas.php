<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Organey_Elementor_Menu_Canvas extends Elementor\Widget_Base{

    public function get_name()
    {
        return 'organey-menu-canvas';
    }

    public function get_title()
    {
        return esc_html__('Organey Menu Canvas', 'organey');
    }

    public function get_icon()
    {
        return 'eicon-nav-menu';
    }

    public function get_categories()
    {
        return ['organey-addons'];
    }

    protected function register_controls()
    {
        $this -> start_controls_section(
            'icon-menu_style',
            [
                'label' => esc_html__('Icon','organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_menu_size',
            [
                'label'     => esc_html__( 'Size Icon', 'organey' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_menu_color',
            [
                'label'     => esc_html__( 'Color', 'organey' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_menu_color_hover',
            [
                'label'     => esc_html__( 'Color Hover', 'organey' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        add_action('wp_footer','organey_mobile_nav');
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-canvas-menu-wrapper' );
        ?>
        <div <?php echo organey_get_render_attribute_string('wrapper' , $this); ?>>
            <?php
                organey_mobile_nav_button();
            ?>
        </div>
        <?php
    }

}
$widgets_manager->register(new Organey_Elementor_Menu_Canvas());
