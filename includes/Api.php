<?php

namespace Plug\Monitor;

/**
 * Manager Class
 */
class Api {

	/**
	 * All API Classes
	 *
	 * @var array
	 */
	protected $classes;

	/**
	 * Initialize
	 */
	public function __construct() {
		$this->classes = array(
			Api\Plugins::class,
		);

		add_action( 'rest_api_init', array( $this, 'init_api' ) );
	}

	/**
	 * Register APIs
	 *
	 * @return void
	 */
	public function init_api() {
		foreach ( $this->classes as $class ) {
			$object = new $class();
			$object->register_routes();
		}
	}
}
