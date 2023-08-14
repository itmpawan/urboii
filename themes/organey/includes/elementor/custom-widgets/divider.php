<?php

use Elementor\Controls_Manager;

add_action('elementor/element/divider/section_divider_style/before_section_end', function ($element, $args) {
    $element->add_control(
        'gap_1',
        [
            'label'     => esc_html__('Position', 'organey'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-divider' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
}, 10, 2);