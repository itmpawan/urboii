<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!organey_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;

class Organey_Elementor_Handheld_Footer extends Elementor\Widget_Base {
    public function get_name() {
        return 'organey-handheld-footer';
    }

    public function get_title() {
        return esc_html__('Organey Handheld Footer', 'organey');
    }

    public function get_icon() {
        return 'eicon-footer';
    }

    public function get_categories() {
        return array('organey-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'footer_setting',
            [
                'label' => esc_html__('Setting', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $options = [
            'custom' => esc_html__('Custom', 'organey'),
            'search' => esc_html__('Search', 'organey'),
        ];

        if (organey_is_woocommerce_activated()) {
            $options['shop']      = esc_html__('Shop', 'organey');
            $options['myaccount'] = esc_html__('My account', 'organey');
            $options['wishlist']  = esc_html__('Wishlist', 'organey');
        }

        $this->add_control('footer_layout',
            [
                'label'   => esc_html__('Type', 'organey'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'search',
                'options' => $options
            ]
        );

        $this->add_control('title',
            [
                'label'     => esc_html__('Heading', 'organey'),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Heading',
                'condition' => [
                    'footer_layout' => 'custom'
                ]
            ]
        );

        $this->add_control('icon',
            [
                'label'     => esc_html__('Heading', 'organey'),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'organey-icon- organey-icon-user',
                    'library' => 'organey',
                ],
                'condition' => [
                    'footer_layout' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'link',
            [
                'label'       => esc_html__('Link', 'organey'),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'organey'),
                'default'     => [
                    'url' => '#',
                ],
                'condition'   => [
                    'footer_layout' => 'custom'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'     => esc_html__('Size Icon', 'organey'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .footer-handheld a i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_size',
            [
                'label'     => esc_html__('Size Title', 'organey'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .footer-handheld a .title' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'aligrment',
            [
                'label'       => esc_html__('Alignment', 'organey'),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'options'     => [
                    'left'   => [
                        'title' => esc_html__('Left', 'organey'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'organey'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'organey'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'label_block' => false,
                'selectors'   => [
                    '{{WRAPPER}} .footer-handheld a' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_controls_settings();
        $layout   = $settings['footer_layout'];

        $link_url = "#";
        if (!empty($settings['link']['url'])) {
            $link_url = $settings['link']['url'];
        }

        switch ($layout) {
            case 'shop':
                ?>
                <div class="footer-handheld">
                    <a class="footer-shop" href="<?php echo esc_url(get_permalink(get_option('woocommerce_shop_page_id'))); ?>">
                        <i class="organey-icon-store"></i>
                        <span class="title"><?php echo esc_html__('Shop', 'organey'); ?></span>
                    </a>
                </div>
                <?php
                break;
            case 'myaccount':
                $html_attr = 'data-toggle=button-side data-target=.site-account-side';
                ?>
                <div class="footer-handheld">
                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" <?php echo is_user_logged_in() ?: esc_attr($html_attr); ?>>
                        <i class="organey-icon-user"></i>
                        <span class="title"><?php echo esc_html__('My Account', 'organey'); ?></span>
                    </a>
                </div>
                <?php
                break;
            case 'search':
                add_action('wp_footer', 'organey_header_search_popup', 1);
                ?>
                <div class="footer-handheld">
                    <a class="button-search-popup" href="#">
                        <i class="organey-icon-search"></i>
                        <span class="title"><?php echo esc_html__('Search', 'organey'); ?></span>
                    </a>
                </div>
                <?php
                break;
            case 'wishlist':
                if (function_exists('woosw_init')) {
                    $key = WPCleverWoosw::get_key();

                    ?>
                    <div class="footer-handheld woosw-check">
                        <a class="footer-wishlist" data-toggle="button-side" data-target=".site-wishlist-side" href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)); ?>">
                            <i class="organey-icon-favourite"></i>
                            <span class="title"><?php echo esc_html__('Wishlist', 'organey'); ?></span>
                            <span class="count"><?php echo esc_html(WPCleverWoosw::get_count($key)); ?></span>
                        </a>
                    </div>
                    <?php
                }
                break;
            default:
                ?>
                <div class="footer-handheld">
                    <a href="<?php echo esc_url($link_url); ?>">
                        <?php
                        if (!empty($settings['icon'])) {
                            ?>
                            <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                            <?php
                        }
                        ?>
                        <span class="title"><?php echo esc_html($settings['title']); ?></span>
                    </a>
                </div>
                <?php
                break;

        }
    }
}

$widgets_manager->register(new Organey_Elementor_Handheld_Footer());



