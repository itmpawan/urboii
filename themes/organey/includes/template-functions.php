<?php
/**
 * Created by PhpStorm.
 * User: namda
 * Date: 12/16/2020
 * Time: 9:57 PM
 */

if ( ! function_exists( 'organey_post_meta' ) ) {
	function organey_post_meta( $atts = array() ) {
		extract(
			shortcode_atts(
				array(
					'author'     => 1,
					'author_ava' => 0,
					'date'       => 1,
					'cats'       => 0,
					'tags'       => 0,
					'labels'     => 0,
					'icons'      => 1,
					'comments'   => 1,
					'limit_cats' => 0,
				),
				$atts
			)
		);
		?>

		<?php if ( $date == 1 ) :
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf(
				$time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);

			$icon_date = $icons == 1 ? '<i class="organey-icon-calendar"></i>' : '';
			$posted_on = '<span class="posted-on">' . $icon_date . sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), $time_string ) . '</span>';

			echo wp_kses( $posted_on, array(
					'span' => array(
						'class' => array(),
					),
					'a'    => array(
						'href'  => array(),
						'title' => array(),
						'rel'   => array(),
					),
					'time' => array(
						'datetime' => array(),
						'class'    => array(),
					),
					'i'    => array(
						'class' => array(),
					)
				)
			);
		endif ?>

		<?php if ( get_post_type() === 'post' ) : ?>
			<?php if ( $author == 1 ) : ?>
                <span class="post-author">
                    <?php if ( $icons == 1 ) : ?>
                        <i class="organey-icon-user"></i>
                    <?php endif; ?>
					<?php if ( $author_ava == 1 ) : ?>
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', 'author-avatar' ); ?>
					<?php endif; ?>
					<?php if ( $labels == 1 ) : ?>
                        <span class="label"><?php esc_html_e( 'Posted by', 'organey' ); ?> </span>
					<?php endif; ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                        <span class="vcard author author_name">
                            <span class="fn"><?php echo get_the_author(); ?></span>
                        </span>
                    </a>
                    </span>
			<?php endif; ?>

			<?php // Categories ?>
			<?php if ( get_the_category_list( ', ' ) && $cats == 1 ) : ?>
                <span class="meta-categories">
                    <?php if ( $labels == 1 ) : ?>
                        <span class="label"><?php esc_html_e( 'Categories:', 'organey' ); ?> </span>
                    <?php endif; ?>
					<?php echo get_the_category_list( ', ' ); ?>
                </span>
			<?php endif; ?>
			<?php // Tags ?>
			<?php if ( get_the_tag_list( '', ', ' ) && $tags == 1 ) : ?>
                <span class="meta-tags">
                    <?php if ( $labels == 1 ) : ?>
                        <span class="label"><?php esc_html_e( 'Posted by', 'organey' ); ?> </span>
                    <?php endif; ?>
					<?php echo get_the_tag_list( '', ', ' ); ?></span>
			<?php endif; ?>
			<?php // Comments ?>
			<?php if ( $comments && comments_open() ) : ?>
                <span class="meta-comment">
                    <?php if ( $icons == 1 ) : ?>
                        <i class="organey-icon-comment"></i>
                    <?php endif; ?>
					<?php if ( $labels == 1 ) : ?>
                        <span class="label"><?php esc_html_e( 'Comments', 'organey' ); ?> </span>
					<?php endif; ?>
                    <a class="comment-link"
                       href="<?php echo esc_url( get_comments_link() ); ?>"><?php if ( $labels == 1 ) : echo esc_html( _n( 'Comment', 'Comments', get_comments_number(), 'organey' ) . ': ' ); endif; ?><?php echo number_format_i18n( get_comments_number() ); ?></a>
                </span>
			<?php endif; ?>

		<?php endif;
	}
}

if ( ! function_exists( 'organey_account_dropdown' ) ) {
	function organey_account_dropdown() { ?>
		<?php if ( has_nav_menu( 'my-account' ) ) : ?>
            <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Dashboard', 'organey' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'my-account',
					'menu_class'     => 'account-links-menu',
					'depth'          => 1,
				) );
				?>
            </nav><!-- .social-navigation -->
		<?php else: ?>
            <ul class="account-dashboard">

				<?php if ( organey_is_woocommerce_activated() ): ?>
                    <li>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" title="<?php esc_attr_e( 'Dashboard', 'organey' ); ?>"><?php esc_html_e( 'Dashboard', 'organey' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" title="<?php esc_attr_e( 'Orders', 'organey' ); ?>"><?php esc_html_e( 'Orders', 'organey' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'downloads' ) ); ?>" title="<?php esc_attr_e( 'Downloads', 'organey' ); ?>"><?php esc_html_e( 'Downloads', 'organey' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" title="<?php esc_attr_e( 'Edit Address', 'organey' ); ?>"><?php esc_html_e( 'Edit Address', 'organey' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" title="<?php esc_attr_e( 'Account Details', 'organey' ); ?>"><?php esc_html_e( 'Account Details', 'organey' ); ?></a>
                    </li>
				<?php else: ?>
                    <li>
                        <a href="<?php echo esc_url( get_dashboard_url( get_current_user_id() ) ); ?>" title="<?php esc_attr_e( 'Dashboard', 'organey' ); ?>"><?php esc_html_e( 'Dashboard', 'organey' ); ?></a>
                    </li>
				<?php endif; ?>
                <li>
                    <a title="<?php esc_attr_e( 'Log out', 'organey' ); ?>" class="tips" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Log Out', 'organey' ); ?></a>
                </li>
            </ul>
		<?php endif;

	}
}

if ( ! function_exists( 'organey_header_account_side' ) ) {
	function organey_header_account_side() {
		?>
        <div class="site-account-side side-wrap">
            <a href="#" class="close-account-side close-side"></a>
            <div class="account-side-heading side-heading">
                <span class="account-side-title side-title"><?php echo esc_html__( 'Sign in', 'organey' ); ?></span>
            </div>
			<?php organey_form_login(); ?>
        </div>
        <div class="wishlist-side-overlay side-overlay"></div>
		<?php
	}
}

if ( ! function_exists( 'organey_form_login' ) ) {
	function organey_form_login() { ?>

        <form class="organey-login-form-ajax" data-toggle="validator">
            <label>
                <span class="label"><?php esc_attr_e( 'Username or email', 'organey' ); ?> <span class="required">*</span></span>
                <input name="username" type="text" required>
            </label>
            <label>
                <span class="label"><?php esc_attr_e( 'Password', 'organey' ); ?> <span class="required">*</span></span>
                <input name="password" type="password" required>
            </label>
            <button type="submit" data-button-action class="button"><?php esc_html_e( 'Login', 'organey' ) ?></button>
            <input type="hidden" name="action" value="organey_login">
			<?php wp_nonce_field( 'ajax-organey-login-nonce', 'security-login' ); ?>
        </form>
        <a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" class="lostpass-link" title="<?php esc_attr_e( 'Lost your password?', 'organey' ); ?>"><?php esc_attr_e( 'Lost your password?', 'organey' ); ?></a>
        <div class="login-form-hook">
			<?php do_action( 'organey_loggin_customs' ); ?>
        </div>
        <div class="login-form-bottom">
            <span class="create-account-text"><?php echo esc_html__( 'No account yet?', 'organey' ); ?></span>
			<?php
			if ( organey_is_woocommerce_activated() && get_option( 'woocommerce_enable_myaccount_registration' ) == "yes" ) { ?>
                <a class="register-link" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" title="<?php esc_attr_e( 'Register', 'organey' ); ?>"><?php esc_attr_e( 'Create an Account', 'organey' ); ?></a>
			<?php } else { ?>
                <a class="register-link" href="<?php echo esc_url( wp_registration_url() ); ?>" title="<?php esc_attr_e( 'Register', 'organey' ); ?>"><?php esc_attr_e( 'Create an Account', 'organey' ); ?></a>
			<?php } ?>
        </div>

		<?php
	}
}

if ( ! function_exists( 'organey_mobile_nav_button' ) ) {
	function organey_mobile_nav_button() {
		if ( isset( get_nav_menu_locations()['handheld'] ) || isset( get_nav_menu_locations()['vertical'] ) ) {
			wp_enqueue_script( 'organey-nav-mobile' );
			?>
            <a href="#" class="menu-mobile-nav-button">
                <span class="toggle-text screen-reader-text"><?php echo esc_attr( apply_filters( 'organey_menu_toggle_text', esc_html__( 'Menu', 'organey' ) ) ); ?></span>
                <i class="organey-icon-bars"></i>
            </a>
			<?php
		}
	}
}

if ( ! function_exists( 'organey_mobile_nav' ) ) {
	function organey_mobile_nav() {
		if ( isset( get_nav_menu_locations()['handheld'] ) || isset( get_nav_menu_locations()['vertical'] ) ) {
			?>
            <div class="organey-mobile-nav">
                <div class="menu-scroll-mobile">
                    <a href="#" class="mobile-nav-close"><i class="organey-icon-times"></i></a>
					<?php
					organey_mobile_navigation();
					?>
                </div>
				<?php if ( organey_is_elementor_activated() ) {
					organey_language_switcher_mobile();
				} ?>
            </div>
            <div class="organey-overlay"></div>
			<?php
		}
	}
}
if ( ! function_exists( 'organey_mobile_navigation' ) ) {
	/**
	 * Display Handheld Navigation
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function organey_mobile_navigation() {
		?>
        <div class="mobile-nav-tabs">
            <ul>
				<?php if ( isset( get_nav_menu_locations()['handheld'] ) ) { ?>
                    <li class="mobile-tab-title mobile-pages-title active" data-menu="pages">
                        <span><?php echo esc_html( get_term( get_nav_menu_locations()['handheld'], 'nav_menu' )->name ); ?></span>
                    </li>
				<?php } ?>
				<?php if ( isset( get_nav_menu_locations()['vertical'] ) ) { ?>
                    <li class="mobile-tab-title mobile-categories-title" data-menu="categories">
                        <span><?php echo esc_html( get_term( get_nav_menu_locations()['vertical'], 'nav_menu' )->name ); ?></span>
                    </li>
				<?php } ?>
            </ul>
        </div>
        <nav class="mobile-menu-tab mobile-navigation mobile-pages-menu active" aria-label="<?php esc_html_e( 'Mobile Navigation', 'organey' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'handheld',
					'container_class' => 'handheld-navigation',
				)
			);
			?>
        </nav>
        <nav class="mobile-menu-tab mobile-navigation-categories mobile-categories-menu" aria-label="<?php esc_html_e( 'Mobile Navigation', 'organey' ); ?>">
			<?php
			$args = apply_filters( 'organey_nav_menu_args', [
				'fallback_cb'     => '__return_empty_string',
				'theme_location'  => 'vertical',
				'container_class' => 'handheld-navigation',
			] );

			wp_nav_menu( $args );
			?>
        </nav>
		<?php
	}
}

if ( ! function_exists( 'organey_language_switcher_mobile' ) ) {
	function organey_language_switcher_mobile() {
		$languages = apply_filters( 'wpml_active_languages', [] );
		if ( organey_is_wpml_activated() && count( $languages ) > 0  ) { ?>
            <div class="organey-language-switcher-mobile">
                <ul class="menu">
                    <li class="item">
                        <div class="language-switcher-head">
                            <img src="<?php echo esc_url( $languages[ ICL_LANGUAGE_CODE ]['country_flag_url'] ) ?>"
                                 alt="<?php esc_attr( $languages[ ICL_LANGUAGE_CODE ]['default_locale'] ) ?>">
                        </div>
                    </li>
					<?php foreach ( $languages as $key => $language ) {
						if ( ICL_LANGUAGE_CODE === $key ) {
							continue;
						} ?>
                        <li class="item">
                            <div class="language-switcher-img">
                                <a href="<?php echo esc_url( $language['url'] ) ?>">
                                    <img src="<?php echo esc_url( $language['country_flag_url'] ) ?>"
                                         alt="<?php esc_attr( $language['default_locale'] ) ?>">
                                </a>
                            </div>
                        </li>
					<?php } ?>
                </ul>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'organey_language_switcher' ) ) {
	function organey_language_switcher() {
		$languages = apply_filters( 'wpml_active_languages', [] );
		if ( organey_is_wpml_activated() && count( $languages ) > 0 ) {
			?>
            <div class="organey-language-switcher">
                <ul class="menu">
                    <li class="item">
                        <div class="language-switcher-head">
                            <img src="<?php echo esc_url( $languages[ ICL_LANGUAGE_CODE ]['country_flag_url'] ) ?>" alt="<?php esc_attr( $languages[ ICL_LANGUAGE_CODE ]['default_locale'] ) ?>">
                            <span class="label">
                                    <?php echo esc_html__( 'Language:', 'organey' ); ?>
                                </span>
                            <span class="title">
                                    <?php echo esc_html( $languages[ ICL_LANGUAGE_CODE ]['translated_name'] ); ?>
                                </span>
                        </div>
                        <ul class="sub-item">
							<?php
							foreach ( $languages as $key => $language ) {
								if ( ICL_LANGUAGE_CODE === $key ) {
									continue;
								}
								?>
                                <li>
                                    <a href="<?php echo esc_url( $language['url'] ) ?>">
                                        <img width="18" height="12" src="<?php echo esc_url( $language['country_flag_url'] ) ?>" alt="<?php esc_attr( $language['default_locale'] ) ?>">
                                        <span><?php echo esc_html( $language['translated_name'] ); ?></span>
                                    </a>
                                </li>
								<?php
							}
							?>
                        </ul>
                    </li>
                </ul>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'organey_header_search_popup' ) ) {
	function organey_header_search_popup() {
		?>
        <div class="site-search-popup">
            <div class="site-search-popup-wrap">
                <a href="#" class="site-search-popup-close"></a>
				<?php
				if ( organey_is_woocommerce_activated() ) {
					organey_product_search();
				} else {
					?>
                    <div class="site-search">
						<?php get_search_form(); ?>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'organey_header_search_button' ) ) {
	function organey_header_search_button() {
		add_action( 'wp_footer', 'organey_header_search_popup', 1 );
		?>
        <div class="site-header-search">
            <a href="#" class="button-search-popup"><i class="organey-icon-search"></i></a>
        </div>
		<?php
	}
}

if ( ! function_exists( 'organey_social_share' ) ) {
	function organey_social_share() {
		if ( organey_get_theme_option( 'social_share' ) ) {
			?>
            <div class="organey-social-share">
				<?php if ( organey_get_theme_option( 'social_share_facebook' ) ): ?>
                    <a class="social-facebook"
                       href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&display=page"
                       target="_blank" title="<?php esc_html_e( 'Share on facebook', 'organey' ); ?>">
                        <i class="organey-icon-facebook"></i>
                        <span><?php esc_html_e( 'Facebook', 'organey' ); ?></span>
                    </a>
				<?php endif; ?>

				<?php if ( organey_get_theme_option( 'social_share_twitter' ) ): ?>
                    <a class="social-twitter"
                       href="http://twitter.com/home?status=<?php esc_attr( get_the_title() ); ?> <?php the_permalink(); ?>" target="_blank"
                       title="<?php esc_html_e( 'Share on Twitter', 'organey' ); ?>">
                        <i class="organey-icon-twitter"></i>
                        <span><?php esc_html_e( 'Twitter', 'organey' ); ?></span>
                    </a>
				<?php endif; ?>

				<?php if ( organey_get_theme_option( 'social_share_linkedin' ) ): ?>
                    <a class="social-linkedin"
                       href="http://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>"
                       target="_blank" title="<?php esc_html_e( 'Share on LinkedIn', 'organey' ); ?>">
                        <i class="organey-icon-linkedin"></i>
                        <span><?php esc_html_e( 'Linkedin', 'organey' ); ?></span>
                    </a>
				<?php endif; ?>
				<?php if ( organey_get_theme_option( 'social_share_pinterest' ) ): ?>
                    <a class="social-pinterest"
                       href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink() ); ?>&amp;description=<?php echo urlencode( get_the_title() ); ?>&amp;; ?>"
                       target="_blank" title="<?php esc_html_e( 'Share on Pinterest', 'organey' ); ?>">
                        <i class="organey-icon-pinterest-p"></i>
                        <span><?php esc_html_e( 'Pinterest', 'organey' ); ?></span>
                    </a>
				<?php endif; ?>

				<?php if ( organey_get_theme_option( 'social_share_email' ) ): ?>
                    <a class="social-envelope" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"
                       title="<?php esc_html_e( 'Email to a Friend', 'organey' ); ?>">
                        <i class="organey-icon-envelope"></i>
                        <span><?php esc_html_e( 'Email', 'organey' ); ?></span>
                    </a>
				<?php endif; ?>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'organey_single_author' ) ) {
	function organey_single_author() {
		get_template_part( 'template-parts/author-bio' );
	}
}

if ( ! function_exists( 'organey_replace_categories_list' ) ) {
	function organey_replace_categories_list( $output, $args ) {
		if ( $args['show_count'] = 1 ) {
			$pattern     = '#<li([^>]*)><a([^>]*)>(.*?)<\/a>\s*<span\s*class="count">\(([0-9]*)\)\s*</span>#i';
			$replacement = '<li$1><a$2><span class="cat-name">$3</span> <span class="cat-count">$4</span></a>';
			$output      = preg_replace( $pattern, $replacement, $output );
			$pattern     = '#<li([^>]*)><a([^>]*)>(.*?)<\/a>\s*\(([0-9]*)\)\s*#i';  // removed ( and )
			$replacement = '<li$1><a$2><span class="cat-name">$3</span> <span class="cat-count">$4</span></a>';

			return preg_replace( $pattern, $replacement, $output );
		}

		return $output;
	}
}

add_filter( 'wp_list_categories', 'organey_replace_categories_list', 10, 2 );

if ( ! function_exists( 'organey_replace_archive_list' ) ) {
	function organey_replace_archive_list( $link_html, $url, $text, $format, $before, $after, $selected ) {
		if ( $format == 'html' ) {
			$pattern     = '#<li><a([^>]*)>(.*?)<\/a>&nbsp;\s*\(([0-9]*)\)\s*#i';  // removed ( and )
			$replacement = '<li><a$1><span class="archive-name">$2</span> <span class="archive-count">$3</span></a>';

			return preg_replace( $pattern, $replacement, $link_html );
		}

		return $link_html;
	}
}

add_filter( 'get_archives_link', 'organey_replace_archive_list', 10, 7 );

