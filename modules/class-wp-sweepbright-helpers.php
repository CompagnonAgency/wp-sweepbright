<?php

/**
 * WP_SweepBright_Helpers.
 *
 * This class contains functions and helpers to process incoming and outgoing
 * data.
 *
 * @package    WP_SweepBright_Helpers
 */

class WP_SweepBright_Helpers {

	public function __construct() {
		// Get HTTP or HTTPS protocol
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';

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

		// Cleanup logs
		$this->cleanup_logs();

		// Show warning that you need to add settings
		$this->onboarding();

		// Schedule publishing events
		$this->schedule_publishing();

		// Save contact settings
		$this->save_contact_settings();

		// Save global settings
		$this->save_global_settings();
	}

	public function cleanup_logs() {
		function activate_pruning( $should_we_prune ){
			return true;
		}
		add_filter( 'wp_logging_should_we_prune', 'activate_pruning', 10 );

		$scheduled = wp_next_scheduled( 'wp_logging_prune_routine' );
		if ( $scheduled == false ){
			wp_schedule_event( time(), 'hourly', 'wp_logging_prune_routine' );
		}
	}

	public function onboarding() {
		if(!get_option('wp_sweepbright_settings')) {
			function wp_sweepbright_onboarding_notice(){
				echo '<div class="notice notice-warning">
				<p><strong>Welcome to WP-SweepBright</strong> | Before you get started, please enter your SweepBright API credentials in the settings. You can do this right <a href="/wp-admin/admin.php?page=wp-sweepbright-settings">here</a>.</p>
				</div>';
			}
			add_action('admin_notices', 'wp_sweepbright_onboarding_notice');
		}
	}

	public static function get_slack() {
		$settings = [
			'username' => 'SweepBright Plugin - BOT',
			'channel' => '#server-logs',
			'link_names' => true
		];
		return new Maknz\Slack\Client('https://hooks.slack.com/services/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', $settings);
	}

	public function schedule_publishing() {
		/* @return $data
		'post_url' => get_post_permalink($post_id),
		'post_id' => $post_id,
		'estate' => $estate,
		'estate_id' => $estate['id']
		*/
		function publish_estate($data) {
			error_log('publishing_start');
			$locale = $GLOBALS['wp_sweepbright_config']['default_locale'];

			// LOG START
			WP_SweepBright_Helpers::log([
				'estate_title' => $data['estate']['description_title'][$locale],
				'post_id' => $data['post_id'],
				'action' => $data['action'],
				'status' => 'Started',
				'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
			]);

		  // Update all of the data to the custom fields
		  if(!is_numeric($data['post_id'])) {
		    return false;
		  } else {
				WP_SweepBright_Controller_Hook::update_fields($data['estate'], $data['post_id']);
		  }

			// Set the estate URL in SweepBright
			WP_SweepBright_Controller_Hook::set_estate_url($data['estate_id'], get_permalink($data['post_id']));

			// LOG END
			WP_SweepBright_Helpers::log([
				'estate_title' => $data['estate']['description_title'][$locale],
				'post_id' => $data['post_id'],
				'action' => $data['action'],
				'status' => 'Completed',
				'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
			]);
			error_log('publishing_end');
			WP_SweepBright_Helpers::get_slack()->send('[' . get_bloginfo('name') . '] "' . $data['estate']['description_title'][$locale] . '" completed successfully!');
		}
		add_action('schedule_estate', 'publish_estate');
	}

	public static function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
		return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
	}

	public static function get_post_ID_from_estate($id) {
	  $query = new WP_Query(array(
	    'post_type' => 'sweepbright_estates',
	    'showposts' => 1,
	    'meta_query' => [
	      'relation' => 'AND',
	      [
	        'key' => 'estate_id',
	        'value' => $id,
	        'compare' => 'LIKE'
	      ],
	    ],
	  ));
	  return $query->posts[0]->ID;
	}

	public static function log($args) {
		$log_data = array(
			'post_title' 	=> $args['estate_title'],
			'post_content' 	=>  '',
			'post_parent'	=> $args['post_id'],
			'log_type'		=> 'wp_sweepbright_logs'
		);

		$log_meta = array(
			'wp_sweepbright_action' => $args['action'],
			'wp_sweepbright_status' => $args['status'],
			'wp_sweepbright_date	' => $args['date'],
		);

		WP_Logging::insert_log($log_data, $log_meta);
	}

	public static function insert_attachment_from_url($url, $post_id = null) {
		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

	  // Filename
	  $file_name = preg_replace('/\?.*/', '', $url);
	  $ext = '.' . pathinfo($file_name, PATHINFO_EXTENSION);
	  $file_name = $wp_upload_dir['path'] . '/asset_' . uniqid() . $ext;
	  $fp = fopen($file_name, 'w');

	  // Options
	  set_time_limit(0);
	  $options = [
	    CURLOPT_FILE => $fp,
	    CURLOPT_TIMEOUT =>  28800,
	    CURLOPT_URL => $url,
	  ];

	  // Download file
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
	    'post_title' => preg_replace( '/\.[^.]+$/', '', basename($file_name)),
	    'post_content' => '',
	    'post_status' => 'inherit'
	  ];

	  // Insert the attachment.
	  $attach_id = wp_insert_attachment($attachment, $file_name, $post_id);

	  // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	  require_once(ABSPATH . 'wp-admin/includes/image.php');

	  // Generate the metadata for the attachment, and update the database record.
	  $attach_data = wp_generate_attachment_metadata($attach_id, $file_name);
	  wp_update_attachment_metadata($attach_id, $attach_data);

	  // Return attachment id
	  return $attach_id;
	}

	public static function contact_form() {
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
		$contact_request_general_form .= "  <select name=\"negotiation\">\n";
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
					'message' => "You have received a new contact request via the website.<br>\nVisit your SweepBright account for more information.<br><br>\n\n<strong>Property:</strong> [title]<br>\n<strong>Name:</strong> [first_name] [last_name]<br>\n<strong>Email address:</strong> [email]<br>\n<strong>Phone:</strong> [phone]<br><br>\n\n<strong>Message:</strong><br>\n[message]",
				]
			];
		}
		return $data;
	}

	public function save_contact_settings() {
		if(isset($_POST['submit-contact-settings']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
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

			function save_contact_notice(){
				echo '<div class="notice notice-success is-dismissible">
				<p>Contact settings saved successfully.</p>
				</div>';
			}
			add_action('admin_notices', 'save_contact_notice');
		}
	}

	public static function settings_form() {
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

	public function save_global_settings() {
		if(isset($_POST['submit-global-settings']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
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

			function save_settings_notice(){
				echo '<div class="notice notice-success is-dismissible">
				<p>Settings saved successfully.</p>
				</div>';
			}
			add_action('admin_notices', 'save_settings_notice');
		}
	}

}
