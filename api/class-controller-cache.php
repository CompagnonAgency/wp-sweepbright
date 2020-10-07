<?php

/**
 * WP_SweepBright_Controller_Cache.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Cache
 */

class WP_SweepBright_Controller_Cache {

	public function __construct() {
	}

	public function init() {
    unlink(plugin_dir_path( __DIR__ ). 'cache/cache.json');

    // Output
    return rest_ensure_response([
      'STATUS_CODE' => http_response_code(200),
    ]);
	}

}
