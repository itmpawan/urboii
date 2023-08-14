<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Organey_Elementor_Account extends Elementor\Widget_Base {
    public function get_name() {
        return 'organey-account';
    }

    public function get_title() {
        return esc_html__('Organey Account', 'organey');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return array('organey-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'account-config',
            [
                'label' => esc_html__('Config', 'organey'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'account_icon',
            [
                'label'   => esc_html__('Icon', 'organey'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'organey-icon- organey-icon-user',
                    'library' => 'organey',
                ],
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
            ]
        );

        $this->add_responsive_control(
            'account_alignment',
            [
                'label'       => esc_html__('Alignment', 'organey'),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'left',
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
                    '{{WRAPPER}} .site-header-account' => 'text-align: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'account-style',
            [
                'label' => esc_html__('Style', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_account_size',
            [
                'label'     => esc_html__('Size Icon', 'organey'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .site-header-account > a i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Icon Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-account > a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Icon Color Hover', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-account > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'style-content',
            [
                'label'        => esc_html__('Position Hover', 'organey'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'left',
                'options'      => [
                    'left'  => esc_html__('Left', 'organey'),
                    'right' => esc_html__('Right', 'organey'),
                ],
                'prefix_class' => 'header-account-content-'
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'account-content',
            [
                'label' => esc_html__('content', 'organey'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__('Color', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-account .account-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'organey'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-account > a:hover .account-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'site-header-account');
        ?>
        <div <?php echo organey_get_render_attribute_string('wrapper', $this); ?>>
            <?php
            $link_url  = "#";
            $html_attr = 'data-toggle=button-side data-target=.site-account-side';
            if (!is_user_logged_in()) {
                add_action('wp_footer', 'organey_header_account_side');
                if (!empty($settings['link']['url'])) {
                    $link_url = $settings['link']['url'];
                }

            }
            ?>
            <a <?php echo is_user_logged_in() ? 'class="my-account-icon"' : esc_attr($html_attr); ?> href="<?php echo esc_url($link_url); ?>">
                <span class="account-user">
                <?php if( ! is_user_logged_in() ){
                    if (!empty($settings['account_icon'])) {
                        \Elementor\Icons_Manager::render_icon($settings['account_icon'], ['aria-hidden' => 'true']);
                    }
                }else{
                    $user_id = get_current_user_id();
                    echo get_avatar( $user_id, 32 );
                } ?>
                </span>
                <span class="account-content">
                    <?php if ( ! is_user_logged_in() ){
                        echo esc_html__('Login', 'organey');
                    }else{
                        $user = wp_get_current_user();
                        echo esc_html( $user->display_name );
                    } ?>
                </span>
            </a>
            <?php if (is_user_logged_in()) { ?>
                <div class="account-dropdown">
                    <div class="account-wrap">
                        <div class="account-inner dashboard">
                            <?php organey_account_dropdown(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php

    }
}

$widgets_manager->register(new Organey_Elementor_Account());



