<?php
define( 'ORGANEY_VERSION', '2.9.0' );

require_once get_theme_file_path( 'includes/class-tgm-plugin-activation.php' );
require_once get_theme_file_path( 'includes/merlin/vendor/autoload.php' );
require_once get_theme_file_path( 'includes/merlin/class-merlin.php' );
require_once get_theme_file_path( 'includes/merlin/class-import.php' );
require_once get_theme_file_path( 'includes/functions.php' );
require_once get_theme_file_path( 'includes/template-functions.php' );
require_once get_theme_file_path( 'includes/class-main.php' );
require_once get_theme_file_path( 'includes/customizer/class-customize.php' );

if ( defined( 'ELEMENTOR_VERSION' ) ) {
	require_once get_theme_file_path( 'includes/class-elementor.php' );
	require_once get_theme_file_path( 'includes/megamenu/megamenu.php' );
}

if ( function_exists( 'hfe_init' ) ) {
	require get_theme_file_path( 'includes/header-footer-elementor/class-hfe.php' );
	require get_theme_file_path( 'includes/merlin/includes/breadcrumb.php' );
}

if ( class_exists( 'WooCommerce' ) ) {
	require_once get_theme_file_path( 'includes/woocommerce/woocommerce-functions.php' );
	require_once get_theme_file_path( 'includes/woocommerce/class-woocommerce-adjacent-products.php' );
	require_once get_theme_file_path( 'includes/woocommerce/class-woocommerce.php' );
	require_once get_theme_file_path( 'includes/woocommerce/woocommerce-template-functions.php' );
	require_once get_theme_file_path( 'includes/woocommerce/woocommerce-template-hooks.php' );
	require_once get_theme_file_path( 'includes/woocommerce/class-woocommerce-settings.php' );
	require get_theme_file_path( 'includes/woocommerce/class-woocommerce-brand.php' );
	require get_theme_file_path( 'includes/merlin/includes/class-wc-widget-product-brands.php' );
}

require_once get_theme_file_path('includes/merlin-ext/class-extension.php');

if ( ! is_user_logged_in() ) {
	require get_theme_file_path( 'includes/modules/class-login.php' );
}
