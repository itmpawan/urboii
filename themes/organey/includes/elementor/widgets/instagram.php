<?php


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!function_exists('sb_instagram_feed_init')) {
    return;
}

use Elementor\Controls_Manager;


class Organey_Elementor_Intagram extends Elementor\Widget_Base {

    public function get_name() {
        return 'organey-instagram';
    }

    public function get_title() {
        return esc_html__('Organey Instagram', 'organey');
    }


    public function get_categories() {
        return array('organey-addons');
    }

    public function get_script_depends() {
        return ['organey-elementor-instagram', 'slick'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Settings', 'organey'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'num',
            [
                'label'   => esc_html__('Number of Photos', 'organey'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => '1',
                'max'     => '33'
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'          => esc_html__('Number of Columns', 'organey'),
                'type'           => \Elementor\Controls_Manager::SELECT,
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'options'        => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => esc_html__('Padding around Images', 'organey'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .row'         => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
                    '{{WRAPPER}} .column-item' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2); margin-bottom: calc({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label'        => esc_html__('Style', 'organey'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'default'      => 'style-1',
                'options'      =>
                    [
                        'style-1' => esc_html__('Style 1', 'organey'),
                        'style-2' => esc_html__('Style 2', 'organey'),
                        'style-3' => esc_html__('Style 3', 'organey'),
                    ],
                'prefix_class' => 'elementor-instagram-'
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel([
            'style' => 'style-1'
        ]);

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-instagram-wrapper');

        $this->add_render_attribute('row', 'class', 'row');

        if ($settings['enable_carousel'] === 'yes') {
            $this->add_render_attribute('row', 'class', 'organey-carousel');
            $carousel_settings = $this->get_carousel_settings();
            $this->add_render_attribute('row', 'data-settings', wp_json_encode($carousel_settings));
        } else {

            if (!empty($settings['column'])) {
                $this->add_render_attribute('row', 'data-elementor-columns', $settings['column']);
            } else {
                $this->add_render_attribute('row', 'data-elementor-columns', 1);
            }

            if (!empty($settings['column_tablet'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-tablet', $settings['column_tablet']);
            } else {
                $this->add_render_attribute('row', 'data-elementor-columns-tablet', 1);
            }

            if (!empty($settings['column_mobile'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-mobile', $settings['column_mobile']);
            } else {
                $this->add_render_attribute('row', 'data-elementor-columns-mobile', 1);
            }
        }


        $atts                    = [];
        $database_settings       = sbi_get_database_settings();
        $instagram_feed_settings = new SB_Instagram_Settings($atts, $database_settings);

        if (empty($database_settings['connected_accounts']) && empty($atts['accesstoken'])) {
            ob_start(); ?>
            <div id="sbi_mod_error" <?php echo current_user_can('manage_instagram_feed_options') ? ' style="display: block;"' : ''; ?>>
                <span><?php _e('This error message is only visible to WordPress admins', 'organey'); ?></span><br/>
                <p><b><?php _e('Error: No connected account.', 'organey'); ?></b></p>
                <p><?php _e('Please go to the Instagram Feed settings page to connect an account.', 'organey'); ?></p>
            </div>
            <?php
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

        $instagram_feed_settings->set_feed_type_and_terms();
        $instagram_feed_settings->set_transient_name();
        $transient_name      = $instagram_feed_settings->get_transient_name();
        $settings_i          = $instagram_feed_settings->get_settings();
        $feed_type_and_terms = $instagram_feed_settings->get_feed_type_and_terms();

        $instagram_feed = new SB_Instagram_Feed($transient_name);


        if ($database_settings['sbi_caching_type'] === 'background') {
            $instagram_feed->add_report('background caching used');
            if ($instagram_feed->regular_cache_exists()) {
                $instagram_feed->add_report('setting posts from cache');
                $instagram_feed->set_post_data_from_cache();
            }

            if ($instagram_feed->need_to_start_cron_job()) {
                $instagram_feed->add_report('setting up feed for cron cache');
                $to_cache = array(
                    'atts'           => $atts,
                    'last_requested' => time(),
                );

                $instagram_feed->set_cron_cache($to_cache, $instagram_feed_settings->get_cache_time_in_seconds());

                SB_Instagram_Cron_Updater::do_single_feed_cron_update($instagram_feed_settings, $to_cache, $atts, false);

                $instagram_feed->set_post_data_from_cache();

            } elseif ($instagram_feed->should_update_last_requested()) {
                $instagram_feed->add_report('updating last requested');
                $to_cache = array(
                    'last_requested' => time(),
                );

                $instagram_feed->set_cron_cache($to_cache, $instagram_feed_settings->get_cache_time_in_seconds(), $settings_i['backup_cache_enabled']);
            }

        } elseif ($instagram_feed->regular_cache_exists()) {
            $instagram_feed->add_report('page load caching used and regular cache exists');
            $instagram_feed->set_post_data_from_cache();

            if ($instagram_feed->need_posts($settings_i['num']) && $instagram_feed->can_get_more_posts()) {
                while ($instagram_feed->need_posts($settings_i['num']) && $instagram_feed->can_get_more_posts()) {
                    $instagram_feed->add_remote_posts($settings_i, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed());
                }
                $instagram_feed->cache_feed_data($instagram_feed_settings->get_cache_time_in_seconds(), $settings_i['backup_cache_enabled']);
            }

        } else {
            $instagram_feed->add_report('no feed cache found');

            while ($instagram_feed->need_posts($settings_i['num']) && $instagram_feed->can_get_more_posts()) {
                $instagram_feed->add_remote_posts($settings_i, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed());
            }

            if (!$instagram_feed->should_use_backup()) {
                $instagram_feed->cache_feed_data($instagram_feed_settings->get_cache_time_in_seconds(), $settings_i['backup_cache_enabled']);
            }

        }

        if ($instagram_feed->should_use_backup()) {
            $instagram_feed->add_report('trying to use backup');
            $instagram_feed->maybe_set_post_data_from_backup();
            $instagram_feed->maybe_set_header_data_from_backup();
        }

        // if need a header
        if ($instagram_feed->need_header($settings_i, $feed_type_and_terms)) {
            if ($instagram_feed->should_use_backup() && $settings_i['minnum'] > 0) {
                $instagram_feed->add_report('trying to set header from backup');
                $header_cache_success = $instagram_feed->maybe_set_header_data_from_backup();
            } elseif ($database_settings['sbi_caching_type'] === 'background') {
                $instagram_feed->add_report('background header caching used');
                $instagram_feed->set_header_data_from_cache();
            } elseif ($instagram_feed->regular_header_cache_exists()) {
                // set_post_data_from_cache
                $instagram_feed->add_report('page load caching used and regular header cache exists');
                $instagram_feed->set_header_data_from_cache();
            } else {
                $instagram_feed->add_report('no header cache exists');
                $instagram_feed->set_remote_header_data($settings_i, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed());
                $instagram_feed->cache_header_data($instagram_feed_settings->get_cache_time_in_seconds(), $settings_i['backup_cache_enabled']);
            }
        } else {
            $instagram_feed->add_report('no header needed');
        }

        $posts2 = $instagram_feed->get_post_data();

        if (empty($posts2) && !empty($instagram_feed_settings->get_connected_accounts_in_feed()) && $settings_i['minnum'] > 0) {
            return;
        }
        $header_data = !empty($instagram_feed->get_header_data()) ? $instagram_feed->get_header_data() : false;
        $username    = SB_Instagram_Parse::get_username($header_data);
        $posts       = array_slice($posts2, 0, $settings['num']);
        if ($settings['style'] == "style-3") {
            ?>
            <div class="instagram-header">
                <i class="organey-icon-instagram"></i>
                <h3 class="instagram-name">
                    <a href="https://www.instagram.com/<?php echo urlencode($username); ?>/" target="_blank" rel="noopener nofollow" title="@<?php echo esc_attr($username); ?>" class="sbi_header_link">@<?php echo esc_html($username); ?></a>
                </h3>
            </div>
            <?php
        }
        ?>
        <div <?php echo organey_elementor_get_render_attribute_string('wrapper', $this); // WPCS: XSS ok ?>>
            <div <?php echo organey_elementor_get_render_attribute_string('row', $this); // WPCS: XSS ok ?>>
                <?php


                $icon_type = 'svg';

                foreach ($posts as $post) {

                    $account_type        = SB_Instagram_Parse::get_account_type($post);
                    $post_id             = SB_Instagram_Parse::get_post_id($post);
                    $img_alt             = SB_Instagram_Parse::get_caption($post, sprintf(__('Instagram post %s', 'organey'), $post_id));
                    $media_type          = SB_Instagram_Parse::get_media_type($post);
                    $permalink           = SB_Instagram_Parse::get_permalink($post);
                    $maybe_carousel_icon = $media_type === 'carousel' ? SB_Instagram_Display_Elements::get_icon('carousel', $icon_type) : '';
                    $maybe_video_icon    = $media_type === 'video' ? SB_Instagram_Display_Elements::get_icon('video', $icon_type) : '';
                    $media_full_res      = SB_Instagram_Parse::get_media_url($post);

                    ?>
                    <div class="column-item">
                        <a class="sbi_photo" href="<?php echo esc_url($permalink); ?>" target="_blank"
                           rel="noopener nofollow">
                            <img src="<?php echo esc_url($media_full_res); ?>" alt="<?php echo esc_attr($img_alt); ?>">
                        </a>
                    </div>
                    <?php
                }

                ?>
            </div>
        </div>
        <?php

    }

    protected function add_control_carousel($condition = array()) {
        $this->start_controls_section(
            'section_carousel_options',
            [
                'label'     => esc_html__('Carousel Options', 'organey'),
                'type'      => Controls_Manager::SECTION,
                'condition' => $condition,
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable', 'organey'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );


        $this->add_control(
            'navigation',
            [
                'label'     => esc_html__('Navigation', 'organey'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dots',
                'options'   => [
                    'both'   => esc_html__('Arrows and Dots', 'organey'),
                    'arrows' => esc_html__('Arrows', 'organey'),
                    'dots'   => esc_html__('Dots', 'organey'),
                    'none'   => esc_html__('None', 'organey'),
                ],
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'     => esc_html__('Pause on Hover', 'organey'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'     => esc_html__('Autoplay', 'organey'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'     => esc_html__('Autoplay Speed', 'organey'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5000,
                'condition' => [
                    'autoplay'        => 'yes',
                    'enable_carousel' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
                ],
            ]
        );

        $this->add_control(
            'infinite',
            [
                'label'     => esc_html__('Infinite Loop', 'organey'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function get_carousel_settings() {
        $settings = $this->get_settings_for_display();

        return array(
            'navigation'         => $settings['navigation'],
            'autoplayHoverPause' => $settings['pause_on_hover'] === 'yes' ? true : false,
            'autoplay'           => $settings['autoplay'] === 'yes' ? true : false,
            'autoplayTimeout'    => $settings['autoplay_speed'],
            'items'              => $settings['column'],
            'items_tablet'       => $settings['column_tablet'] ? $settings['column_tablet'] : $settings['column'],
            'items_mobile'       => $settings['column_mobile'] ? $settings['column_mobile'] : 1,
            'loop'               => $settings['infinite'] === 'yes' ? true : false,
        );
    }

}

$widgets_manager->register(new Organey_Elementor_Intagram());
