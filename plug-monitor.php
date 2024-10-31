<?php
/**
 * PlugMonitor
 *
 * @package           PlugMonitor
 * @author            Abdul Hadi
 * @copyright         2024 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Monitor
 * Plugin URI:        https://wordpress.org/plugins/plug-in-monitor/
 * Description:       Plugin Monitor: Your WordPress plugins toolbox.
 * Version:           1.1.2
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Abdul Hadi
 * Author URI:        https://www.linkedin.com/in/abdulhadicse/
 * Text Domain:       plug-monitor
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
if ( ! class_exists( 'Plug_Monitor', false ) ) :
	final class Plug_Monitor {
		/**
		 * Plugin version 1.0
		 *
		 * @var string
		 */
		const version = '1.1.2';

		/**
		 * Holds various class instances
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		private $container = array();

		/**
		 * Class constructor
		 */
		private function __construct() {
			$this->define_constants();

			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		}

		/**
		 * Magic getter to bypass referencing objects
		 *
		 * @param string $prop
		 *
		 * @since 1.0.0
		 *
		 * @return Class Instance
		 */
		public function __get( $prop ) {
			if ( array_key_exists( $prop, $this->container ) ) {
				return $this->container[ $prop ];
			}
		}

		/**
		 * Instances all the classes
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function init_classes() {
			$this->container['pluginData']    = new Plug\Monitor\Plugins\PluginData();
			$this->container['pluginMonitor'] = new Plug\Monitor\Plugins\pluginMonitor();
		}

		/**
		 * Initializes a singleton instance
		 *
		 * @return \Plug_Monitor
		 */
		public static function init() {
			static $instance = false;

			if ( ! $instance ) {
				$instance = new self();
			}

			return $instance;
		}

		/**
		 * Define the required plugin constants
		 *
		 * @return void
		 */
		public function define_constants() {
			define( 'PLUG_MONITOR_VERSION', self::version );
			define( 'PLUG_MONITOR_FILE', __FILE__ );
			define( 'PLUG_MONITOR_PATH', __DIR__ );
			define( 'PLUG_MONITOR_URL', plugins_url( '', PLUG_MONITOR_FILE ) );
			define( 'PLUG_MONITOR_ASSETS', PLUG_MONITOR_URL . '/assets' );
		}

		/**
		 * Initialize the plugin
		 *
		 * @return void
		 */
		public function init_plugin() {
			// Initialize the classes.
			$this->init_classes();

			// Register assets.
			new Plug\Monitor\Assets();
			new Plug\Monitor\Api();
			new Plug\Monitor\Dispatcher();

			if ( is_admin() ) {
				new Plug\Monitor\Admin();
			}
		}

		/**
		 * Do stuff upon plugin activation
		 *
		 * @return void
		 */
		public function activate() {
			$installed = get_option( 'plug_monitor_installed' );

			if ( ! $installed ) {
				update_option( 'plug_monitor_installed', time() );
			}

			update_option( 'plug_monitor_version', PLUG_MONITOR_VERSION );
		}
	}
endif;
/**
 * Initializes the main plugin
 *
 * @return \Plug_Monitor
 */
function plug_monitor() {
	if ( class_exists( 'Plug_Monitor' ) ) {
		return Plug_Monitor::init();
	}
}

// kick-off the plugin.
plug_monitor();
