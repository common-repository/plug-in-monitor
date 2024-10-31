<?php

namespace Plug\Monitor\Plugins;

// Don't call the file directly.
defined( 'ABSPATH' ) or die( "Hey! You can't access this file, you silly human!" );

/**
 * Plugin Insight Monitor handler class
 */
class PluginMonitor {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		// Hook to update plugin update info when the plugin is updated.
		add_action( 'upgrader_process_complete', array( $this, 'check_plugin_upgrader_process_complete' ), 2, 20 );

		// Hook to update plugin info when activated.
		add_action( 'activated_plugin', array( $this, 'check_plugin_activated_complete' ), 1, 20 );
	}

	/**
	 * Check Plugin Upgrader Process Completion.
	 *
	 * Updates the information about a plugin after an upgrade process completes.
	 *
	 * @param object $upgrader The upgrader object.
	 * @param array  $options  The options for the upgrader.
	 * @return void
	 */
	public function check_plugin_upgrader_process_complete( $upgrader, $options ) {
		// Check if the update is for a plugin and if plugin information is available.
		if ( isset( $options['type'] ) && $options['type'] === 'plugin' && isset( $options['plugins'][0] ) ) {
			// Get plugin data.
			$plugin_info = $upgrader->skin->plugin_info;

			// Get the current date.
			$update_date = gmdate( 'd/m/Y' );

			// Create the update information array.
			$update_info = array(
				'last_updated_date' => $update_date,
				'old_version'       => $plugin_info['Version'],
			);

			// Retrieve existing plugin update information or initialize an empty array.
			$plugins_update_info = get_option( 'plug_monitor_version_monitor', array() );

			// Update the plugin's update information.
			$plugins_update_info[ $options['plugins'][0] ] = $update_info;

			// Store the updated plugin update information.
			update_option( 'plug_monitor_version_monitor', $plugins_update_info );
		}
	}

	/**
	 * Check Plugin Activation Completion
	 *
	 * Updates the information about a plugin after it is activated.
	 *
	 * @param string $plugin The path to the main plugin file.
	 * @return void
	 */
	public function check_plugin_activated_complete( $plugin ) {
		// Get plugin data.
		$plugin_info = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

		// Get the current date.
		$update_date = gmdate( 'd/m/Y' );

		// Create the update information array.
		$update_info = array(
			'last_updated_date' => $update_date,
			'old_version'       => $plugin_info['Version'],
		);

		// Retrieve existing plugin update information or initialize an empty array.
		$plugins_update_info = get_option( 'plug_monitor_version_monitor', array() );

		// Update the plugin's update information.
		$plugins_update_info[ $plugin ] = $update_info;

		// Store the updated plugin update information.
		update_option( 'plug_monitor_version_monitor', $plugins_update_info );
	}
}
