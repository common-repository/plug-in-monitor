<?php

namespace Plug\Monitor;

// don't call the file directly
defined( 'ABSPATH' ) or die( "Hey! You can't access this file, you silly human!" );

/**
 * Assets handler class
 */
class Assets {
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
	}

	/**
	 * All available scripts
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_scripts() {
		return array(
			'app-script' => array(
				'src'     => PLUG_MONITOR_URL . '/dist/js/index.js',
				'version' => filemtime( PLUG_MONITOR_PATH . '/dist/js/index.js' ),
				'deps'    => array( 'wp-api-fetch', 'wp-i18n' ),
			),
		);
	}

	/**
	 * All available styles
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_styles() {
		return array(
			'app-style' => array(
				'src'     => PLUG_MONITOR_ASSETS . '/css/app.css',
				'version' => filemtime( PLUG_MONITOR_PATH . '/assets/css/app.css' ),
			),
		);
	}

	/**
	 * Register scripts and styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_assets() {
		$scripts = $this->get_scripts();
		$styles  = $this->get_styles();

		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;

			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $deps, $style['version'] );
		}
	}
}
