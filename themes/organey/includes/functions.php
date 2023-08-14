<?php
if ( ! function_exists( 'organey_get_theme_option' ) ) {
	function organey_get_theme_option( $option_name, $default = false ) {

		if ( $option = get_option( 'organey_options_' . $option_name ) ) {
			$default = $option;
		}

		return $default;
	}
}


if (!function_exists('organey_set_theme_option')) {
	function organey_set_theme_option($option_name, $value = false) {
		update_option('organey_options_' . $option_name, $value);
	}
}

if ( ! function_exists( 'organey_is_product_archive' ) ) {
	function organey_is_product_archive() {
		if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'organey_is_wpml_activated' ) ) {
	function organey_is_wpml_activated() {
		return class_exists( 'SitePress' ) ? true : false;
	}
}

if ( ! function_exists( 'organey_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function organey_is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}

if ( ! function_exists( 'organey_is_woocommerce_extension_activated' ) ) {

	function organey_is_woocommerce_extension_activated( $extension = 'WC_Bookings' ) {
		if ( $extension == 'YITH_WCQV' ) {
			return class_exists( $extension ) && class_exists( 'YITH_WCQV_Frontend' ) ? true : false;
		}

		return class_exists( $extension ) ? true : false;
	}
}

if ( ! function_exists( 'organey_is_elementor_pro_activated' ) ) {
	function organey_is_elementor_pro_activated() {
		return function_exists( 'elementor_pro_load_plugin' ) ? true : false;
	}
}

if ( ! function_exists( 'organey_is_contactform_activated' ) ) {
	function organey_is_contactform_activated() {
		return class_exists( 'WPCF7' );
	}
}

/**
 * Call a shortcode function by tag name.
 *
 * @param string $tag The shortcode whose function to call.
 * @param array $atts The attributes to pass to the shortcode function. Optional.
 * @param array $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 * @since  1.4.6
 *
 */
function organey_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}


if ( ! function_exists( 'organey_elementor_get_render_attribute_string' ) ) {
	function organey_elementor_get_render_attribute_string( $element, $obj ) {
		return $obj->get_render_attribute_string( $element );
	}
}

if ( ! function_exists( 'organey_elementor_parse_text_editor' ) ) {
	function organey_elementor_parse_text_editor( $content, $obj ) {
		$content = apply_filters( 'widget_text', $content, $obj->get_settings() );

		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
		$content = wptexturize( $content );

		if ( $GLOBALS['wp_embed'] instanceof \WP_Embed ) {
			$content = $GLOBALS['wp_embed']->autoembed( $content );
		}

		return $content;
	}
}

if ( ! function_exists( 'organey_elementor_get_strftime' ) ) {
	function organey_elementor_get_strftime( $instance, $obj ) {
		$string = '';
		if ( $instance['show_days'] ) {
			$string .= $obj->render_countdown_item( $instance, 'label_days', 'elementor-countdown-days' );
		}
		if ( $instance['show_hours'] ) {
			$string .= $obj->render_countdown_item( $instance, 'label_hours', 'elementor-countdown-hours' );
		}
		if ( $instance['show_minutes'] ) {
			$string .= $obj->render_countdown_item( $instance, 'label_minutes', 'elementor-countdown-minutes' );
		}
		if ( $instance['show_seconds'] ) {
			$string .= $obj->render_countdown_item( $instance, 'label_seconds', 'elementor-countdown-seconds' );
		}

		return $string;
	}
}

if ( ! function_exists( 'organey_elementor_get_page_setting' ) ) {
	function organey_elementor_get_page_setting( $setting, $default = '' ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc ) {
				$default = $current_doc->get_settings( $setting );
			}
		}

		return $default;
	}
}

if ( ! function_exists( 'organey_get_render_attribute_string' ) ) {
	function organey_get_render_attribute_string( $id, $obj ) {
		return $obj->get_render_attribute_string( $id );
	}
}

if ( ! function_exists( 'organey_is_elementor_activated' ) ) {
	function organey_is_elementor_activated() {
		return function_exists( 'elementor_load_plugin_textdomain' ) ? true : false;
	}
}

if ( ! function_exists( 'organey_is_mailchimp_activated' ) ) {
	function organey_is_mailchimp_activated() {
		return function_exists( '_mc4wp_load_plugin' );
	}
}


if ( ! function_exists( 'organey_is_revslider_activated' ) ) {
	function organey_is_revslider_activated() {
		return class_exists( 'RevSliderBase' );
	}
}

if ( ! function_exists( 'organey_sanitize_editor' ) ) {
	function organey_sanitize_editor( $value ) {
		return force_balance_tags( apply_filters( 'the_content', $value ) );
	}
}


if ( ! function_exists( 'organey_render_skeleton' ) ) {
	function organey_render_skeleton( $classes = '', $number = 1, $style = [], $echo = 1, $content = '' ) {
		$html  = '';
		if (organey_is_elementor_activated()) {
			if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
				if ($echo) {
					printf('%s', ob_get_clean());
				} else {
					return sprintf('%s', ob_get_clean());
				}
				return '';
			}
		}
		if(!$content){
			$content = ob_get_clean();
		}
		$style = is_array( $style ) ? $style : [];
		$css   = '';
		foreach ( $style as $key => $value ) {
			$css .= $key . ':' . $value . ';';
		}
		for ( $i = 1; $i <= $number; $i ++ ) {
			$html .= '<div ' . ( $css ? 'style="'.$css.'"' : '' ) . ' class="skeleton-item ' . esc_attr( $classes ) . '"></div>';
		}
		if ( $echo ) {
			printf( '<div class="skeleton-body"><script type="text/template">%s</script>%s</div>', json_encode( $content ), $html );
		} else {
			return sprintf( '<div class="skeleton-body"><script type="text/template">%s</script>%s</div>', json_encode( $content ), $html );
		}
	}
}
