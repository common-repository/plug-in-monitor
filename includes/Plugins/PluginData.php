<?php

namespace Plug\Monitor\Plugins;

// don't call the file directly
defined( 'ABSPATH' ) or die( "Hey! You can't access this file, you silly human!" );

/**
 * Class PluginData
 *
 * A class to retrieve data related to plugins.
 */
class PluginData {
	/**
	 * Retrieves all installed plugins.
	 *
	 * @return array An associative array containing all installed plugins.
	 */
	public function getAllPlugins() {
		return get_plugins();
	}

	/**
	 * Retrieves the list of active plugins.
	 *
	 * @return array An array containing the paths to active plugins.
	 */
	public function getActivePlugins() {
		return get_option( 'active_plugins' );
	}

	/**
	 * Retrieves information about available plugin updates.
	 *
	 * @return object|bool An object containing information about available plugin updates, or false if none are available.
	 */
	public function getPluginUpdates() {
		return get_site_transient( 'update_plugins' );
	}

	/**
	 * Retrieves information about plugin updates last monitored.
	 *
	 * @return array An associative array containing information about plugin updates last monitored.
	 */
	public function getPluginsUpdateInfo() {
		return get_option( 'plug_monitor_version_monitor', array() );
	}

	/**
	 * Retrieves counts of plugins in different statuses.
	 *
	 * @return array An associative array containing counts of plugins in different statuses.
	 */
	public function getPluginCounts() {
		$all_plugins    = $this->getAllPlugins();
		$active_plugins = $this->getActivePlugins();
		$plugin_updates = $this->getPluginUpdates();

		$plugin_counts = array(
			'all'             => count( $all_plugins ),
			'active'          => count( $active_plugins ),
			'inactive'        => ( count( $all_plugins ) - count( $active_plugins ) ),
			'updateAvailable' => count( $plugin_updates->response ),
		);

		return $plugin_counts;
	}

	/**
	 * Get plugin columns.
	 *
	 * @return array
	 */
	public function getPluginColumns() {
		$columns = array(
			array(
				'id'        => 'pluginName',     // Unique identifier for the column.
				'name'      => __( 'Plugin Name', 'plug-monitor' ),  // The display name of the column, translatable.
				'columnGap' => 2.5,   // The gap width for the column (for layout purposes). Default : 1.
				'textWrap'  => true,   // Indicates whether text should wrap within the column. Possible values: true (text wraps), false (text doesn't wrap). Default : false.
				'align'     => 'left',  // The alignment of text within the column. Possible values: 'right' (align text to the right), 'left' (align text to the left). Default : left.
			),

			array(
				'id'        => 'activeVersion',
				'name'      => __( 'Active Version', 'plug-monitor' ),
				'columnGap' => 1,
				'textWrap'  => false,
				'align'     => 'left',
			),
			array(
				'id'        => 'newVersion',
				'name'      => __( 'New Version', 'plug-monitor' ),
				'columnGap' => 1,
				'textWrap'  => false,
				'align'     => 'left',
			),
			array(
				'id'        => 'lastUpdated',
				'name'      => __( 'Last Updated', 'plug-monitor' ),
				'columnGap' => 1,
				'textWrap'  => false,
				'align'     => 'left',
			),
			array(
				'id'        => 'oldVersion',
				'name'      => __( 'Old Version', 'plug-monitor' ),
				'columnGap' => 1,
				'textWrap'  => false,
				'align'     => 'left',
			),
			array(
				'id'        => 'status',
				'name'      => __( 'Status', 'plug-monitor' ),
				'columnGap' => 1,
				'textWrap'  => false,
				'align'     => 'left',
			),
			array(
				'id'        => 'updateAvailable',
				'name'      => __( 'Update Available', 'plug-monitor' ),
				'columnGap' => 1,
				'textWrap'  => false,
				'align'     => 'left',
			),
		);

		return apply_filters( 'pmonitor_column_names', $columns );
	}
}
