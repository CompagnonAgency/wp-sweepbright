<?php

/**
 * WP_SweepBright_Helpers.
 *
 * This class contains functions and helpers to process incoming and outgoing
 * data.
 *
 * @package    WP_SweepBright_Helpers
 */

use Selective\ImageType\ImageTypeDetector;
use Selective\ImageType\Provider\RasterProvider;

class WP_SweepBright_Helpers
{
	public function __construct()
	{
		// Get HTTP or HTTPS protocol
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';

		// Get the host
		if (isset($_SERVER['HTTP_HOST'])) {
			$host = $protocol . $_SERVER['HTTP_HOST'];
		} else {
			$host = '/wp-json/v1/sweepbright/';
		}

		// Config
		$GLOBALS['wp_sweepbright_config'] = [
			'base_uri_dev' => $host . '/wp-json/v1/sweepbright/',
			'base_uri_prod' => 'https://website.sweepbright.com/api/',
			'base_uri_api_dev' => 'http://localhost:4000/api/',
			'base_uri_api_prod' => 'https://wp-sweepbright-manager.herokuapp.com/api/',
			'api_version' => WP_SweepBright_Helpers::settings_form()['api_version'],
			'client_id' => WP_SweepBright_Helpers::settings_form()['client_id'],
			'client_secret' => WP_SweepBright_Helpers::settings_form()['client_secret'],
			'default_locale' => WP_SweepBright_Helpers::settings_form()['default_language'],
			'max_per_page' => WP_SweepBright_Helpers::settings_form()['max_per_page'],
			'recent_total' => WP_SweepBright_Helpers::settings_form()['recent_total'],
			'geo_distance' => WP_SweepBright_Helpers::settings_form()['geo_distance'],
			'recaptcha_site_key' => WP_SweepBright_Helpers::settings_form()['recaptcha_site_key'],
			'recaptcha_secret_key' => WP_SweepBright_Helpers::settings_form()['recaptcha_secret_key'],
		];

		// Show warning that you need to add settings
		$this->onboarding();

		// Save contact settings
		$this->save_contact_settings();

		// Save global settings
		$this->save_global_settings();
	}

	public function onboarding()
	{
		if (!get_option('wp_sweepbright_settings')) {
			function wp_sweepbright_onboarding_notice()
			{
				echo '<div class="notice notice-warning">
				<p><strong>Welcome to WP-SweepBright</strong> | Before you get started, please enter your SweepBright API credentials in the settings. You can do this right <a href="/wp-admin/admin.php?page=wp-sweepbright-settings">here</a>.</p>
				</div>';
			}
			add_action('admin_notices', 'wp_sweepbright_onboarding_notice');
		}
	}

	public static function isLocalhost()
	{
		$local = false;
		if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || getenv('ENV') === 'staging') {
			$local = true;
		}
		return $local;
	}

	public static function get_project()
	{
		return WP_SweepBright_Helpers::get_post_ID_from_estate(get_field('estate')['project_id']);
	}

	public static function get_post_ID_from_estate($id)
	{
		$query = new WP_Query(array(
			'post_type' => 'sweepbright_estates',
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => 'estate_id',
					'value' => $id,
					'compare' => '='
				],
			],
		));
		return $query->posts[0]->ID;
	}

	public static function status($data)
	{
		if (get_option('wp_sweepbright_publish_status')) {
			update_option('wp_sweepbright_publish_status', $data);
		} else {
			add_option('wp_sweepbright_publish_status', $data);
		}
	}

	public static function log($args)
	{
		// TODO: LOG to database
		error_log(print_r($args, true));
	}

	public static function insert_attachment_from_url($file, $post_id = null)
	{
		// Unique ID
		$id = uniqid();

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Download file
		$ext = pathinfo($file['url'])["extension"];
		$ext = strtok($ext, '?');
		$file_name = $wp_upload_dir['path'] . '/asset_' . $id . '.' . $ext;
		$fp = fopen($file_name, 'w');

		set_time_limit(0);
		$options = [
			CURLOPT_FILE => $fp,
			CURLOPT_TIMEOUT =>  28800,
			CURLOPT_CONNECTTIMEOUT => 28800,
			CURLOPT_URL => $file['url'],
		];
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype(basename($file_name), null);

		// Prepare an array of post data for the attachment.
		$attachment = [
			'guid' => $wp_upload_dir['url'] . '/' . basename($file_name),
			'post_mime_type' => $filetype['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
			'post_content' => '',
			'post_status' => 'inherit'
		];

		// Insert the attachment.
		$attach_id = false;
		$is_heic = false;
		$is_image = false;

		// Detect images
		if ($ext === 'jpg' || $ext === 'JPG' || $ext === 'jpeg' || $ext === 'JPEG' || $ext === 'png' || $ext === 'PNG' || $ext === 'gif' || $ext === 'GIF') {
			$is_image = true;

			// Detect HEIC
			$file = new SplFileObject($file_name);
			$detector = new ImageTypeDetector();
			$detector->addProvider(new RasterProvider());
			$imageType = $detector->getImageTypeFromFile($file);

			if ($imageType->getMimeType() === 'image/heic' || $imageType->getMimeType() === 'image/heif') {
				$is_heic = true;
			}
		}

		if (!$is_heic) {
			$attach_id = wp_insert_attachment($attachment, $file_name, $post_id);

			if ($is_image) {
				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once(ABSPATH . 'wp-admin/includes/image.php');

				// Resize the large image.
				$image = wp_get_image_editor($file_name);

				if (!is_wp_error($image)) {
					$image->resize(1920, 1920, false);
					$image->save($file_name);
				}
			}
		}

		// Return attachment id
		return $attach_id;
	}

	public static function contact_form()
	{
		$locale = explode('_', get_locale())[0];

		// Estate
		$contact_request_estate_form = "<div>\n";
		$contact_request_estate_form .= "  <label>First name:</label> <input type=\"text\" name=\"first_name\" required><br>\n";
		$contact_request_estate_form .= "  <label>Last name:</label> <input type=\"text\" name=\"last_name\" required><br>\n";
		$contact_request_estate_form .= "  <label>Email address:</label> <input type=\"text\" name=\"email\" required><br>\n";
		$contact_request_estate_form .= "  <label>Phone:</label> <input type=\"text\" name=\"phone\" required><br>\n";
		$contact_request_estate_form .= "  <label>Message:</label><br>\n";
		$contact_request_estate_form .= "  <textarea name=\"message\" required></textarea>\n";
		$contact_request_estate_form .= "  <input type=\"hidden\" name=\"locale\" value=\"$locale\"><br>\n";
		$contact_request_estate_form .= "  <input type=\"hidden\" name=\"recaptcha_response\" id=\"recaptchaResponse\"><br>\n";
		$contact_request_estate_form .= "  <button class=\"btn btn-primary\" type=\"submit\" name=\"submit-contact-estate\">Send</button>\n";
		$contact_request_estate_form .= " </div>";

		// General
		$contact_request_general_form = "<div>\n";
		$contact_request_general_form .= "  <label>First name:</label> <input type=\"text\" name=\"first_name\" required><br>\n";
		$contact_request_general_form .= "  <label>Last name:</label> <input type=\"text\" name=\"last_name\" required><br>\n";
		$contact_request_general_form .= "  <label>Email address:</label> <input type=\"text\" name=\"email\" required><br>\n";
		$contact_request_general_form .= "  <label>Phone:</label> <input type=\"text\" name=\"phone\" required><br>\n";
		$contact_request_general_form .= "  <label>Message:</label> <textarea name=\"message\" required></textarea><br>\n";
		$contact_request_general_form .= "  <label>Negotiation:</label><br>\n";
		$contact_request_general_form .= "  <select name=\"negotiation\" required>\n";
		$contact_request_general_form .= "    <option value=\"let\">Let</option>\n";
		$contact_request_general_form .= "    <option value=\"sale\">Sale</option>\n";
		$contact_request_general_form .= "  </select><br>\n";
		$contact_request_general_form .= "  <label>Types:</label><br>\n";
		$contact_request_general_form .= "  <input type=\"checkbox\" name=\"types[]\" value=\"apartment\"> Apartment<br>\n";
		$contact_request_general_form .= "  <input type=\"checkbox\" name=\"types[]\" value=\"commercial\"> Commercial<br>\n";
		$contact_request_general_form .= "  <input type=\"checkbox\" name=\"types[]\" value=\"house\"> House<br>\n";
		$contact_request_general_form .= "  <input type=\"checkbox\" name=\"types[]\" value=\"land\"> Land<br>\n";
		$contact_request_general_form .= "  <input type=\"checkbox\" name=\"types[]\" value=\"office\"> Office<br>\n";
		$contact_request_general_form .= "  <input type=\"checkbox\" name=\"types[]\" value=\"parking\"> Parking<br>\n";
		$contact_request_general_form .= "  <label>Max price:</label> <input type=\"text\" name=\"max_price\"><br>\n";
		$contact_request_general_form .= "  <label>Min price:</label> <input type=\"text\" name=\"min_price\"><br>\n";
		$contact_request_general_form .= "  <label>Min rooms:</label> <input type=\"text\" name=\"min_rooms\"><br>\n";
		$contact_request_general_form .= "  <label>Country (language code):</label> <input type=\"text\" name=\"country\" required><br>\n";
		$contact_request_general_form .= "  <label>Postal codes (split by comma):</label>\n";
		$contact_request_general_form .= "  <input type=\"text\" name=\"postal_codes\" required><br>\n";
		$contact_request_general_form .= "  <input type=\"hidden\" name=\"locale\" value=\"$locale\"><br>\n";
		$contact_request_general_form .= "  <input type=\"hidden\" name=\"recaptcha_response\" id=\"recaptchaResponse\"><br>\n";
		$contact_request_general_form .= "  <button class=\"btn btn-primary\" type=\"submit\" name=\"submit-contact-general\">Send</button>\n";
		$contact_request_general_form .= " </div>";

		if (get_option('wp_sweepbright_contact')) {
			$data = get_option('wp_sweepbright_contact');
		} else {
			$data = [
				'contact_request_estate_form' => $contact_request_estate_form,
				'contact_request_general_form' => $contact_request_general_form,
				'autoresponder' => [
					'to' => get_option('admin_email'),
					'cc' => '',
					'subject' => "New contact request | [first_name] [last_name]",
					'message' => "You have received a new contact request via the website.<br>\nVisit your SweepBright account for more information.<br><br>\n\n<strong>Property:</strong> [title]<br>\n<strong>Link:</strong> [url]<br>\n<strong>Name:</strong> [first_name] [last_name]<br>\n<strong>Email address:</strong> [email]<br>\n<strong>Phone:</strong> [phone]<br><br>\n\n<strong>Message:</strong><br>\n[message]",
				]
			];
		}
		return $data;
	}

	public function save_contact_settings()
	{
		if (isset($_POST['submit-contact-settings']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = [
				'contact_request_estate_form' => stripslashes($_POST['contact_request_estate_form']),
				'contact_request_general_form' => stripslashes($_POST['contact_request_general_form']),
				'autoresponder' => [
					'to' => $_POST['mail_to'],
					'cc' => $_POST['mail_cc'],
					'subject' => $_POST['mail_subject'],
					'message' => $_POST['mail_message'],
				],
			];

			if (get_option('wp_sweepbright_contact')) {
				update_option('wp_sweepbright_contact', $data);
			} else {
				add_option('wp_sweepbright_contact', $data);
			}

			function save_contact_notice()
			{
				echo '<div class="notice notice-success is-dismissible">
				<p>Contact settings saved successfully.</p>
				</div>';
			}
			add_action('admin_notices', 'save_contact_notice');
		}
	}

	public static function settings_form()
	{
		if (get_option('wp_sweepbright_settings')) {
			$data = get_option('wp_sweepbright_settings');
		} else {
			$data = [
				'client_id' => '',
				'client_secret' => '',
				'custom_url' => 'estates',
				'default_language' => 'en',
				'api_version' => 'v20191206',
				'max_per_page' => 12,
				'recent_total' => 3,
				'geo_distance' => 5,
				'recaptcha_site_key' => '',
				'recaptcha_secret_key' => '',
			];
		}
		return $data;
	}

	public function save_global_settings()
	{
		if (isset($_POST['submit-global-settings']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = [
				'client_id' => $_POST['client_id'],
				'client_secret' => $_POST['client_secret'],
				'custom_url' => $_POST['custom_url'],
				'default_language' => $_POST['default_language'],
				'api_version' => $_POST['api_version'],
				'max_per_page' => $_POST['max_per_page'],
				'recent_total' => $_POST['recent_total'],
				'geo_distance' => $_POST['geo_distance'],
				'recaptcha_site_key' => $_POST['recaptcha_site_key'],
				'recaptcha_secret_key' => $_POST['recaptcha_secret_key'],
			];

			if (get_option('wp_sweepbright_settings')) {
				update_option('wp_sweepbright_settings', $data);
			} else {
				add_option('wp_sweepbright_settings', $data);
			}

			flush_rewrite_rules();

			function save_settings_notice()
			{
				echo '<div class="notice notice-success is-dismissible">
				<p>Settings saved successfully.</p>
				</div>';
			}
			add_action('admin_notices', 'save_settings_notice');
		}
	}
}
