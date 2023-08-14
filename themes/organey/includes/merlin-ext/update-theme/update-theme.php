<?php

class Organey_Extension_Update_Theme {
	private static $instance;

	/**
	 * @return Organey_Extension_Update_Theme
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function __construct() {
		add_filter( 'pre_set_site_transient_update_themes', [ $this, 'update_theme' ] );
	}

	public function check_version($version) {
		$theme_data = wp_get_theme();
		return version_compare($version, $theme_data->Version, '>');
	}

	private function check_update() {
		$license = organey_get_theme_option( 'theme_license' );
		$url     = 'https://leebrosus.com/checkupdate.php?action=check&license=' . $license . '&theme_id=33325838';

		// Send the request
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response_body = wp_remote_retrieve_body( $response );
		$result        = json_decode( $response_body, true );

		return $result;
	}

	public function update_theme( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		$info = $this->check_update();
		if ( $info['has_update'] ) {
			if($this->check_version($info['version'])){
				$theme_data = wp_get_theme();
				$theme_slug = $theme_data->get_template();
				$transient->response[$theme_slug] = array(
					'new_version' => $info['version'],
					'package' => $info['url'],
				);
			}
		}

		return $transient;
	}
}

Organey_Extension_Update_Theme::get_instance();
