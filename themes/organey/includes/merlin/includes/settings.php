<?php

class Organey_Setup_Setting_Page {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			'Custom Setup Settings',
			'Custom Setup Settings',
			'manage_options',
			'custom-setup-settings',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'organey_options_setup' );
		?>
        <div class="wrap">
            <h1><?php esc_html_e('Custom Setup Themes', 'organey') ?></h1>
            <form method="post" action="<?php esc_url(admin_url('options-general.php?page=custom-setup-settings')) ?>">
                <table class="form-table">
                    <tr>
                        <th>
                            <label><?php esc_html_e('Setup Themes', 'organey') ?></label>
                        </th>
                        <td>
                            <fieldset>
                                <ul>
                                    <li>
                                        <label><?php esc_html_e('Header', 'organey') ?>:
                                            <select name="header">
                                                <option value="home-1">Header 1</option>
                                            </select>
                                        </label>
                                    </li>
                                    <li>
                                        <label><?php esc_html_e('Footer', 'organey') ?>:
                                            <select name="footer">
                                                <option value="footer-1">Footer 1</option>
                                            </select>
                                        </label>
                                    </li>
                                </ul>
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <?php submit_button(esc_html('Setup Now!')); ?>
            </form>
        </div>
		<?php
	}

}

if ( is_admin() ) {
	$my_settings_page = new Organey_Setup_Setting_Page();
}
