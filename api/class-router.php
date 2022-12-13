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
		// REST routes
		add_action('rest_api_init', function () {
			register_rest_route('v1/sweepbright', '/hook', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::hook',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/publish', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::publish',
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

			register_rest_route('v1/sweepbright', '/pages/settings', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::pages_settings',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/settings/save', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_settings_save',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/setup', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_setup',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/list', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::pages_list',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/delete', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_delete',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/(?P<id>[^/]+)(?:/(?P<duplicate>\d+))?', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::pages_data',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/save', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_save',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/column', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_column',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/column/save', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_column_save',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/column/delete', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_column_delete',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/row', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_row',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/row/save', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_row_save',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/pages/row/delete', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_row_delete',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/theme/save', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::pages_theme_save',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/theme', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::pages_theme_data',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/favorites', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::favorites_list',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/favorites/update', array(
				'methods' => 'POST',
				'callback' => __CLASS__ . '::favorites_update',
				'permission_callback' => '__return_true',
			));

			register_rest_route('v1/sweepbright', '/locations', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::locations',
				'permission_callback' => '__return_true',
			));
		});
	}

	public static function hook($data)
	{
		$wp_sweepbright_controller_hook = new WP_SweepBright_Controller_Hook();
		return $wp_sweepbright_controller_hook->init($data);
	}

	public static function publish($data)
	{
		$wp_sweepbright_controller_hook = new WP_SweepBright_Controller_Hook();
		return $wp_sweepbright_controller_hook->publish_estate($data);
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

		// Log
		WP_SweepBright_Helpers::log([
			'post_id' => WP_SweepBright_Helpers::get_post_ID_from_estate($estate_id),
			'title' => 'Contact request [Estate]',
			'message' => 'Contact request has been sent for:' . get_the_title(WP_SweepBright_Helpers::get_post_ID_from_estate($estate_id)),
			'action' => 'contact_request_estate',
			'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
		]);
	}

	public static function contact_request_general($data)
	{
		// Log
		WP_SweepBright_Helpers::log([
			'post_id' => false,
			'title' => 'Contact request [General]',
			'message' => 'General contact request has been sent.',
			'action' => 'contact_request_general',
			'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
		]);
	}

	public static function list($data)
	{
		header('Content-Type: application/json');
		return WP_SweepBright_Query::list($data);
	}

	public static function pages_settings()
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->settings();
	}

	public static function pages_settings_save($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->save_settings($data);
	}

	public static function pages_list()
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->list();
	}

	public static function pages_delete($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->delete($data);
	}

	public static function pages_setup($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->setup($data);
	}

	public static function pages_save($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->save($data);
	}

	public static function pages_column($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->pages_column($data);
	}

	public static function pages_column_save($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->pages_column_save($data);
	}

	public static function pages_column_delete($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->pages_column_delete($data);
	}

	public static function pages_row($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->pages_row($data);
	}

	public static function pages_row_save($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->pages_row_save($data);
	}

	public static function pages_row_delete($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->pages_row_delete($data);
	}

	public static function pages_data($data)
	{
		return WP_SweepBright_Controller_Pages::data($data);
	}

	public static function pages_theme_save($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->theme_save($data);
	}

	public static function pages_theme_data()
	{
		return WP_SweepBright_Controller_Pages::theme_data();
	}

	public static function favorites_list()
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->favorites_list();
	}

	public static function favorites_update($data)
	{
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
		return $wp_sweepbright_controller_pages->favorites_update($data);
	}

	public static function locations($data)
	{
		$wp_sweepbright_controller_locations = new WP_SweepBright_Controller_Locations();
		return $wp_sweepbright_controller_locations->locations($data);
	}
}
