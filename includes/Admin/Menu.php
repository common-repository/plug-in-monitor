<?php

namespace Plug\Monitor\Admin;

/**
 * Menu Class
 */
class Menu {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	/**
	 * Register admin menu
	 *
	 * @return void
	 */
	public function register_menu() {
		// Add submenu page under the Plugins menu.
		$menu = add_submenu_page(
			'plugins.php',
			'Plugin Monitor',
			'Plugin Monitor',
			'manage_options',
			'plugin-monitor',
			array( $this, 'render_page' )
		);

		add_action( 'admin_print_scripts-' . $menu, array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Render the page
	 *
	 * @return void
	 */
	public function render_page() {
		echo '<div id="pm-app"></div>';
	}

	/**
	 * Enqueue JS and CSS
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// enqueue scripts and styles.
		wp_enqueue_script( 'app-script' );
		wp_enqueue_style( 'app-style' );

		// localize script.
		wp_localize_script(
			'app-script',
			'wpApiSettings',
			array(
				'root'  => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	/**
	 * Get the localize script
	 *
	 * @return array
	 */
	public function localize_script() {
		// To do.
	}
}
