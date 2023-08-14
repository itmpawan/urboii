<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Organey_Customize')) {

    class Organey_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {
            require_once get_theme_file_path('includes/customizer/customize-control/editor.php');
            /**
             * Theme options.
             */

            $this->init_organey_blog($wp_customize);

            $this->init_organey_social($wp_customize);

            if (organey_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('organey_customize_register', $wp_customize);
        }

        public function init_organey_social($wp_customize) {

            $wp_customize->add_section('organey_social', array(
                'title' => esc_html__('Socials', 'organey'),
            ));

            $wp_customize->add_setting('organey_options_social_share', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'organey_social',
                'label'   => esc_html__('Show Social Share', 'organey'),
            ));
            $wp_customize->add_setting('organey_options_social_share_facebook', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'organey_social',
                'label'   => esc_html__('Share on Facebook', 'organey'),
            ));
            $wp_customize->add_setting('organey_options_social_share_twitter', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'organey_social',
                'label'   => esc_html__('Share on Twitter', 'organey'),
            ));
            $wp_customize->add_setting('organey_options_social_share_linkedin', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'organey_social',
                'label'   => esc_html__('Share on Linkedin', 'organey'),
            ));
            $wp_customize->add_setting('organey_options_social_share_google-plus', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_setting('organey_options_social_share_pinterest', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'organey_social',
                'label'   => esc_html__('Share on Pinterest', 'organey'),
            ));
            $wp_customize->add_setting('organey_options_social_share_email', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'organey_social',
                'label'   => esc_html__('Share on Email', 'organey'),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_organey_blog($wp_customize) {

            $wp_customize->add_section('organey_blog_archive', array(
                'title' => esc_html__('Blog', 'organey'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('organey_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_blog_style', array(
                'section' => 'organey_blog_archive',
                'label'   => esc_html__('Blog style', 'organey'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'organey'),
                    'grid'     => esc_html__('Blog Grid', 'organey'),
                ),
            ));

            $wp_customize->add_setting('organey_options_blog_sidebar', array(
                'type'              => 'option',
                'default'           => 'right',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_blog_sidebar', array(
                'section' => 'organey_blog_archive',
                'label'   => esc_html__('Sidebar Position', 'organey'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'organey'),
                    'right' => esc_html__('Right', 'organey'),

                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'organey'),
            ));

            $wp_customize->add_section('organey_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'organey'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            $wp_customize->add_setting('organey_options_woocommerce_archive_layout', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_woocommerce_archive_layout', array(
                'section' => 'organey_woocommerce_archive',
                'label'   => esc_html__('Layout Style', 'organey'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Sidebar', 'organey'),
                    'canvas'   => esc_html__('Canvas Filter', 'organey'),
                ),
            ));

            $wp_customize->add_setting('organey_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('organey_options_woocommerce_archive_sidebar', array(
                'section' => 'organey_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'organey'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'organey'),
                    'right' => esc_html__('Right', 'organey'),
                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('organey_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'organey'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('organey_options_single_product_gallery_layout', array(
                'type'              => 'option',
                'default'           => 'horizontal',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('organey_options_single_product_gallery_layout', array(
                'section' => 'organey_woocommerce_single',
                'label'   => esc_html__('Style', 'organey'),
                'type'    => 'select',
                'choices' => array(
                    'horizontal' => esc_html__('Horizontal', 'organey'),
                    'vertical'   => esc_html__('Vertical', 'organey'),
                    'gallery'    => esc_html__('Gallery', 'organey'),
                    'sticky'     => esc_html__('Sticky', 'organey'),
                ),
            ));

            $wp_customize->add_setting('organey_options_single_product_content_meta', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'organey_sanitize_editor',
                'transport'         => 'refresh'
            ));

            $wp_customize->add_control(new Organey_Customize_Control_Editor($wp_customize, 'organey_options_single_product_content_meta', array(
                'section' => 'organey_woocommerce_single',
                'label'   => esc_html__('Single extra description', 'organey'),
            )));


            // =========================================
            // Product
            // =========================================

            $wp_customize->add_section('organey_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'organey'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('organey_options_wocommerce_block_style', array(
                'type'              => 'option',
                'default'           => '0',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('organey_options_wocommerce_block_style', array(
                'section' => 'organey_woocommerce_product',
                'label'   => esc_html__('Style', 'organey'),
                'type'    => 'select',
                'choices' => array(
                    '0' => esc_html__('Default', 'organey'),
                ),
            ));

            $wp_customize->add_setting('organey_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('organey_options_woocommerce_product_hover', array(
                'section' => 'organey_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'organey'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__( 'None', 'organey' ),
                    'bottom-to-top' => esc_html__( 'Bottom to Top', 'organey' ),
                    'top-to-bottom' => esc_html__( 'Top to Bottom', 'organey' ),
                    'right-to-left' => esc_html__( 'Right to Left', 'organey' ),
                    'left-to-right' => esc_html__( 'Left to Right', 'organey' ),
                    'swap'          => esc_html__( 'Swap', 'organey' ),
                    'fade'          => esc_html__( 'Fade', 'organey' ),
                    'zoom-in'       => esc_html__( 'Zoom In', 'organey' ),
                    'zoom-out'      => esc_html__( 'Zoom Out', 'organey' ),
                ),
            ));

            $wp_customize->add_setting('organey_options_wocommerce_columns_mobile', array(
                'type'              => 'option',
                'default'           => '2',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('organey_options_wocommerce_columns_mobile', array(
                'section' => 'woocommerce_product_catalog',
                'label'   => esc_html__('Products per row on mobile', 'organey'),
                'description' => __( 'How many products should be shown per row on mobile?', 'organey' ),
                'type'    => 'select',
                'choices' => array(
                    '1' => esc_html__('1 ', 'organey'),
                    '2' => esc_html__('2', 'organey'),
                ),
            ));

        }
    }
}
return new Organey_Customize();
