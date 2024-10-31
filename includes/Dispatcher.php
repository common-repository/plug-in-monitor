<?php

namespace Plug\Monitor;

/**
 * Dispatcher Class
 */
class Dispatcher {

	/**
	 * Initialize
	 */
	public function __construct() {
		new Plugins\PluginMonitor();
	}
}
