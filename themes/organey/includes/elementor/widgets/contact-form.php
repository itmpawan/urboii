<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!organey_is_contactform_activated()) {
    return;
}

use Elementor\Controls_Manager;


class Organey_Elementor_ContactForm extends Elementor\Widget_Base {

    public function get_name() {
        return 'organey-contactform';
    }

    public function get_title() {
        return esc_html__('Organey Contact Form', 'organey');
    }

    public function get_categories() {
        return array('organey-addons');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'contactform7',
            [
                'label' => esc_html__('General', 'organey'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $cf7               = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
        $contact_forms[''] = esc_html__('Please select form', 'organey');
        if ($cf7) {
            foreach ($cf7 as $cform) {
                $contact_forms[$cform->ID] = $cform->post_title;
            }
        } else {
            $contact_forms[0] = esc_html__('No contact forms found', 'organey');
        }

        $this->add_control(
            'cf_id',
            [
                'label'   => esc_html__('Select contact form', 'organey'),
                'type'    => Controls_Manager::SELECT,
                'options' => $contact_forms,
                'default' => ''
            ]
        );

        $this->add_control(
            'form_name',
            [
                'label'   => esc_html__('Form name', 'organey'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Contact form', 'organey'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('label_style',
            [
                'label' => esc_html__('Label', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'typo_label',
                'selector' => '{{WRAPPER}} .wpcf7-form label'
            ]
        );

        $this->add_control('color_label',
            [
                'label'     => esc_html__('Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form label' => 'color: {{VALUE}};'
                ]

            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label'      => esc_html__('Margin', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section('input_style',
            [
                'label' => esc_html__('Input', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'typo_input',
                'selector' => '{{WRAPPER}} .wpcf7-form input:not([type="submit"]), {{WRAPPER}} .wpcf7-form textarea'
            ]
        );

        $this->add_control('color_input',
            [
                'label'     => esc_html__('Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"]), {{WRAPPER}} .wpcf7-form textarea' => 'color: {{VALUE}};'
                ]

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'border_input',
                'selector' => '{{WRAPPER}} .wpcf7-form input:not([type="submit"]), {{WRAPPER}} .wpcf7-form textarea'
            ]
        );

        $this->add_control('border_color_input_focus',
            [
                'label'     => esc_html__('Border Color Focus', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"]):focus, {{WRAPPER}} .wpcf7-form textarea:focus' => 'border-color: {{VALUE}};'
                ]

            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"]), {{WRAPPER}} .wpcf7-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label'      => esc_html__('Padding', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"]), {{WRAPPER}} .wpcf7-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_margin',
            [
                'label'      => esc_html__('Margin', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"]), {{WRAPPER}} .wpcf7-form textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('button_style',
            [
                'label' => esc_html__('Button', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'typo_button',
                'selector' => '{{WRAPPER}} .wpcf7-form button, {{WRAPPER}} .wpcf7-form input[type="submit"]'
            ]
        );

        $this->add_control('color_button',
            [
                'label'     => esc_html__('Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form button, {{WRAPPER}} .wpcf7-form input[type="submit"]' => 'color: {{VALUE}};'
                ]

            ]
        );

        $this->add_control('color_button_hover',
            [
                'label'     => esc_html__('Color Hover', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form button:hover, {{WRAPPER}} .wpcf7-form input[type="submit"]:hover' => 'color: {{VALUE}};'
                ]

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'border_button',
                'selector' => '{{WRAPPER}} .wpcf7-form button, {{WRAPPER}} .wpcf7-form input[type="submit"]'
            ]
        );

        $this->add_control('border_color_button_focus',
            [
                'label'     => esc_html__('Border Color Hover', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form button:hover, {{WRAPPER}} .wpcf7-form input[type="submit"]:hover' => 'border-color: {{VALUE}};'
                ]

            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form button, {{WRAPPER}} .wpcf7-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__('Padding', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form button, {{WRAPPER}} .wpcf7-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label'      => esc_html__('Margin', 'organey'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form button, {{WRAPPER}} .wpcf7-form input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!$settings['cf_id']) {
            return;
        }
        $args['id']    = $settings['cf_id'];
        $args['title'] = $settings['form_name'];

        echo organey_do_shortcode('contact-form-7', $args);
    }
}

$widgets_manager->register(new Organey_Elementor_ContactForm());
