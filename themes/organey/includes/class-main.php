<?php
if ( ! class_exists( 'Organey_Theme_Setup' ) ):
	class Organey_Theme_Setup {
		public function __construct() {
			add_action( 'after_setup_theme', [ $this, 'setup' ] );
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			add_filter( 'organey_theme_sidebar', array( $this, 'set_sidebar' ), 20 );
			add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'child_theme_scripts' ], 30 );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
			add_action( 'wp_head', [ $this, 'pingback' ] );
			add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
			add_action( 'wp_body_open', [ $this, 'preloader_support' ] );
			add_filter( 'body_class', array( $this, 'body_classes' ) );

			add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
			add_filter( 'use_widgets_block_editor', '__return_false' );
		}

		public function register_required_plugins() {
			/**
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$plugins = array(
				array(
					'name'     => 'Elementor',
					'slug'     => 'elementor',
					'required' => true,
				),
				array(
					'name'     => 'Elementor – Header, Footer & Blocks Template',
					'slug'     => 'header-footer-elementor',
					'required' => true,
				),
				array(
					'name'     => 'Revslider',
					'slug'     => 'revslider',
					'required' => true,
					'source'   => esc_url( 'http://plugins.leebrosus.com/revslider.zip' ),
				),
				array(
					'name'     => 'Woocommerce',
					'slug'     => 'woocommerce',
					'required' => true,
				),
				array(
					'name'     => 'SVG Support',
					'slug'     => 'svg-support',
					'required' => true,
				),
				array(
					'name'     => 'Make Column Clickable Elementor',
					'slug'     => 'make-column-clickable-elementor',
					'required' => true,
				),
				array(
					'name'     => 'Woo Variation Swatches',
					'slug'     => 'woo-variation-swatches',
					'required' => false,
				),
				array(
					'name'     => 'WPC Smart Wishlist for WooCommerce',
					'slug'     => 'woo-smart-wishlist',
					'required' => false,
				),
				array(
					'name'     => 'WPC Smart Quick View for WooCommerce',
					'slug'     => 'woo-smart-quick-view',
					'required' => false,
				),
				array(
					'name'     => 'WPC Smart Compare for WooCommerce',
					'slug'     => 'woo-smart-compare',
					'required' => false,
				),
				array(
					'name'     => 'Mailchimp For Wordpress',
					'slug'     => 'mailchimp-for-wp',
					'required' => true,
				),
				array(
					'name'     => 'Contact Form 7',
					'slug'     => 'contact-form-7',
					'required' => true,
				),

			);

			/*
			 * Array of configuration settings. Amend each line as needed.
			 *
			 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
			 * strings available, please help us make TGMPA even better by giving us access to these translations or by
			 * sending in a pull-request with .po file(s) with the translations.
			 *
			 * Only uncomment the strings in the config array if you want to customize the strings.
			 */
			$config = array(
				'id'           => 'organey',
				// Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',
				// Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins',
				// Menu slug.
				'has_notices'  => true,
				// Show admin notices or not.
				'dismissable'  => true,
				// If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',
				// If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,
				// Automatically activate plugins after installation or not.
				'message'      => '',
			);

			tgmpa( $plugins, $config );
		}

		public function scripts() {
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style(
				'organey-style',
				get_template_directory_uri() . '/style.css',
				[],
				ORGANEY_VERSION
			);
			wp_style_add_data( 'organey-style', 'rtl', 'replace' );

			wp_enqueue_style( 'organey-fonts', $this->get_google_fonts() );
			wp_enqueue_script( 'organey-theme', get_template_directory_uri() . '/assets/js/main.js', array(
				'jquery',
				'wp-util'
			), ORGANEY_VERSION, true );
			wp_localize_script( 'organey-theme', 'organeyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

			wp_enqueue_script('imagesloaded');

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			wp_register_script( 'slick', get_template_directory_uri() . '/assets/js/vendor/slick' . $suffix . '.js', array(), ORGANEY_VERSION, true );
			wp_register_script( 'magnific-popup', get_template_directory_uri() . '/assets/js/vendor/jquery.magnific-popup.min.js', array( 'jquery' ), ORGANEY_VERSION, true );

			wp_register_script( 'organey-nav-mobile', get_template_directory_uri() . '/assets/js/nav-mobile.js', array( 'jquery' ), ORGANEY_VERSION, true );

			wp_register_script( 'organey-countdown', get_template_directory_uri() . '/assets/js/countdown.js', array( 'jquery' ), ORGANEY_VERSION, true );

			wp_register_script( 'spritespin', get_template_directory_uri() . '/assets/js/vendor/spritespin.js', array( 'jquery' ), ORGANEY_VERSION, true );

			wp_register_script( 'sticky-kit', get_template_directory_uri() . '/assets/js/vendor/jquery.sticky-kit.min.js', array( 'jquery' ), ORGANEY_VERSION, true );

			wp_register_script( 'tooltipster', get_template_directory_uri() . '/assets/js/vendor/tooltipster.min.js', array(), ORGANEY_VERSION, true );
		}

		public function admin_scripts() {
			wp_enqueue_style( 'organey-admin-style', get_theme_file_uri( 'assets/css/admin/dashboard.css' ) );
		}

		public function child_theme_scripts() {
			if ( is_child_theme() ) {
				$child_theme = wp_get_theme( get_stylesheet() );
				wp_enqueue_style( 'organey-child-style', get_stylesheet_uri(), array(), $child_theme->get( 'Version' ) );
			}
		}

		public function setup() {
			$GLOBALS['content_width'] = apply_filters( 'organey_content_width', 800 );

			$hook_result = apply_filters_deprecated( 'organey_load_textdomain', [ true ], '2.0', 'organey_load_textdomain' );
			if ( apply_filters( 'organey_load_textdomain', $hook_result ) ) {
				load_theme_textdomain( 'organey', get_template_directory() . '/languages' );
			}

			register_nav_menus(
				apply_filters(
					'organey_register_nav_menus', array(
						'primary'  => esc_html__( 'Primary Menu', 'organey' ),
						'handheld' => esc_html__( 'Handheld Menu', 'organey' ),
						'vertical' => esc_html__( 'Vertical Menu', 'organey' ),
					)
				)
			);

			$hook_result = apply_filters_deprecated( 'organey_add_theme_support', [ true ], '2.0', 'organey_add_theme_support' );
			if ( apply_filters( 'organey_add_theme_support', $hook_result ) ) {
				add_theme_support( 'post-thumbnails' );
				add_image_size( 'organey-post-grid', 650, 450, true );
				add_image_size( 'organey-product-list', 150, 150, true );
				set_post_thumbnail_size( 1170, 600, true );
				add_theme_support( 'automatic-feed-links' );
				add_theme_support( 'title-tag' );
				add_theme_support(
					'html5',
					array(
						'search-form',
						'comment-form',
						'comment-list',
						'gallery',
						'caption',
						'widgets',
						'script',
						'style',
					)
				);
				add_theme_support( 'custom-logo', array(
					'width'       => 300,
					'height'      => 200,
					'flex-width'  => true,
					'flex-height' => true,
				) );

				add_theme_support( 'title-tag' );
				add_theme_support( 'customize-selective-refresh-widgets' );
				add_theme_support( 'wp-block-styles' );
				add_theme_support( 'align-wide' );
				add_theme_support( 'editor-styles' );
				add_theme_support( 'responsive-embeds' );

				/*
				 * WooCommerce.
				 */
				$hook_result = apply_filters_deprecated( 'organey_add_woocommerce_support', [ true ], '2.0', 'organey_add_woocommerce_support' );
				if ( apply_filters( 'organey_add_woocommerce_support', $hook_result ) ) {
					// WooCommerce in general.
					add_theme_support( 'woocommerce' );
				}
			}
		}

		public function widgets_init() {
			$sidebar_args['sidebar']        = array(
				'name'        => esc_html__( 'Sidebar Archive', 'organey' ),
				'id'          => 'sidebar-blog',
				'description' => '',
			);
			$sidebar_args['sidebar-single'] = array(
				'name'        => esc_html__( 'Sidebar Single Post', 'organey' ),
				'id'          => 'sidebar-single',
				'description' => '',
			);

			$sidebar_args = apply_filters( 'organey_sidebar_args', $sidebar_args );

			foreach ( $sidebar_args as $sidebar => $args ) {
				$widget_tags = array(
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<span class="gamma widget-title">',
					'after_title'   => '</span>',
				);

				register_sidebar( $args + $widget_tags );
			}
		}

		public function set_sidebar( $name ) {

			if ( is_singular( 'post' ) ) {
				if ( is_active_sidebar( 'sidebar-single' ) ) {
					$name = 'sidebar-single';
				}
			}

			if ( is_archive() || is_home() || is_search() ) {
				if ( is_active_sidebar( 'sidebar-blog' ) ) {
					$name = 'sidebar-blog';
				}
			}

			return $name;
		}

		public function body_classes( $classes ) {
			global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
			if ( $is_lynx ) {
				$classes[] = 'lynx';
			} elseif ( $is_gecko ) {
				$classes[] = 'gecko';
			} elseif ( $is_opera ) {
				$classes[] = 'opera';
			} elseif ( $is_NS4 ) {
				$classes[] = 'ns4';
			} elseif ( $is_safari ) {
				$classes[] = 'safari';
			} elseif ( $is_chrome ) {
				$classes[] = 'chrome';
			} elseif ( $is_IE ) {
				$classes[] = 'ie';
			}

			if ( $is_iphone ) {
				$classes[] = 'iphone';
			}

			// Adds a class to blogs with more than 1 published author.
			if ( is_multi_author() ) {
				$classes[] = 'group-blog';
			}

			// Add class when using homepage template + featured image.
			if ( has_post_thumbnail() ) {
				$classes[] = 'has-post-thumbnail';
			}
			$sidebar = organey_get_theme_option( 'blog_sidebar', 'left' );

			if ( is_singular( 'post' ) ) {
				if ( is_active_sidebar( 'sidebar-single' ) ) {
					$classes[] = 'organey-content-has-sidebar';
					if ( $sidebar == 'left' ) {
						$classes[] = 'organey-sidebar-left';
					} else {
						$classes = array_diff( $classes, array(
							'organey-sidebar-left',
						) );
					}
				}
			}

			if ( is_archive() || is_home() || is_category() || is_tag() || is_author() || is_search() ) {
				if ( is_active_sidebar( 'sidebar-blog' ) ) {
					$classes[] = 'organey-content-has-sidebar';
					if ( $sidebar == 'left' ) {
						$classes[] = 'organey-sidebar-left';
					} else {
						$classes = array_diff( $classes, array(
							'organey-sidebar-left',
						) );
					}
				}
			}

			return $classes;
		}

		public function preloader_support() {
			echo '<div id="wptime-plugin-preloader"></div>';
		}

		public function pingback() {
			get_template_part( 'template-parts/preload' );
			if ( is_singular() && pings_open() ) {
				echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
			}
		}

		public function get_google_fonts() {
			$google_fonts = apply_filters( 'organey_google_font_families', [
				'Poppins' => 'Poppins:400,500,600,700,800,900',
			] );

			if ( count( $google_fonts ) <= 0 ) {
				return false;
			}

			$query_args = array(
				'family'  => implode( '|', $google_fonts ),
				'subset'  => rawurlencode( 'latin,latin-ext' ),
				'display' => 'swap',
			);

			$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

			return $fonts_url;
		}
	}
endif;

return new Organey_Theme_Setup();
