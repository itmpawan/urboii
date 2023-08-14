<?php

class Organey_Merlin_Config {

	private $wizard;

	public function __construct() {
		$this->init();
		add_filter( 'merlin_import_files', [ $this, 'import_files' ] );
		add_action( 'merlin_after_all_import', [ $this, 'after_import_setup' ], 10, 1 );
		add_filter( 'merlin_generate_child_functions_php', [ $this, 'render_child_functions_php' ] );

		add_action( 'import_start', function () {
			add_filter( 'wxr_importer.pre_process.post_meta', [ $this, 'fiximport_elementor' ], 10, 1 );
		} );
		// Setup HF
		if ( function_exists( 'hfe_header_enabled' ) ) {
			add_filter( 'hfe_settings_tabs', [ $this, 'setup_hf_theme' ] );
			add_action( 'admin_menu', [ $this, 'register_setuphf_page' ] );
			add_action( 'admin_post_custom_setup_hf', [ $this, 'action_setup_hf' ] );
		}

		// Custom
		add_filter( 'merlin_get_base_content', [ $this, 'setup_melin_content' ], 10, 2 );
		add_filter( 'organey_custom_import_data_info', [ $this, 'get_custom_import_data_info' ] );
		add_filter( 'organey_get_import_steps_text', [ $this, 'get_import_steps_text' ] );
	}

	public function get_import_steps_text( $list ) {
		$list['all-page'] = esc_html__( 'Pages + Menu', 'organey' );

		return $list;
	}

	public function get_custom_import_data_info( $import_data ) {
		$import_data = array(
			'content'       => false,
			'all-page'      => true,
			'homepage'      => false,
			'blog'          => true,
			'woocommerce'   => true,
			'header-footer' => true,
			'widgets'       => false,
			'options'       => false,
			'sliders'       => false,
			'redux'         => false,
			'after_import'  => false,
		);

		return $import_data;
	}

	public function setup_melin_content( $content, $merlin ) {
		$content['header-footer'] = array(
			'title'            => esc_html__( 'Header Footer Builder', 'organey' ),
			'description'      => esc_html__( 'Install hf.xml', 'organey' ),
			'pending'          => esc_html__( 'Pending', 'organey' ),
			'installing'       => esc_html__( 'Installing', 'organey' ),
			'success'          => esc_html__( 'Success', 'organey' ),
			'install_callback' => array( $merlin->importer, 'import' ),
			'checked'          => $merlin->is_possible_upgrade() ? 0 : 1,
			'data'             => get_theme_file_path( 'dummy-data/hf.xml' ),
		);

		$content['woocommerce'] = array(
			'title'            => esc_html__( 'Woocommerce Product Sample', 'organey' ),
			'description'      => esc_html__( 'Install woocommerce.xml', 'organey' ),
			'pending'          => esc_html__( 'Pending', 'organey' ),
			'installing'       => esc_html__( 'Installing', 'organey' ),
			'success'          => esc_html__( 'Success', 'organey' ),
			'install_callback' => array( $merlin->importer, 'import' ),
			'checked'          => $merlin->is_possible_upgrade() ? 0 : 1,
			'data'             => get_theme_file_path( 'dummy-data/woocommerce.xml' ),
		);

		$content['blog'] = array(
			'title'            => esc_html__( 'Woocommerce Product Sample', 'organey' ),
			'description'      => esc_html__( 'Install woocommerce.xml', 'organey' ),
			'pending'          => esc_html__( 'Pending', 'organey' ),
			'installing'       => esc_html__( 'Installing', 'organey' ),
			'success'          => esc_html__( 'Success', 'organey' ),
			'install_callback' => array( $merlin->importer, 'import' ),
			'checked'          => $merlin->is_possible_upgrade() ? 0 : 1,
			'data'             => get_theme_file_path( 'dummy-data/blog.xml' ),
		);

		$content['all-page'] = array(
			'title'            => esc_html__( 'All Pages + Menu', 'organey' ),
			'description'      => esc_html__( 'Install allpages.xml', 'organey' ),
			'pending'          => esc_html__( 'Pending', 'organey' ),
			'installing'       => esc_html__( 'Installing', 'organey' ),
			'success'          => esc_html__( 'Success', 'organey' ),
			'install_callback' => array( $merlin->importer, 'import' ),
			'checked'          => $merlin->is_possible_upgrade() ? 0 : 1,
			'data'             => get_theme_file_path( 'dummy-data/allpages.xml' ),
		);

		return $content;
	}

	public function import_files(){
            return array(
            array(
                'import_file_name'           => 'home 1',
                'home'                       => 'home-1',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-1.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-1/slider-1.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_1.jpg'),
            ),

            array(
                'import_file_name'           => 'home 10',
                'home'                       => 'home-10',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-10.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-10/slider-2.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_10.jpg'),
            ),

            array(
                'import_file_name'           => 'home 2',
                'home'                       => 'home-2',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-2.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-2/slider-1.zip',
                'import_more_revslider_file_url' => ['https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-2/slider-4.zip',],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_2.jpg'),
            ),

            array(
                'import_file_name'           => 'home 3',
                'home'                       => 'home-3',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-3.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-3/slider-1-1.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_3.jpg'),
            ),

            array(
                'import_file_name'           => 'home 4',
                'home'                       => 'home-4',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-4.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-4/slider-1-1.zip',
                'import_more_revslider_file_url' => ['https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-4/slider-1.zip',],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_4.jpg'),
            ),

            array(
                'import_file_name'           => 'home 5',
                'home'                       => 'home-5',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-5.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-5/slider-2.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_5.jpg'),
            ),

            array(
                'import_file_name'           => 'home 6',
                'home'                       => 'home-6',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-6.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-6/slider-2.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_6.jpg'),
            ),

            array(
                'import_file_name'           => 'home 7',
                'home'                       => 'home-7',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-7.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-7/slider-2.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_7.jpg'),
            ),

            array(
                'import_file_name'           => 'home 8',
                'home'                       => 'home-8',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-8.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-8/slider-1.zip',
                'import_more_revslider_file_url' => ['https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-8/slider-3.zip',],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_8.jpg'),
            ),

            array(
                'import_file_name'           => 'home 9',
                'home'                       => 'home-9',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-9.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'https://demothemedh.b-cdn.net/organey/dummy_data/revsliders/home-9/slider-3.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_9.jpg'),
            ),
            );
        }

	public function after_import_setup( $selected_import ) {
		$selected_import = ( $this->import_files() )[ $selected_import ];
		$check_oneclick  = get_option( 'organey_check_oneclick', [] );

		$this->set_demo_menus();
		wp_delete_post( 1, true );

		// setup Home page
		$home = get_page_by_path( $selected_import['home'] );
		if ( $home ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home->ID );
		}

		// Header Footer Builder
		$this->reset_header_footer();
		$this->set_hf( $selected_import['home'] );

		// WooCommerce
		if ( ! isset( $check_oneclick['woocommerce'] ) ) {
			update_option( 'woocommerce_single_image_width', 800 );
			update_option( 'woocommerce_thumbnail_image_width', 450 );
			update_option( 'woocommerce_thumbnail_cropping', 'uncropped' );
			$check_oneclick['woocommerce'] = true;
		}

		$options = $this->get_all_options();

		// Elementor
		if ( ! isset( $check_oneclick['elementor-options'] ) ) {
			$active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
			update_post_meta( $active_kit_id, '_elementor_page_settings', $options['elementor'] );

			update_option( 'elementor_experiment-e_swiper_latest', 'inactive' );
			update_option( 'elementor_unfiltered_files_upload', 1 );
			update_option( 'elementor_font_display', 'swap' );

			$check_oneclick['elementor-options'] = true;
		}

		// Options
		$theme_options = $options['options'];
		foreach ( $theme_options as $key => $option ) {
			update_option( $key, $option );
		}

		//Mailchimp
		if ( ! isset( $check_oneclick['mailchip'] ) ) {
			$mailchimp = get_page_by_title( 'Organey Mail Chimp', OBJECT, 'mc4wp-form' );
			if ( $mailchimp ) {
				update_option( 'mc4wp_default_form_id', $mailchimp->ID );
			}
			$check_oneclick['mailchip'] = true;
		}

		if(organey_is_elementor_activated()){
			\Elementor\Plugin::instance()->files_manager->clear_cache();
		}

		set_theme_mod( 'custom_logo', $this->get_attachment( '_logo' ) );

		update_option( 'organey_check_oneclick', $check_oneclick );
	}

	private function get_attachment( $key ) {
		$params = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'meta_key'       => $key,
		);
		$post   = get_posts( $params );
		if ( $post ) {
			return $post[0]->ID;
		}

		return 0;
	}

	private function init() {
		$this->wizard = new Merlin(
			$config = array(
				// Location / directory where Merlin WP is placed in your theme.
				'merlin_url'         => 'merlin',
				// The wp-admin page slug where Merlin WP loads.
				'parent_slug'        => 'themes.php',
				// The wp-admin parent page slug for the admin menu item.
				'capability'         => 'manage_options',
				// The capability required for this menu to be displayed to the user.
				'dev_mode'           => true,
				// Enable development mode for testing.
				'license_step'       => false,
				// EDD license activation step.
				'license_required'   => false,
				// Require the license activation step.
				'license_help_url'   => '',
				'directory'          => '/includes/merlin',
				// URL for the 'license-tooltip'.
				'edd_remote_api_url' => '',
				// EDD_Theme_Updater_Admin remote_api_url.
				'edd_item_name'      => '',
				// EDD_Theme_Updater_Admin item_name.
				'edd_theme_slug'     => '',
				// EDD_Theme_Updater_Admin item_slug.
			),
			$strings = array(
				'admin-menu'          => esc_html__( 'Theme Setup', 'organey' ),

				/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
				'title%s%s%s%s'       => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'organey' ),
				'return-to-dashboard' => esc_html__( 'Return to the dashboard', 'organey' ),
				'ignore'              => esc_html__( 'Disable this wizard', 'organey' ),

				'btn-skip'                 => esc_html__( 'Skip', 'organey' ),
				'btn-next'                 => esc_html__( 'Next', 'organey' ),
				'btn-start'                => esc_html__( 'Start', 'organey' ),
				'btn-no'                   => esc_html__( 'Cancel', 'organey' ),
				'btn-plugins-install'      => esc_html__( 'Install', 'organey' ),
				'btn-child-install'        => esc_html__( 'Install', 'organey' ),
				'btn-content-install'      => esc_html__( 'Install', 'organey' ),
				'btn-import'               => esc_html__( 'Import', 'organey' ),
				'btn-license-activate'     => esc_html__( 'Activate', 'organey' ),
				'btn-license-skip'         => esc_html__( 'Later', 'organey' ),

				/* translators: Theme Name */
				'license-header%s'         => esc_html__( 'Activate %s', 'organey' ),
				/* translators: Theme Name */
				'license-header-success%s' => esc_html__( '%s is Activated', 'organey' ),
				/* translators: Theme Name */
				'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'organey' ),
				'license-label'            => esc_html__( 'License key', 'organey' ),
				'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'organey' ),
				'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'organey' ),
				'license-tooltip'          => esc_html__( 'Need help?', 'organey' ),

				/* translators: Theme Name */
				'welcome-header%s'         => esc_html__( 'Welcome to %s', 'organey' ),
				'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'organey' ),
				'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'organey' ),
				'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'organey' ),

				'child-header'         => esc_html__( 'Install Child Theme', 'organey' ),
				'child-header-success' => esc_html__( 'You\'re good to go!', 'organey' ),
				'child'                => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'organey' ),
				'child-success%s'      => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'organey' ),
				'child-action-link'    => esc_html__( 'Learn about child themes', 'organey' ),
				'child-json-success%s' => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'organey' ),
				'child-json-already%s' => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'organey' ),

				'plugins-header'         => esc_html__( 'Install Plugins', 'organey' ),
				'plugins-header-success' => esc_html__( 'You\'re up to speed!', 'organey' ),
				'plugins'                => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'organey' ),
				'plugins-success%s'      => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'organey' ),
				'plugins-action-link'    => esc_html__( 'Advanced', 'organey' ),

				'import-header'      => esc_html__( 'Import Content', 'organey' ),
				'import'             => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'organey' ),
				'import-action-link' => esc_html__( 'Advanced', 'organey' ),

				'ready-header'      => esc_html__( 'All done. Have fun!', 'organey' ),

				/* translators: Theme Author */
				'ready%s'           => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'organey' ),
				'ready-action-link' => esc_html__( 'Extras', 'organey' ),
				'ready-big-button'  => esc_html__( 'View your website', 'organey' ),
				'ready-link-1'      => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'organey' ) ),
				'ready-link-2'      => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://themebeans.com/contact/', esc_html__( 'Get Theme Support', 'organey' ) ),
				'ready-link-3'      => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'organey' ) ),
			)
		);

		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
	}

	public function render_child_functions_php() {
		$output
			= "<?php
/**
 * Theme functions and definitions.
 */
		 ";

		return $output;
	}

	public function widgets_init() {
		require_once get_parent_theme_file_path( '/includes/merlin/includes/recent-post.php' );
		register_widget( 'Organey_WP_Widget_Recent_Posts' );
		if ( organey_is_woocommerce_activated() ) {
			require_once get_parent_theme_file_path( '/includes/merlin/includes/class-wc-widget-layered-nav.php' );
			register_widget( 'Organey_Widget_Layered_Nav' );
		}
	}

	private function set_hf( $home ) {
		$all_hf = $this->get_all_header_footer();
		$datas  = $all_hf[ $home ];
		foreach ( $datas as $item ) {
			$hf = get_page_by_path( $item[0]['slug'], OBJECT, 'elementor-hf' );
			if ( $hf ) {
				update_post_meta( $hf->ID, 'ehf_target_include_locations', $item[0]['ehf_target_include_locations'] );
			}
		}
	}

	private function get_all_header_footer() {
		return [
			'home-1'  => [
				'header' => [
					[
						'slug'                         => 'header-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-2'  => [
				'header' => [
					[
						'slug'                         => 'header-2',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-3'  => [
				'header' => [
					[
						'slug'                         => 'header-3',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-4'  => [
				'header' => [
					[
						'slug'                         => 'header-4',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-2',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-5'  => [
				'header' => [
					[
						'slug'                         => 'header-5',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-6'  => [
				'header' => [
					[
						'slug'                         => 'header-6',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-7'  => [
				'header' => [
					[
						'slug'                         => 'header-3',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-8'  => [
				'header' => [
					[
						'slug'                         => 'header-7',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-9'  => [
				'header' => [
					[
						'slug'                         => 'header-8',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-10' => [
				'header' => [
					[
						'slug'                         => 'header-3',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'                         => 'footer-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			]
		];
	}

	private function reset_header_footer() {
		$args = array(
			'post_type'      => 'elementor-hf',
			'posts_per_page' => - 1,
			'meta_query'     => array(
				array(
					'key'     => 'ehf_template_type',
					'compare' => 'IN',
					'value'   => [ 'type_footer', 'type_header' ]
				),
			)
		);
		$hf   = new WP_Query( $args );
		while ( $hf->have_posts() ) : $hf->the_post();
			update_post_meta( get_the_ID(), 'ehf_target_include_locations', [] );
			update_post_meta( get_the_ID(), 'ehf_target_exclude_locations', [] );
		endwhile;
		wp_reset_postdata();
	}

	private function reset_header() {
		$args = array(
			'post_type'      => 'elementor-hf',
			'posts_per_page' => - 1,
			'meta_query'     => array(
				array(
					'key'     => 'ehf_template_type',
					'compare' => 'IN',
					'value'   => [ 'type_header' ]
				),
			)
		);
		$hf   = new WP_Query( $args );
		while ( $hf->have_posts() ) : $hf->the_post();
			update_post_meta( get_the_ID(), 'ehf_target_include_locations', [] );
			update_post_meta( get_the_ID(), 'ehf_target_exclude_locations', [] );
		endwhile;
		wp_reset_postdata();
	}

	private function reset_footer() {
		$args = array(
			'post_type'      => 'elementor-hf',
			'posts_per_page' => - 1,
			'meta_query'     => array(
				array(
					'key'     => 'ehf_template_type',
					'compare' => 'IN',
					'value'   => [ 'type_footer' ]
				),
			)
		);
		$hf   = new WP_Query( $args );
		while ( $hf->have_posts() ) : $hf->the_post();
			update_post_meta( get_the_ID(), 'ehf_target_include_locations', [] );
			update_post_meta( get_the_ID(), 'ehf_target_exclude_locations', [] );
		endwhile;
		wp_reset_postdata();
	}

	public function set_demo_menus() {
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		set_theme_mod(
			'nav_menu_locations',
			array(
				'primary'  => $main_menu->term_id,
				'handheld' => $main_menu->term_id,
			)
		);
	}

	public function register_setuphf_page() {
		add_submenu_page(
			'themes.php',
			__( 'Setup Header Footer', 'organey' ),
			__( 'Setup Header Footer', 'organey' ),
			'manage_options',
			'hfe-setuphf',
			[ $this, 'setuphf_template' ]
		);
	}

	public function setuphf_template() {
		echo '<h1 class="hfe-heading-inline">';
		esc_attr_e( 'Elementor Header & Footer Builder ', 'organey' );
		echo '</h1>';
		?>

        <div class="nav-tab-wrapper">
			<?php
			if ( ! isset( HFE\Themes\HFE_Settings_Page::$hfe_settings_tabs ) ) {
				HFE\Themes\HFE_Settings_Page::$hfe_settings_tabs['hfe_templates'] = [
					'name' => __( 'All Templates', 'organey' ),
					'url'  => admin_url( 'edit.php?post_type=elementor-hf' ),
				];
			}

			HFE\Themes\HFE_Settings_Page::$hfe_settings_tabs['hfe_about'] = [
				'name' => __( 'About Us', 'organey' ),
				'url'  => admin_url( 'themes.php?page=hfe-about' ),
			];

			$tabs = apply_filters( 'hfe_settings_tabs', HFE\Themes\HFE_Settings_Page::$hfe_settings_tabs );

			foreach ( $tabs as $tab_id => $tab ) {

				$tab_slug = str_replace( '_', '-', $tab_id );

				$active_tab = ( ( isset( $_GET['page'] ) && $tab_slug == $_GET['page'] ) || ( ! isset( $_GET['page'] ) && 'hfe_templates' == $tab_id ) ) ? $tab_id : '';

				$active = ( $active_tab == $tab_id ) ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab['url'] ) . '" class="nav-tab' . esc_attr( $active ) . '">';
				echo esc_html( $tab['name'] );
				echo '</a>';
			}

			?>
        </div>
		<?php
		$headers = get_posts( [
			'posts_per_page' => - 1,
			'post_type'      => 'elementor-hf',
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'   => 'ehf_template_type',
					'value' => 'type_header',
				),
			),
		] );
		$footers = get_posts( [
			'posts_per_page' => - 1,
			'post_type'      => 'elementor-hf',
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'   => 'ehf_template_type',
					'value' => 'type_footer',
				),
			),
		] );
		?>
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <table class="form-table">
				<?php if ( isset( $_GET['saved'] ) ) { ?>
                    <tr>
                        <td>
                            <div class="updated">
                                <p><?php esc_html_e( 'Success! Have been setup for your website', 'organey' ); ?></p>
                            </div>
                        </td>
                    </tr>
				<?php } ?>

                <tr>
                    <td>
                        <fieldset>
                            <ul>
                                <li>
                                    <label><?php esc_html_e( 'Header', 'organey' ) ?>:
                                        <select name="setup-header">
                                            <option value="" selected>Select Header</option>
											<?php foreach ( $headers as $header ): ?>
                                                <option value="<?php echo esc_attr( $header->ID ) ?>"><?php echo esc_html( $header->post_title ); ?></option>
											<?php endforeach; ?>
                                        </select>
                                    </label>
                                </li>
                            </ul>
                        </fieldset>
                        <fieldset>
                            <ul>
                                <li>
                                    <label><?php esc_html_e( 'Footer', 'organey' ) ?>:
                                        <select name="setup-footer">
                                            <option value="" selected>Select Footer</option>
											<?php foreach ( $footers as $footer ): ?>
                                                <option value="<?php echo esc_attr( $footer->ID ) ?>"><?php echo esc_html( $footer->post_title ); ?></option>
											<?php endforeach; ?>
                                        </select>
                                    </label>
                                </li>
                            </ul>
                        </fieldset>
                    </td>
                </tr>
            </table>

			<?php wp_nonce_field( 'setup_custom_hf' ); ?>
            <input type="hidden" name="action" value="custom_setup_hf">
			<?php submit_button( esc_html( 'Setup Now!' ) ); ?>
        </form>
		<?php
	}

	public function action_setup_hf() {
		$retrieved_nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $retrieved_nonce, 'setup_custom_hf' ) ) {
			die( 'Failed security check' );
		}

		if ( isset( $_REQUEST['setup-header'] ) && $_REQUEST['setup-header'] ) {
			$this->reset_header();
			update_post_meta( absint( $_REQUEST['setup-header'] ), 'ehf_target_include_locations', [
				'rule'     => [ 'basic-global' ],
				'specific' => []
			] );
		}

		if ( isset( $_REQUEST['setup-footer'] ) && $_REQUEST['setup-footer'] ) {
			$this->reset_footer();
			update_post_meta( absint( $_REQUEST['setup-footer'] ), 'ehf_target_include_locations', [
				'rule'     => [ 'basic-global' ],
				'specific' => []
			] );
		}

		wp_redirect( admin_url( 'themes.php?page=hfe-setuphf&saved=1' ) );
		exit;
	}

	public function setup_hf_theme( $hfe_settings_tabs ) {
		if ( ! current_theme_supports( 'header-footer-elementor' ) ) {
			$hfe_settings_tabs['hfe_setuphf'] = [
				'name' => __( 'Setup Header Footer', 'organey' ),
				'url'  => admin_url( 'themes.php?page=hfe-setuphf' ),
			];
		}

		return $hfe_settings_tabs;
	}

	public function fiximport_elementor( $post_meta ) {
		if ( '_elementor_data' === $post_meta['key'] ) {
			$post_meta['value'] = wp_slash( $post_meta['value'] );
		}

		return $post_meta;
	}

	public function get_all_options(){
        $options = [];
        $options['options']   = json_decode('{"organey_options_social_share":"1","organey_options_social_share_facebook":"1","organey_options_social_share_twitter":"1","organey_options_social_share_linkedin":"1","organey_options_social_share_pinterest":"1","organey_options_social_share_email":"1","organey_options_single_product_gallery_layout":"horizontal","organey_options_single_product_content_meta":"<p><span style=\"color:#3D3E3D\n;font-size:12px;font-weight:600; margin-bottom: 15px; display: block;\">Guarantee Safe Checkout</span><img loading=\"lazy\" style=\"padding-bottom: 5px;\" class=\"alignnone size-medium wp-image-6252\" src=\"https://demothemedh.b-cdn.net/organey/wp-content/uploads/2021/08/credit-300x17.jpg\" sizes=\"(max-width: 300px) 100vw, 300px\" alt=\"\" width=\"300\" height=\"17\" /></p>\n"}', true);
        $options['elementor']   = json_decode('{"system_colors":[{"_id":"primary","title":"Primary","color":"#5C9963"},{"_id":"primary_hover","title":"Primary Hover","color":"#528959"},{"_id":"secondary","title":"Secondary","color":"#FFA900"},{"_id":"secondary_hover","title":"Secondary Hover","color":"#ff8d00"},{"_id":"text","title":"Text","color":"#656766"},{"_id":"accent","title":"Heading","color":"#2F3E30"},{"_id":"border","title":"Border","color":"#E4E4E4"},{"_id":"light","title":"Light","color":"#9f9f9f"}],"custom_colors":[],"system_typography":[{"_id":"heading_title","title":"Heading Title","typography_typography":"custom","typography_font_family":"Poppins","typography_font_weight":"600","typography_font_size":{"unit":"px","size":30,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":30,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":24,"sizes":[]},"typography_line_height":{"unit":"px","size":40,"sizes":[]},"typography_line_height_mobile":{"unit":"px","size":34,"sizes":[]}},{"_id":"heading_text","title":"Heading Text","typography_typography":"custom","typography_font_family":"Poppins","typography_font_weight":"600","typography_font_size":{"unit":"px","size":24,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":20,"sizes":[]},"typography_line_height":{"unit":"px","size":34,"sizes":[]},"typography_line_height_mobile":{"unit":"px","size":30,"sizes":[]}},{"_id":"heading_banner","title":"Heading Banner","typography_typography":"custom","typography_font_family":"Poppins","typography_font_weight":"600","typography_font_size":{"unit":"px","size":24,"sizes":[]},"typography_line_height":{"unit":"px","size":30,"sizes":[]}},{"_id":"heading_footer","title":"heading Footer","typography_typography":"custom","typography_font_family":"Poppins","typography_font_weight":"700","typography_text_transform":"uppercase","typography_font_size":{"unit":"px","size":16,"sizes":[]},"typography_line_height":{"unit":"px","size":20,"sizes":[]}}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Organey","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"viewport_md":768,"viewport_lg":1025,"viewport_mobile":767,"viewport_tablet":1024}', true);
        return $options;
    } // end get_all_options

}

return new Organey_Merlin_Config();
