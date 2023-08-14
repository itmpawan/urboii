<?php

use Elementor\Controls_Manager;

add_action('elementor/element/icon-list/section_icon_style/before_section_end', function ($element, $args) {
    $element->add_control(
        'padding_icon_list',
        [
            'label'      => esc_html__('Padding', 'organey'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .elementor-icon-list-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $element->add_control(
        'icon_list_border_radius',
        [
            'label'      => esc_html__('Border Radius', 'organey'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .elementor-icon-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $element->add_control(
        'background_icon_list',
        [
            'label'     => esc_html__('Background', 'organey'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .elementor-icon-list-icon' => 'background-color: {{VALUE}};',
            ],
        ]
    );

}, 10, 2);