<?php
if ( ! class_exists( 'Organey_Theme_Elementor' ) ):
	class Organey_Theme_Elementor {
		public function __construct() {
			add_action( 'wp', [ $this, 'register_auto_scripts_frontend' ] );
			add_action( 'elementor/init', array( $this, 'add_category' ) );
			add_filter( 'elementor/frontend/admin_bar/settings', [ $this, 'add_menu_in_admin_bar' ] );
			add_filter( 'elementor/controls/animations/additional_animations', [ $this, 'add_animations_scroll' ] );
			add_action( 'elementor/theme/register_locations', [ $this, 'register_locations' ] );
			add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'scripts' ] );
			add_action( 'elementor/widgets/register', array( $this, 'customs_widgets' ) );
			add_action( 'elementor/widgets/register', array( $this, 'include_widgets' ) );

			add_filter( 'elementor/widget/render_content', [ $this, 'get_skeleton_content' ], 10, 2 );

			add_filter( 'organey_page_title', [ $this, 'hide_title' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'global_css' ], 20 );

			add_action( 'elementor/icons_manager/native', [ $this, 'add_icons_native' ] );
			add_action( 'elementor/controls/controls_registered', [ $this, 'add_icons' ] );
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'add_style_editor' ], 99 );

			// Add Parallax in section
			require get_theme_file_path( 'includes/elementor/parallax-section.php' );
			if ( ! organey_is_elementor_pro_activated() ) {
				require get_theme_file_path( 'includes/elementor/custom-css.php' );
				require get_theme_file_path( 'includes/elementor/sticky-section.php' );
				if ( is_admin() ) {
					add_action( 'manage_elementor_library_posts_columns', [ $this, 'admin_columns_headers' ] );
					add_action( 'manage_elementor_library_posts_custom_column', [
						$this,
						'admin_columns_content'
					], 10, 2 );
				}
			}
		}

		public function add_category() {
			Elementor\Plugin::instance()->elements_manager->add_category(
				'organey-addons',
				array(
					'title' => esc_html__( 'Organey Addons', 'organey' ),
					'icon'  => 'fa fa-plug',
				),
				1 );
		}

		public function admin_columns_headers( $defaults ) {
			$defaults['shortcode'] = esc_html__( 'Shortcode', 'organey' );

			return $defaults;
		}

		public function admin_columns_content( $column_name, $post_id ) {
			if ( 'shortcode' === $column_name ) {
				ob_start();
				?>
                <input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="[hfe_template id='<?php echo esc_attr( $post_id ); ?>']"/>
				<?php
				ob_get_contents();
			}
		}

		public function add_style_editor() {
			wp_enqueue_style( 'organey-elementor-editor-icon', get_theme_file_uri( '/elementor-icons.css' ), [], ORGANEY_VERSION );
		}

		public function add_icons( $manager ) {
			$new_icons = json_decode( '{"organey-icon-almonds":"almonds","organey-icon-carrot":"carrot","organey-icon-cart":"cart","organey-icon-cherry":"cherry","organey-icon-close":"close","organey-icon-collapse":"collapse","organey-icon-copy":"copy","organey-icon-credit-card":"credit-card","organey-icon-delete":"delete","organey-icon-dropdown":"dropdown","organey-icon-expand":"expand","organey-icon-favourite":"favourite","organey-icon-filter":"filter","organey-icon-fish":"fish","organey-icon-history":"history","organey-icon-leaf":"leaf","organey-icon-lettuce":"lettuce","organey-icon-live-support":"live-support","organey-icon-location-1":"location-1","organey-icon-mail-1":"mail-1","organey-icon-minus-square":"minus-square","organey-icon-open-can":"open-can","organey-icon-organic":"organic","organey-icon-paper-bags":"paper-bags","organey-icon-phone-1":"phone-1","organey-icon-phone-plus":"phone-plus","organey-icon-plus-square":"plus-square","organey-icon-rocket":"rocket","organey-icon-search":"search","organey-icon-shop-leaf":"shop-leaf","organey-icon-short-arrow-left":"short-arrow-left","organey-icon-sprout":"sprout","organey-icon-tag":"tag","organey-icon-tofu":"tofu","organey-icon-user":"user","organey-icon-360":"360","organey-icon-angle-down":"angle-down","organey-icon-angle-left":"angle-left","organey-icon-angle-right":"angle-right","organey-icon-angle-up":"angle-up","organey-icon-arrow-left":"arrow-left","organey-icon-arrow-right":"arrow-right","organey-icon-bars":"bars","organey-icon-calendar":"calendar","organey-icon-cart-empty":"cart-empty","organey-icon-chevron-down":"chevron-down","organey-icon-chevron-left":"chevron-left","organey-icon-chevron-right":"chevron-right","organey-icon-chevron-up":"chevron-up","organey-icon-clock":"clock","organey-icon-cloud-download-alt":"cloud-download-alt","organey-icon-comment":"comment","organey-icon-comments":"comments","organey-icon-edit":"edit","organey-icon-envelope":"envelope","organey-icon-expand-alt":"expand-alt","organey-icon-external-link-alt":"external-link-alt","organey-icon-eye":"eye","organey-icon-file-alt":"file-alt","organey-icon-file-archive":"file-archive","organey-icon-folder-open":"folder-open","organey-icon-folder":"folder","organey-icon-frown":"frown","organey-icon-gift":"gift","organey-icon-grid":"grid","organey-icon-heart-fill":"heart-fill","organey-icon-heart":"heart","organey-icon-home":"home","organey-icon-info-circle":"info-circle","organey-icon-instagram":"instagram","organey-icon-list":"list","organey-icon-long-arrow-alt-down":"long-arrow-alt-down","organey-icon-long-arrow-alt-left":"long-arrow-alt-left","organey-icon-long-arrow-alt-right":"long-arrow-alt-right","organey-icon-long-arrow-alt-up":"long-arrow-alt-up","organey-icon-map-marker-check":"map-marker-check","organey-icon-meh":"meh","organey-icon-minus":"minus","organey-icon-money-bill":"money-bill","organey-icon-pencil-alt":"pencil-alt","organey-icon-plus":"plus","organey-icon-quote":"quote","organey-icon-random":"random","organey-icon-reply-all":"reply-all","organey-icon-reply":"reply","organey-icon-router":"router","organey-icon-shopping-cart":"shopping-cart","organey-icon-sign-out-alt":"sign-out-alt","organey-icon-smile":"smile","organey-icon-spinner":"spinner","organey-icon-square":"square","organey-icon-star":"star","organey-icon-store":"store","organey-icon-sync":"sync","organey-icon-tachometer-alt":"tachometer-alt","organey-icon-th-large":"th-large","organey-icon-th-list":"th-list","organey-icon-thumbtack":"thumbtack","organey-icon-times-circle":"times-circle","organey-icon-times":"times","organey-icon-truck":"truck","organey-icon-video":"video","organey-icon-adobe":"adobe","organey-icon-amazon":"amazon","organey-icon-android":"android","organey-icon-angular":"angular","organey-icon-apper":"apper","organey-icon-apple":"apple","organey-icon-atlassian":"atlassian","organey-icon-behance":"behance","organey-icon-bitbucket":"bitbucket","organey-icon-bitcoin":"bitcoin","organey-icon-bity":"bity","organey-icon-bluetooth":"bluetooth","organey-icon-btc":"btc","organey-icon-centos":"centos","organey-icon-chrome":"chrome","organey-icon-codepen":"codepen","organey-icon-cpanel":"cpanel","organey-icon-discord":"discord","organey-icon-dochub":"dochub","organey-icon-docker":"docker","organey-icon-dribbble":"dribbble","organey-icon-dropbox":"dropbox","organey-icon-drupal":"drupal","organey-icon-ebay":"ebay","organey-icon-facebook":"facebook","organey-icon-figma":"figma","organey-icon-firefox":"firefox","organey-icon-google-plus":"google-plus","organey-icon-google":"google","organey-icon-grunt":"grunt","organey-icon-gulp":"gulp","organey-icon-html5":"html5","organey-icon-joomla":"joomla","organey-icon-link-brand":"link-brand","organey-icon-linkedin":"linkedin","organey-icon-mailchimp":"mailchimp","organey-icon-opencart":"opencart","organey-icon-paypal":"paypal","organey-icon-pinterest-p":"pinterest-p","organey-icon-reddit":"reddit","organey-icon-skype":"skype","organey-icon-slack":"slack","organey-icon-snapchat":"snapchat","organey-icon-spotify":"spotify","organey-icon-trello":"trello","organey-icon-twitter":"twitter","organey-icon-vimeo":"vimeo","organey-icon-whatsapp":"whatsapp","organey-icon-wordpress":"wordpress","organey-icon-yoast":"yoast","organey-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons );
		}

		public function additional_fonts( $fonts ) {
			return $fonts;
		}

		public function add_icons_native( $tabs ) {
			$tabs['opal-custom'] = [
				'name'          => 'organey-icon',
				'label'         => esc_html__( 'Organey Icon', 'organey' ),
				'prefix'        => 'organey-icon-',
				'displayPrefix' => 'organey-icon-',
				'labelIcon'     => 'fab fa-font-awesome-alt',
				'ver'           => ORGANEY_VERSION,
				'fetchJson'     => get_theme_file_uri( '/includes/elementor/icons.json' ),
				'native'        => true,
			];

			return $tabs;
		}

		public function add_menu_in_admin_bar( $admin_bar_config ) {
			if ( $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id() ) {
				$admin_bar_config['elementor_edit_page']['children'][] = [
					'id'    => 'elementor_app_global_settings_editor',
					'title' => esc_html__( 'Open Global Settings', 'organey' ),
					'href'  => Elementor\Plugin::$instance->documents->get( $active_kit_id )->get_edit_url(),
				];
			}

			return $admin_bar_config;
		}

		public function global_css() {
			wp_enqueue_style( 'organey-elementor-css', get_theme_file_uri( 'elementor.css' ), false, ORGANEY_VERSION );
			wp_style_add_data( 'organey-elementor-css', 'rtl', 'replace' );

			$active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
			Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
			$myvals = get_post_meta( $active_kit_id, '_elementor_page_settings', true );
			if ( ! empty( $myvals ) ) {
				$css = '';
				foreach ( $myvals['system_colors'] as $key => $value ) {
					$css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
				}

				$var = "body{{$css}}";
				wp_add_inline_style( 'organey-style', $var );
			}
		}

		public function scripts() {
			wp_enqueue_script( 'organey-elementor-js', get_theme_file_uri( '/assets/js/elementor.js' ), [ 'jquery' ], ORGANEY_VERSION );
		}

		public function register_auto_scripts_frontend() {
			// Register auto scripts frontend
			$files = glob( get_theme_file_path( '/assets/js/elementor/*.js' ) );
			foreach ( $files as $file ) {
				$file_name = wp_basename( $file );
				$handle    = str_replace( ".js", '', $file_name );
				$scr       = get_theme_file_uri( '/assets/js/elementor/' . $file_name );
				if ( file_exists( $file ) ) {
					wp_register_script( 'organey-elementor-' . $handle, $scr, [
						'jquery',
						'elementor-frontend'
					], ORGANEY_VERSION, true );
				}
			}
		}

		public function customs_widgets() {
			$files = glob( get_theme_file_path( '/includes/elementor/custom-widgets/*.php' ) );
			foreach ( $files as $file ) {
				$file = get_theme_file_path( 'includes/elementor/custom-widgets/' . wp_basename( $file ) );
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		}

		public function include_widgets( $widgets_manager ) {
			$files = glob( get_theme_file_path( '/includes/elementor/widgets/*.php' ) );
			foreach ( $files as $file ) {
				$file = get_theme_file_path( 'includes/elementor/widgets/' . wp_basename( $file ) );
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		}

		/**
		 * @param $widget_content
		 * @param $widget \Elementor\Widget_Base
		 */
		public function get_skeleton_content( $widget_content, $widget ) {
			$settings = $widget->get_settings();
			if ( $widget->get_name() == 'image-box' ) {
				if ( isset( $settings['image']['id'] ) ) {
					$image = wp_get_attachment_image_src( $settings['image']['id'], $settings['thumbnail_size'] );
					if ( $image ) {
						list( $src, $width, $height ) = $image;
						ob_start();
						printf( '%s', $widget_content );
						$widget_content = organey_render_skeleton( 'skeleton-image-box', 1, [ 'padding-bottom' => 'calc(' . $height / $width * 100 . '% + 50px)' ], 0 );
					}
				}
			}

			return $widget_content;
		}

		public function add_animations_scroll( $animations ) {
			$animations['HP Animation'] = [
				'hp-move-up'    => 'Move Up',
				'hp-move-down'  => 'Move Down',
				'hp-move-left'  => 'Move Left',
				'hp-move-right' => 'Move Right',
				'hp-scale-up'   => 'Scale',
			];

			return $animations;
		}

		public function register_locations( $elementor_theme_manager ) {
			$hook_result = apply_filters_deprecated( 'organey_register_elementor_locations', [ true ], '2.0', 'organey_register_elementor_locations' );
			if ( apply_filters( 'organey_register_elementor_locations', $hook_result ) ) {
				$elementor_theme_manager->register_all_core_location();
			}
		}

		public function hide_title( $val ) {
			$current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}

			return $val;
		}
	}
endif;

return new Organey_Theme_Elementor();
