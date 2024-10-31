<?php

namespace Plug\Monitor\Api;

use WP_REST_Server;

class Plugins extends Base {

	/**
	 * Initialize
	 *
	 * @return void
	 */
	public function __construct() {
		$this->namespace = 'pmonitor/v1';
		$this->rest_base = 'plugins';
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'admin_permissions_check' ),
					'args'                => array(),
				),
			)
		);
	}

	/**
	 * Retrieves a list of plugins.
	 *
	 * @param WP_Rest_Request $request
	 *
	 * @return WP_Rest_Response|WP_Error
	 */
	public function get_items( $request ) {
		// Get all columns.
		$column_names = plug_monitor()->pluginData->getPluginColumns();

		// Get counts of plugins in different statuses.
		$plugin_counts = plug_monitor()->pluginData->getPluginCounts();

		// Get plugin data as needed.
		$all_plugins         = plug_monitor()->pluginData->getAllPlugins();
		$active_plugins      = plug_monitor()->pluginData->getActivePlugins();
		$plugin_updates      = plug_monitor()->pluginData->getPluginUpdates();
		$plugins_update_info = plug_monitor()->pluginData->getPluginsUpdateInfo();

		$rows = array();

		// Iterate through all plugins to generate rows.
		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			$new_version = isset( $plugin_updates->response[ $plugin_file ]->new_version ) ? $plugin_updates->response[ $plugin_file ]->new_version : '-';

			$rows[] = array(
				'pluginName'      => $plugin_data['Name'],
				'activeVersion'   => isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : __( 'Version not specified', 'plug-monitor' ),
				'newVersion'      => $new_version,
				'lastUpdated'     => isset( $plugins_update_info[ $plugin_file ] ) ? $plugins_update_info[ $plugin_file ]['last_updated_date'] : '-',
				'oldVersion'      => isset( $plugins_update_info[ $plugin_file ] ) ? $plugins_update_info[ $plugin_file ]['old_version'] : '-',
				'status'          => in_array( $plugin_file, $active_plugins, true ) ? __( 'Active', 'plug-monitor' ) : __( 'Inactive', 'plug-monitor' ),
				'updateAvailable' => ( '-' !== $new_version ) ? 'Yes' : 'No',
				'slug'            => ltrim( dirname( $plugin_file ), '/' ),
			);
		}

		$plugin_data = apply_filters( 'pmonitor_rows_data', $rows, $all_plugins, $active_plugins, $plugin_updates, $plugins_update_info );

		// Prepare responses.
		$response = array(
			'counts'  => $plugin_counts,
			'columns' => $column_names,
			'data'    => $plugin_data,
		);

		return rest_ensure_response( $response );
	}
}
