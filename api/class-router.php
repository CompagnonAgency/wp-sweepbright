<?php

/**
 * WP_SweepBright_Router.
 *
 * This provides all of the API routes.
 *
 * @package    WP_SweepBright_Router
 */

class WP_SweepBright_Router {

	public function __construct() {
		// The hooks should be available at all times,
		// So it can be loaded by a cron job
		require_once plugin_dir_path( __DIR__ ). 'api/class-controller-hook.php';

		// REST routes
		add_action('rest_api_init', function () {
		  register_rest_route('v1/sweepbright', '/hook', array(
		    'methods' => 'POST',
		    'callback' => __CLASS__ . '::hook',
		  ));

			register_rest_route('v1/sweepbright', '/estates/(?P<estate_id>[^/]+)', array(
		    'methods' => 'GET',
				'callback' => __CLASS__ . '::estate',
		  ));

			register_rest_route('v1/sweepbright', '/estates/(?P<estate_id>[^/]+)/contacts', array(
		    'methods' => 'POST',
				'callback' => __CLASS__ . '::contact_request_estate',
		  ));

			register_rest_route('v1/sweepbright', '/contacts', array(
		    'methods' => 'POST',
				'callback' => __CLASS__ . '::contact_request_general',
			));
			
			register_rest_route('v1/sweepbright', '/list', array(
		    'methods' => 'GET',
				'callback' => __CLASS__ . '::list',
		  ));
		});
	}

	public static function hook($data) {
		$wp_sweepbright_controller_hook = new WP_SweepBright_Controller_Hook();
		return $wp_sweepbright_controller_hook->init($data);
	}

	public static function estate($data) {
		require_once plugin_dir_path( __DIR__ ). 'api/class-controller-estate.php';
		$wp_sweepbright_controller_estate = new WP_SweepBright_Controller_Estate();
		return $wp_sweepbright_controller_estate->init($data);
	}

	public static function contact_request_estate($data) {
	  $estate_id = $data['estate_id'];

		WP_SweepBright_Helpers::log([
			'estate_title' => get_the_title(WP_SweepBright_Helpers::get_post_ID_from_estate($estate_id)),
			'post_id' => $estate_id,
			'action' => 'contact_request_estate',
			'status' => 'Received',
			'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
		]);
	}

	public static function contact_request_general($data) {
		WP_SweepBright_Helpers::log([
			'estate_title' => '-',
			'post_id' => false,
			'action' => 'contact_request_general',
			'status' => 'Received',
			'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
		]);
	}

	public static function list($data) {
		header('Content-Type: application/json');

		return WP_SweepBright_Query::list([
			'json' => true,
			'ajax' => true,
			'params' => $data,
		]);
	}

}
