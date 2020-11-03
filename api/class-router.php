<?php

/**
 * WP_SweepBright_Router.
 *
 * This provides all of the API routes.
 *
 * @package    WP_SweepBright_Router
 */

class WP_SweepBright_Router
{

	public function __construct()
	{
		// The hooks should be available at all times,
		// So it can be loaded by a cron job
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-hook.php';

		// REST routes
		add_action('rest_api_init', function () {
			register_rest_route('v1/sweepbright', '/hook', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::hook',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/estates/(?P<estate_id>[^/]+)', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::estate',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/estates/(?P<estate_id>[^/]+)/contacts', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::contact_request_estate',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/contacts', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::contact_request_general',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/list', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::list',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/logs', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::logs',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/property/(?P<estate_id>[^/]+)', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::property',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/property/(?P<estate_id>[^/]+)/units', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::units',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/property/(?P<estate_id>[^/]+)/units_paged/(?P<page>[^/]+)', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::units_paged',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/property/(?P<estate_id>[^/]+)/save', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::save_property',
				'permission_callback' => '__return_true',
			));
		});
	}

	public static function hook($data)
	{
		$wp_sweepbright_controller_hook = new WP_SweepBright_Controller_Hook();
		return $wp_sweepbright_controller_hook->init($data);
	}

	public static function estate($data)
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-estate.php';
		$wp_sweepbright_controller_estate = new WP_SweepBright_Controller_Estate();
		return $wp_sweepbright_controller_estate->init($data);
	}

	public static function logs($data)
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-logs.php';
		$wp_sweepbright_controller_logs = new WP_SweepBright_Controller_Logs();
		return $wp_sweepbright_controller_logs->init($data);
	}

	public static function property($data)
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-property.php';
		$wp_sweepbright_controller_property = new WP_SweepBright_Controller_property();
		return $wp_sweepbright_controller_property->init($data);
	}

	public static function units($data)
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-property.php';
		$wp_sweepbright_controller_property = new WP_SweepBright_Controller_property();
		return $wp_sweepbright_controller_property->units($data);
	}

	public static function units_paged($data)
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-property.php';
		$wp_sweepbright_controller_property = new WP_SweepBright_Controller_property();
		return $wp_sweepbright_controller_property->units_paged($data);
	}

	public static function save_property($data)
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-property.php';
		$wp_sweepbright_controller_property = new WP_SweepBright_Controller_property();
		return $wp_sweepbright_controller_property->save($data);
	}

	public static function contact_request_estate($data)
	{
		$estate_id = $data['estate_id'];

		WP_SweepBright_Helpers::log([
			'estate_title' => get_the_title(WP_SweepBright_Helpers::get_post_ID_from_estate($estate_id)),
			'post_id' => $estate_id,
			'action' => 'contact_request_estate',
			'status' => 'Received',
			'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
		]);
	}

	public static function contact_request_general($data)
	{
		WP_SweepBright_Helpers::log([
			'estate_title' => '-',
			'post_id' => false,
			'action' => 'contact_request_general',
			'status' => 'Received',
			'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
		]);
	}

	public static function list($data)
	{
		header('Content-Type: application/json');
		return WP_SweepBright_Query::list($data);
	}
}
