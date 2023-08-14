<?php

defined('ABSPATH') || exit();


class Organey_Megamenu {

    private $is_megamenu = false;
    private $menu_items = [];

    public function __construct() {
        $this->includes_core();

        $this->includes();
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('organey_nav_menu_args', [$this, 'set_menu_args'], 99999);


    }

    public function set_menu_args($args) {
        $args['walker'] = new Organey_Megamenu_Walker();

        return $args;
    }

    private function includes_core() {
        if (is_admin()) {
            include_once get_template_directory() . '/includes/megamenu/includes/admin/class-admin.php';
        }
        include_once get_template_directory() . '/includes/megamenu/includes/core-functions.php';
    }

    private function includes() {

        include_once get_template_directory() . '/includes/megamenu/includes/hook-functions.php';
        include_once get_template_directory() . '/includes/megamenu/includes/class-menu-walker.php';
    }

    public function enqueue_scripts() {

        wp_enqueue_script('organey-megamenu-frontend', get_template_directory_uri() . '/includes/megamenu/assets/js/frontend.js', array('jquery'), ORGANEY_VERSION, true);

        foreach ($this->menu_items as $id) {
            Elementor\Core\Files\CSS\Post::create($id)->enqueue();
        }
    }

}

return new Organey_Megamenu();
