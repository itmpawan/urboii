<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! organey_is_revslider_activated() ) {
	return;
}

use Elementor\Controls_Manager;

class Organey_Elementor_RevSlider extends Elementor\Widget_Base {

	public function get_name() {
		return 'organey-revslider';
	}

	public function get_title() {
		return esc_html__( 'Organey Revolution Slider', 'organey' );
	}

	public function get_categories() {
		return array( 'organey-addons' );
	}

	public function get_icon() {
		return 'organey-icon-sync';
	}


	protected function register_controls() {
		$this->start_controls_section(
			'rev_slider',
			[
				'label' => esc_html__( 'General', 'organey' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$slider     = new RevSlider();
		$arrSliders = $slider->getArrSliders();

		$revsliders = array();
		if ( $arrSliders ) {
			foreach ( $arrSliders as $slider ) {
				/** @var $slider RevSlider */
				$revsliders[ $slider->getAlias() ] = $slider->getTitle();
			}
		} else {
			$revsliders[0] = esc_html__( 'No sliders found', 'organey' );
		}

		$this->add_control(
			'rev_alias',
			[
				'label'   => esc_html__( 'Revolution Slider', 'organey' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $revsliders,
				'default' => ''
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! $settings['rev_alias'] ) {
			return;
		}
		echo apply_filters( 'organey_revslider_shortcode', do_shortcode( '[rev_slider ' . $settings['rev_alias'] . ']' ) );
	}
}

$widgets_manager->register( new Organey_Elementor_RevSlider() );
