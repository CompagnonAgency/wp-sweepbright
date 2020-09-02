<?php

/**
 * WP_SweepBright_Controller_Hook.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Hook
 */

use GuzzleHttp\Client;
use kamermans\OAuth2\GrantType\ClientCredentials;
use kamermans\OAuth2\OAuth2Middleware;
use GuzzleHttp\HandlerStack;

class WP_SweepBright_Controller_Hook {
	public function __construct() {
	}

	public function init($data) {
		$hook_date = microtime(true);

		// Get POST request arguments
		$event = $data['event'];
		$estate_id = $data['estate_id'];

		// Event listener
		$this->event_listener($event, $estate_id);

		// Output
		return rest_ensure_response([
    	'STATUS_CODE' => http_response_code(200),
  	]);
	}

	public static function auth() {
		$auth_client = new Client([
			'base_uri' => 'https://website.sweepbright.com/api/oauth/',
		]);
		$auth_response = $auth_client->request('POST', 'token', [
			'verify' => false,
			'json' => [
				'grant_type' => 'client_credentials',
				'client_id' => $GLOBALS['wp_sweepbright_config']['client_id'],
				'client_secret' => $GLOBALS['wp_sweepbright_config']['client_secret'],
			],
		]);
		$auth_response = json_decode($auth_response->getBody()->getContents(), true);
		$headers = [
			'Authorization' => 'Bearer ' . $auth_response['access_token'],
			'Content-Type' => 'application/json',
			'Accept' => 'application/vnd.sweepbright.v20191206+json',
		];
		return $headers;
	}

	public static function get_client() {
		if (WP_SweepBright_Helpers::isLocalhost()) {
			$client = [
				'base_uri' => $GLOBALS['wp_sweepbright_config']['base_uri_dev'],
			];
		} else {
			$client = [
				'base_uri' => $GLOBALS['wp_sweepbright_config']['base_uri_prod'],
				'headers' => WP_SweepBright_Controller_Hook::auth(),
			];
		}

		return new Client($client);
	}

	public function event_listener($event, $estate_id) {
		switch ($event) {
			case 'estate-added':
				$estate = $this->get_estate($estate_id);
				$cron = $this->add_estate($estate);
				$cron['action'] = 'add';
				$this->async_publish_estate($cron);
				break;
			case 'estate-updated':
				$estate = $this->get_estate($estate_id);
				$cron = $this->edit_estate($estate);
				$cron['action'] = 'update';
				$this->async_publish_estate($cron);
				break;
			case 'estate-deleted':
				$estate = $this->get_estate($estate_id);
				$this->delete_estate($estate, $estate_id);
				break;
			default:
				break;
		}
	}

	public function get_estate($estate_id) {
		$response = WP_SweepBright_Controller_Hook::get_client()->request('GET', "estates/$estate_id", [
	    'verify' => false,
	  ]);
	  return json_decode($response->getBody()->getContents(), true);
	}

	public function add_estate($estate) {
		$post_id = wp_insert_post($this->save_estate($estate, null));

	  return [
	    'post_url' => get_post_permalink($post_id),
	    'post_id' => $post_id,
	    'estate' => $estate,
	    'estate_id' => $estate['id']
	  ];
	}

	public function edit_estate($estate) {
		$id = WP_SweepBright_Helpers::get_post_ID_from_estate($estate['id']);
	  $post_id = wp_update_post($this->save_estate($estate, $id));

	  return [
	    'post_url' => get_post_permalink($post_id),
	    'post_id' => $post_id,
	    'estate' => $estate,
	    'estate_id' => $estate['id']
	  ];
	}

	public function save_estate($estate, $post_id = null) {
		// Default locale
		$locale = $GLOBALS['wp_sweepbright_config']['default_locale'];

		// Save the estate
		if ($post_id) {
			// Make sure the slug is rebuilt if needed
	    $slug = sanitize_title($estate['description_title'][$locale]);
	    $slug = wp_unique_post_slug($slug, $post_id, 'publish', 'sweepbright_estates', false);

	    // Update
	    $post = [
	      'ID' => $post_id,
	      'post_title' => $estate['description_title'][$locale],
	      'post_name' => $slug,
	      'post_status' => 'draft',
	      'post_author' => 1,
	      'post_type' => 'sweepbright_estates',
	    ];
	  } else {
	    // Insert
	    $post = [
	      'post_title' => $estate['description_title'][$locale],
	      'post_status' => 'draft',
	      'post_author' => 1,
	      'post_type' => 'sweepbright_estates',
	      'post_date' => date('Y-m-d H:i:s', strtotime('-1 min')),
	    ];
	  }
	  return $post;
	}

	public function async_publish_estate($estate) {
		// Schedule single event (cron)
		error_log('publishing_start_chron');

		wp_schedule_single_event(time(), 'schedule_estate', [$estate]);

		spawn_cron();
	}

	public static function set_estate_url($estate_id, $url) {
	  $estate_url = false;
	  if (!WP_SweepBright_Helpers::isLocalhost()) {
	    $estate_url = WP_SweepBright_Controller_Hook::get_client()->request('PUT', "estates/$estate_id/url", [
	      'verify' => false,
	      'json' => ['url' => $url],
	    ]);
	  }
	  return $estate_url;
	}

	// Delete estate
	public function delete_estate($estate, $estate_id) {
	  $id = WP_SweepBright_Helpers::get_post_ID_from_estate($estate_id);
	  wp_delete_post($id, true);

		$locale = $GLOBALS['wp_sweepbright_config']['default_locale'];
		WP_SweepBright_Helpers::log([
			'estate_title' => $estate['description_title'][$locale],
			'post_id' => $id,
			'action' => 'delete',
			'status' => 'Completed',
			'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
		]);

	  return true;
	}

	// Update custom fields
	public static function update_fields($estate, $post_id) {
		// Remove linked attachments
		delete_post_media($post_id);

		// Load fields
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-estate-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-open-homes-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-price-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-location-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-features-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-facilities-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-rooms-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-conditions-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-building-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-sizes-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-energy-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-security-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-heating-cooling-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-comfort-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-ameneties-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-vendors-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-negotiator-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-office-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-occupancy-update.php';
		require_once plugin_dir_path( __DIR__ ). 'modules/update/class-orientation-update.php';

		// Run updates
		FieldEstateUpdate::update($estate, $post_id);
		FieldOpenHomesUpdate::update($estate, $post_id);
		FieldPriceUpdate::update($estate, $post_id);
		FieldLocationUpdate::update($estate, $post_id);
		FieldFeaturesUpdate::update($estate, $post_id);
		FieldFacilitiesUpdate::update($estate, $post_id);
		FieldRoomsUpdate::update($estate, $post_id);
		FieldConditionsUpdate::update($estate, $post_id);
		FieldBuildingUpdate::update($estate, $post_id);
		FieldSizesUpdate::update($estate, $post_id);
		FieldEnergyUpdate::update($estate, $post_id);
		FieldSecurityUpdate::update($estate, $post_id);
		FieldHeatingCoolingUpdate::update($estate, $post_id);
		FieldComfortUpdate::update($estate, $post_id);
		FieldAmenitiesUpdate::update($estate, $post_id);
		FieldVendorsUpdate::update($estate, $post_id);
		FieldNegotiatorUpdate::update($estate, $post_id);
		FieldOfficeUpdate::update($estate, $post_id);
		FieldOccupancyUpdate::update($estate, $post_id);
		FieldOrientationUpdate::update($estate, $post_id);

		// Update WordPress status to "published"
	  wp_update_post([
	    'ID' => $post_id,
	    'post_status' => 'publish',
	  ]);
	}

}
