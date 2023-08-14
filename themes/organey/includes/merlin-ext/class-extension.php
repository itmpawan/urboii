<?php
if (!class_exists('Organey_Theme_WooCommerce_Ext')):
    class Organey_Theme_WooCommerce_Ext {

        private static $instance;

        protected $active_kit, $elementor_settings;

        public static function get_instance() {
            if (null === static::$instance) {
                static::$instance = new static();
            }

            return static::$instance;
        }

        public function __construct() {
            $this->includes();
            add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);

            add_action('admin_post_organey_save_settings', [$this, 'save_settings']);
            add_action('admin_menu', array($this, 'admin_menu'), 1);
            add_action('admin_bar_menu', [$this, 'add_menu_bar'], 100);
        }


        public function add_menu_bar() {
            /**
             * @global WP_Admin_Bar $wp_admin_bar
             */
            global $wp_admin_bar;
            $wp_admin_bar->add_menu(array(
                'id'     => 'organey_theme_options', // an unique id (required)
                'parent' => '', // false for a top level menu
                'title'  => '⚡ Organey Theme Options', // title/menu text to display
                'href'   => admin_url('admin.php?page=leebrosus'), // target url of this menu item
            ));
        }

        public function admin_scripts() {
            wp_enqueue_style('organey-backend', get_theme_file_uri('assets/css/backend.css'));

            wp_enqueue_style('wp-color-picker');

            wp_enqueue_script('organey-backend', get_theme_file_uri('assets/js/backend.js'),
                [
                    'jquery',
                    'wp-color-picker',
                    'jquery-ui-sortable'
                ],
                ORGANEY_VERSION, true);
        }

        public function init_settings() {
            if (organey_is_elementor_activated()) {
                $this->active_kit         = Elementor\Plugin::$instance->kits_manager->get_active_id();
                $this->elementor_settings = get_post_meta($this->active_kit, '_elementor_page_settings', true);
            }
        }

        public function save_settings() {
            $retrieved_nonce = $_REQUEST['_wpnonce'];
            if (!wp_verify_nonce($retrieved_nonce, 'organey_save_settings')) {
                die('Failed security check');
            }
            $this->init_settings();
            $this->setup_setting_uncheck();
            $this->elementor_settings = $this->setup_elementor_setting($_REQUEST['organey_elementor'], $this->elementor_settings);
            $this->save_options();

            update_post_meta($this->active_kit, '_elementor_page_settings', $this->elementor_settings);
            if(organey_is_elementor_activated()){
                \Elementor\Plugin::instance()->files_manager->clear_cache();
            }

            do_action('organey_save_settings');
            wp_redirect($_REQUEST['_wp_http_referer']);
            exit();
        }

        public function admin_menu() {
            add_menu_page(
                'Organey Theme',
                'Organey Theme',
                'manage_options',
                'leebrosus',
                array($this, 'setting_page'),
                get_theme_file_uri('assets/images/theme-icon.svg'),
                60
            );
        }

        public function save_options() {
            $options = $_REQUEST['organey_options'];
            foreach ($options as $key => $value) {
                organey_set_theme_option($key, $value);
            }

            // Main Option
            $main_options = $_REQUEST['main_options'];
            foreach ($main_options as $key => $value) {
                update_option($key, $value);
            }
            $this->elementor_settings = $this->setup_elementor_setting($main_options, $this->elementor_settings);
        }

        private function setup_setting_uncheck() {
            $list_options = apply_filters('organey_ext_list_option_uncheck', [
                'social_share',
                'social_share_facebook',
                'social_share_twitter',
                'social_share_linkedin',
                'social_share_pinterest',
                'social_share_email',
                'enable_photo_review',
                'enable_live_view',
                'enable_brand',
                'enable_sticky_add_to_cart',
                'enable_quick_view',
                'enable_estimate_delivery',
                'enable_cookie_notice',
                'enable_ask_a_question',
                'enable_back_to_top',
                'enable_product_notification',
                'enable_popup_subscribe'
            ]);
            foreach ($list_options as $key) {
                if (!isset($_REQUEST['organey_options'][$key])) {
                    $_REQUEST['organey_options'][$key] = 'no';
                }
            }
        }

        private function setup_elementor_setting($array1, $array2) {
            foreach ($array1 as $key => $value) {
                if (is_array($value)) {
                    $array2[$key] = $this->setup_elementor_setting($value, $array2[$key]);
                } else {
                    if ($value) {
                        $array2[$key] = $value;
                    }
                }
            }

            return $array2;
        }

        private function get_elementor_setting($key, $default = '') {
            if (isset($this->elementor_settings[$key])) {
                return $this->elementor_settings[$key];
            }

            return $default;
        }

        public function setting_page() {
            $this->init_settings();
            $system_color  = isset($this->elementor_settings['system_colors']) ? $this->elementor_settings['system_colors'] : false;
            $content_width = isset($this->elementor_settings['container_width']) ? $this->elementor_settings['container_width']['size'] : false;
            ?>
            <div class="lb_settings_page wrap">
                <h1 class="settings_page_title">Organey <?php esc_html_e('Settings', 'organey'); ?></h1>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <h2 class="nav-tab-wrapper">
                        <a href="#organey-general" class="nav-tab nav-tab-active"><?php esc_html_e('General', 'organey'); ?></a>
                        <a href="#organey-blog" class="nav-tab"><?php esc_html_e('Blog', 'organey'); ?></a>
                        <?php if (organey_is_woocommerce_activated()) { ?>
                            <a href="#organey-woocommerce" class="nav-tab"><?php esc_html_e('WooCommerce', 'organey'); ?></a>
                        <?php } ?>
                        <a href="#organey-license" class="nav-tab"><?php esc_html_e('License', 'organey'); ?></a>
                    </h2>
                    <div class="lb_settings_page_content">
                        <table id="organey-general" class="form-table active">
                            <?php do_action('organey_settings_table_general_before'); ?>
                            <tr class="heading">
                                <th colspan="2">
                                    <?php echo esc_html__('Layout', 'organey') ?>
                                </th>
                            </tr>
                            <?php if ($content_width): ?>
                                <tr>
                                    <th><?php echo esc_html__('Content Width', 'organey') ?>:</th>
                                    <td>
                                        <input type="number" name="organey_elementor[container_width][size]" value="<?php echo esc_attr($content_width); ?>">
                                        <span class="description"><?php esc_html_e('Sets the default width of the content area (Default: 1140)', 'organey'); ?></span>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($system_color): ?>
                                <tr class="heading">
                                    <th colspan="2">
                                        <?php echo esc_html__('Colors', 'organey') ?>
                                    </th>
                                </tr>
                                <?php foreach ($system_color as $key => $color): ?>
                                    <tr>
                                        <th><?php echo esc_html($color['title']) ?>:</th>
                                        <td>
                                            <input type="text" name="organey_elementor[system_colors][<?php echo esc_attr($key) ?>][color]" value="<?php echo esc_attr($color['color']); ?>" class="organey-color-picker">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php do_action('organey_settings_table_general_after'); ?>
                            <tr class="submit">
                                <td colspan="2">
                                    <?php wp_nonce_field('organey_save_settings'); ?>
                                    <input type="hidden" name="action" value="organey_save_settings">
                                    <?php submit_button(esc_html('Save Change')); ?>
                                </td>
                            </tr>
                        </table>
                        <table id="organey-blog" class="form-table">
                            <?php do_action('organey_settings_table_blog_before'); ?>
                            <tr>
                                <th><?php esc_html_e('Blog Style', 'organey') ?>:</th>
                                <td>
                                    <select name="organey_options[blog_style]">
                                        <option value="standard" <?php selected('standard', get_option('organey_options_blog_style', 'standard')); ?>><?php esc_html_e('Standard', 'organey'); ?></option>
                                        <option value="grid" <?php selected('grid', get_option('organey_options_blog_style', 'standard')); ?>><?php esc_html_e('Grid', 'organey'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Sidebar Position', 'organey') ?>:</th>
                                <td>
                                    <select name="organey_options[blog_sidebar]">
                                        <option value="left" <?php selected('left', get_option('organey_options_blog_sidebar')); ?>><?php esc_html_e('Left', 'organey'); ?></option>
                                        <option value="right" <?php selected('right', get_option('organey_options_blog_sidebar')); ?>><?php esc_html_e('Right', 'organey'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <?php do_action('organey_settings_table_blog_after'); ?>
                            <tr class="submit">
                                <td colspan="2">
                                    <?php submit_button(esc_html('Save Change')); ?>
                                </td>
                            </tr>
                        </table>
                        <?php if (organey_is_woocommerce_activated()) { ?>
                            <table id="organey-woocommerce" class="form-table">
                                <?php do_action('organey_settings_table_woocommerce_before'); ?>
                                <tr class="heading">
                                    <th colspan="2">
                                        <?php echo esc_html__('Product Catalog', 'organey') ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Product Hover Effect', 'organey') ?>:</th>
                                    <td>
                                        <?php $woocommerce_product_hover = organey_get_theme_option('woocommerce_product_hover'); ?>
                                        <select name="organey_options[woocommerce_product_hover]">
                                            <option value="fade" <?php selected('fade', $woocommerce_product_hover); ?>><?php esc_html_e('Fade', 'organey'); ?></option>
                                            <option value="zoom-in" <?php selected('zoom-in', $woocommerce_product_hover); ?>><?php esc_html_e('Zoom in', 'organey'); ?></option>
                                            <option value="swap" <?php selected('swap', $woocommerce_product_hover); ?>><?php esc_html_e('Swap', 'organey'); ?></option>
                                            <option value="top-to-bottom" <?php selected('top-to-bottom', $woocommerce_product_hover); ?>><?php esc_html_e('Top to bottom', 'organey'); ?></option>
                                            <option value="bottom-to-top" <?php selected('bottom-to-top', $woocommerce_product_hover); ?>><?php esc_html_e('Bottom to top', 'organey'); ?></option>
                                            <option value="left-to-right" <?php selected('left-to-right', $woocommerce_product_hover); ?>><?php esc_html_e('Left to right', 'organey'); ?></option>
                                            <option value="right-to-left" <?php selected('right-to-left', $woocommerce_product_hover); ?>><?php esc_html_e('Right to left', 'organey'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Shop page display', 'organey') ?>:</th>
                                    <td>
                                        <?php $woocommerce_shop_page_display = get_option('woocommerce_shop_page_display') ?>
                                        <select name="main_options[woocommerce_shop_page_display]">
                                            <option value="" <?php selected('', $woocommerce_shop_page_display); ?>><?php esc_html_e('Show products', 'organey'); ?></option>
                                            <option value="subcategories" <?php selected('subcategories', $woocommerce_shop_page_display); ?>><?php esc_html_e('Show categories', 'organey'); ?></option>
                                            <option value="both" <?php selected('both', $woocommerce_shop_page_display); ?>><?php esc_html_e('Show categories & products', 'organey'); ?></option>
                                        </select>
                                        <span class="description"><?php esc_html_e('Choose what to display on the main shop page.', 'organey'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Shop page display', 'organey') ?>:</th>
                                    <td>
                                        <?php $woocommerce_category_archive_display = get_option('woocommerce_category_archive_display') ?>
                                        <select name="main_options[woocommerce_category_archive_display]">
                                            <option value="" <?php selected('', $woocommerce_category_archive_display); ?>><?php esc_html_e('Show products', 'organey'); ?></option>
                                            <option value="subcategories" <?php selected('subcategories', $woocommerce_category_archive_display); ?>><?php esc_html_e('Show categories', 'organey'); ?></option>
                                            <option value="both" <?php selected('both', $woocommerce_category_archive_display); ?>><?php esc_html_e('Show categories & products', 'organey'); ?></option>
                                        </select>
                                        <span class="description"><?php esc_html_e('Choose what to display on product category pages.', 'organey'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Default product sorting', 'organey') ?>:</th>
                                    <td>
                                        <?php $woocommerce_default_catalog_orderby = get_option('woocommerce_default_catalog_orderby', 'menu_order') ?>
                                        <select name="main_options[woocommerce_default_catalog_orderby]">
                                            <option value="menu_order" <?php selected('menu_order', $woocommerce_default_catalog_orderby); ?>><?php esc_html_e('Default sorting (custom ordering + name)', 'organey'); ?></option>
                                            <option value="popularity" <?php selected('popularity', $woocommerce_default_catalog_orderby); ?>><?php esc_html_e('Popularity (sales)', 'organey'); ?></option>
                                            <option value="rating" <?php selected('rating', $woocommerce_default_catalog_orderby); ?>><?php esc_html_e('Average rating', 'organey'); ?></option>
                                            <option value="date" <?php selected('date', $woocommerce_default_catalog_orderby); ?>><?php esc_html_e('Sort by most recent', 'organey'); ?></option>
                                            <option value="price" <?php selected('price', $woocommerce_default_catalog_orderby); ?>><?php esc_html_e('Sort by price (asc)', 'organey'); ?></option>
                                            <option value="price-desc" <?php selected('price-desc', $woocommerce_default_catalog_orderby); ?>><?php esc_html_e('Sort by price (desc)', 'organey'); ?></option>
                                        </select>
                                        <span class="description"><?php esc_html_e('How should products be sorted in the catalog by default?', 'organey'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Products per row', 'organey') ?>:</th>
                                    <td>
                                        <div class="organey-group-devices">
                                            <div class="list-devices">
                                                <i class="eicon-device-desktop active" data-device="desktop"></i>
                                                <i class="eicon-device-laptop" data-device="laptop"></i>
                                                <i class="eicon-device-tablet eicon-tilted" data-device="tablet"></i>
                                                <i class="eicon-device-tablet" data-device="tablet-extra"></i>
                                                <i class="eicon-device-mobile eicon-tilted" data-device="mobile-extra"></i>
                                                <i class="eicon-device-mobile" data-device="mobile"></i>
                                            </div>
                                            <div class="organey-device desktop active">
                                                <span><?php echo esc_html__('Desktop', 'organey') ?>:</span>
                                                <input type="number" min="1" step="1" name="main_options[woocommerce_catalog_columns]" value="<?php echo esc_attr(get_option('woocommerce_catalog_columns')) ?>">
                                            </div>
                                            <div class="organey-device laptop">
                                                <span><?php echo esc_html__('Laptop', 'organey') ?>:</span>
                                                <input type="number" min="1" step="1" name="main_options[woocommerce_catalog_columns_laptop]" value="<?php echo esc_attr($this->get_elementor_setting('woocommerce_catalog_columns_laptop')) ?>">
                                            </div>
                                            <div class="organey-device tablet-extra">
                                                <span><?php echo esc_html__('Tablet Extra', 'organey') ?>:</span>
                                                <input type="number" min="1" step="1" name="main_options[woocommerce_catalog_columns_tablet_extra]" value="<?php echo esc_attr($this->get_elementor_setting('woocommerce_catalog_columns_tablet_extra')) ?>">
                                            </div>
                                            <div class="organey-device tablet">
                                                <span><?php echo esc_html__('Tablet', 'organey') ?>:</span>
                                                <input type="number" min="1" step="1" name="main_options[woocommerce_catalog_columns_tablet]" value="<?php echo esc_attr($this->get_elementor_setting('woocommerce_catalog_columns_tablet')) ?>">
                                            </div>
                                            <div class="organey-device mobile-extra">
                                                <span><?php echo esc_html__('Mobile Extra', 'organey') ?>:</span>
                                                <input type="number" min="1" step="1" name="main_options[woocommerce_catalog_columns_mobile_extra]" value="<?php echo esc_attr($this->get_elementor_setting('woocommerce_catalog_columns_mobile_extra')) ?>">
                                            </div>
                                            <div class="organey-device mobile">
                                                <span><?php echo esc_html__('Mobile', 'organey') ?>:</span>
                                                <input type="number" min="1" step="1" name="main_options[woocommerce_catalog_columns_mobile]" value="<?php echo esc_attr($this->get_elementor_setting('woocommerce_catalog_columns_mobile')) ?>">
                                            </div>
                                        </div>
                                        <span class="description"><?php esc_html_e('How many products should be shown per row?', 'organey'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Rows per page', 'organey') ?>:</th>
                                    <td>
                                        <input type="number" min="1" step="1" name="main_options[woocommerce_catalog_rows]" value="<?php echo esc_attr(get_option('woocommerce_catalog_rows')) ?>">
                                        <span class="description"><?php esc_html_e('How many rows of products should be shown per page?', 'organey'); ?></span>
                                    </td>
                                </tr>
                                <?php do_action('organey_settings_table_woocommerce_after'); ?>
                                <tr class="submit">
                                    <td colspan="2">
                                        <?php submit_button(esc_html('Save Change')); ?>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                        <table id="organey-license" class="form-table">
                            <tr>
                                <th><?php esc_html_e('License', 'organey') ?>:</th>
                                <td>
                                    <input type="text" name="organey_options[theme_license]" value="<?php echo esc_attr(organey_get_theme_option('theme_license', '')) ?>">
                                </td>
                            </tr>
                            <tr class="submit">
                                <td colspan="2">
                                    <?php submit_button(esc_html('Save Change')); ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
            <?php
        }

        private function includes() {
            $list_exts = glob(get_theme_file_path('includes/merlin-ext/*/*.php'));
            foreach ($list_exts as $file) {
                $name_folder = basename(dirname($file));
                $file_path   = get_theme_file_path('includes/merlin-ext/' . $name_folder . '/' . $name_folder . '.php');
                if (file_exists($file_path)) {
                    $this->exts[] = $name_folder;
                    require_once $file_path;
                }
            }
        }
    }

endif;

Organey_Theme_WooCommerce_Ext::get_instance();

