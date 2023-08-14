<?php
/**
 * The template for displaying header.
 *
 * @package Organey
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description', 'display' );
?>
<header class="site-header" role="banner">
    <div class="container">
        <div class="header-wrap">
            <div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} elseif ( $site_name ) {
					?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'organey' ); ?>" rel="home">
							<?php echo esc_html( $site_name ); ?>
                        </a>
                    </h1>
                    <p class="site-description">
						<?php
						if ( $tagline ) {
							echo esc_html( $tagline );
						}
						?>
                    </p>
				<?php } ?>
            </div>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
                <nav class="site-navigation" role="navigation">
                    <button class="menu-toggle"><span><?php echo esc_html__( 'Menu', 'organey' ); ?></span></button>
					<?php
					wp_nav_menu( [
						'theme_location'  => 'primary',
						'container_class' => 'primary-navigation',
					] );
					?>
                </nav>
			<?php endif; ?>
        </div>
    </div>
</header>
