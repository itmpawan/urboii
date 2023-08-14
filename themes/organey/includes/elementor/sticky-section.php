<?php

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Widget_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Organey_Sticky_Module {

	public function __construct() {
		add_action( 'elementor/element/section/section_effects/after_section_start', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/section_effects/after_section_start', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'scripts' ] );
	}

	public function scripts() {
		wp_register_script( 'elementor-sticky', get_template_directory_uri() . '/assets/js/vendor/jquery.sticky.js', [ 'jquery' ], ORGANEY_VERSION, true );
		wp_enqueue_script( 'organey-elementor-sticky', get_template_directory_uri() . '/assets/js/vendor/sticky.js', [
			'jquery',
			'elementor-frontend',
			'elementor-sticky'
		], ORGANEY_VERSION, true );
	}

	public function register_controls( Element_Base $element ) {
		$element->add_control(
			'sticky',
			[
				'label'              => esc_html__( 'Sticky', 'organey' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					''       => esc_html__( 'None', 'organey' ),
					'top'    => esc_html__( 'Top', 'organey' ),
					'bottom' => esc_html__( 'Bottom', 'organey' ),
				],
				'separator'          => 'before',
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'sticky_on',
			[
				'label'              => esc_html__( 'Sticky On', 'organey' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'label_block'        => true,
				'default'            => [ 'desktop', 'tablet', 'mobile' ],
				'options'            => [
					'desktop' => esc_html__( 'Desktop', 'organey' ),
					'tablet'  => esc_html__( 'Tablet', 'organey' ),
					'mobile'  => esc_html__( 'Mobile', 'organey' ),
				],
				'condition'          => [
					'sticky!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'sticky_offset',
			[
				'label'              => esc_html__( 'Offset', 'organey' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'min'                => 0,
				'max'                => 500,
				'required'           => true,
				'condition'          => [
					'sticky!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'sticky_effects_offset',
			[
				'label'              => esc_html__( 'Effects Offset', 'organey' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'min'                => 0,
				'max'                => 1000,
				'required'           => true,
				'condition'          => [
					'sticky!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		if ( $element instanceof Widget_Base ) {
			$element->add_control(
				'sticky_parent',
				[
					'label'              => esc_html__( 'Stay In Column', 'organey' ),
					'type'               => Controls_Manager::SWITCHER,
					'condition'          => [
						'sticky!' => '',
					],
					'render_type'        => 'none',
					'frontend_available' => true,
				]
			);
		}

		$element->add_control(
			'sticky_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
	}
}

new Organey_Sticky_Module();
