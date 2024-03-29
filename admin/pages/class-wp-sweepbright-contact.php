<?php

/**
 * WP_SweepBright_Contact.
 *
 * This class sends emails on behalf of SweepBright, and stores form settings,
 * such as `from`, `to`, and so on.
 *
 * @package    WP_SweepBright_Contact
 */

session_start();

class WP_SweepBright_Contact
{

	public function __construct()
	{
		$this->register_shortcode();
		$this->verify_recaptcha();

		add_action('wp_footer', 'add_recaptcha_to_footer');
		function add_recaptcha_to_footer()
		{
			WP_SweepBright_Contact::add_recaptcha();
		}
	}

	public static function add_recaptcha()
	{
		$script = '';
		$script .= '<script id="wp-sweepbright-recaptcha-load" src="https://www.google.com/recaptcha/api.js?render=' . WP_SweepBright_Helpers::settings_form()['recaptcha_site_key'] . '"></script>';
		$script .= '<script id="wp-sweepbright-recaptcha-exec">';
		$script .= 'grecaptcha.ready(function () {';
		$script .= '	grecaptcha.execute(\'' . WP_SweepBright_Helpers::settings_form()['recaptcha_site_key'] . '\', { action: \'contact\' }).then(function (token) {';
		$script .= '		window.WPContactRecaptcha = token;';
		$script .= '		if (document.getElementById(\'recaptchaResponse\')) {';
		$script .= '			var recaptchaResponse = document.getElementById(\'recaptchaResponse\');';
		$script .= '			recaptchaResponse.value = token;';
		$script .= '		}';
		$script .= '	});';
		$script .= '});';
		$script .= '</script>';
		echo $script;
	}

	public function verify_recaptcha()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
			$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
			$recaptcha_secret = WP_SweepBright_Helpers::settings_form()['recaptcha_secret_key'];
			$recaptcha_response = $_POST['recaptcha_response'];

			$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
			$recaptcha = json_decode($recaptcha);

			if ($recaptcha->success) {
				$this->submit_estate_form();
				$this->submit_general_form();
				$this->submit_valuation_form();
				$this->submit_contact_form();
			} else {
				$this->emit_event('wp_contact_estate_error', $recaptcha);
				$_POST['wp_contact_estate_sent'] = false;
				$_POST['wp_contact_estate_error'] = $recaptcha;
			}
		}
	}

	public function register_shortcode()
	{
		function contact_estate_shortcode()
		{
			$form = "";
			$form .= "<form name=\"contact-estate\" method=\"post\" action=\"\">";
			$form .= stripslashes(WP_SweepBright_Helpers::get_contact_form_translation(WP_SweepBright_Helpers::contact_form()['contact_request_estate_form']));
			$form .= "</form>";
			return $form;
		}
		add_shortcode('contact-request-estate', 'contact_estate_shortcode');

		function contact_general_shortcode()
		{
			$form = "";
			$form .= "<form name=\"contact-general\" method=\"post\" action=\"\">";
			$form .= stripslashes(WP_SweepBright_Helpers::get_contact_form_translation(WP_SweepBright_Helpers::contact_form()['contact_request_general_form']));
			$form .= "</form>";
			return $form;
		}
		add_shortcode('contact-request-general', 'contact_general_shortcode');
	}

	public function validate_input($data)
	{
		$data = trim($data);
		return $data;
	}

	public function emit_event($event, $message = false)
	{
		add_action('wp_head', function () use ($event, $message) {
			echo "
			<script type=\"text/javascript\">
  		setTimeout(() => {
				// Create the event.
				var sb_event = document.createEvent('CustomEvent');

				// Define that the event name is 'build'.
				sb_event.initCustomEvent('$event', true, true, { message: " . json_encode($message) . " });

				// target can be any Element or other EventTarget.
				document.dispatchEvent(sb_event);
		  }, 500);
			</script>
			";
		});
	}

	public function parse_template($template, $form)
	{
		$output = $template;
		if (isset($form['title'])) {
			$output = str_replace('[title]', $form['title'], $template);
		}
		if (isset($form['address'])) {
			$output = str_replace('[address]', $form['address'], $output);
		}
		if (isset($form['negotiation'])) {
			$output = str_replace('[negotiation]', $form['negotiation'], $output);
		}
		if (isset($form['url'])) {
			$output = str_replace('[url]', $form['url'], $output);
		}
		if (isset($form['first_name'])) {
			$output = str_replace('[first_name]', $form['first_name'], $output);
		}
		if (isset($form['last_name'])) {
			$output = str_replace('[last_name]', $form['last_name'], $output);
		}
		if (isset($form['reference'])) {
			$output = str_replace('[reference]', $form['reference'], $output);
		}
		if (isset($form['postal_code'])) {
			$output = str_replace('[postal_code]', $form['postal_code'], $output);
		}
		if (isset($form['subject'])) {
			$output = str_replace('[subject]', $form['subject'], $output);
		}
		if (isset($form['email'])) {
			$output = str_replace('[email]', $form['email'], $output);
		}
		if (isset($form['phone'])) {
			$output = str_replace('[phone]', $form['phone'], $output);
		}
		if (isset($form['message'])) {
			$output = str_replace('[message]', $form['message'], $output);
		}
		if (isset($form['street'])) {
			$output = str_replace('[street]', $form['street'], $output);
		}
		if (isset($form['city'])) {
			$output = str_replace('[city]', $form['city'], $output);
		}
		return $output;
	}

	public function autorespond($form)
	{
		$to = WP_SweepBright_Helpers::contact_form()['autoresponder']['to'];
		$subject = $this->parse_template(WP_SweepBright_Helpers::contact_form()['autoresponder']['subject'], $form);
		$body = $this->parse_template(WP_SweepBright_Helpers::contact_form()['autoresponder']['message'], $form);
		$headers = [
			'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
			'Reply-To: ' . $form['first_name'] . ' ' . $form['last_name'] . ' <' . $form['email'] . '>',
		];
		if (WP_SweepBright_Helpers::contact_form()['autoresponder']['cc']) {
			$cc = explode(',', WP_SweepBright_Helpers::contact_form()['autoresponder']['cc']);
			foreach ($cc as &$cc_mail) {
				$cc_mail = trim($cc_mail);
				$headers[] = "Cc: $cc_mail";
			}
		}
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		wp_mail($to, $subject, $body, $headers);
	}

	public function mail_form($form)
	{
		// Send to admin
		$to = $_SESSION["send_to"];
		if (!$_SESSION["send_to"] || empty($_SESSION["send_to"])) {
			$to = get_option('admin_email');
		}
		$subject = $this->parse_template($_SESSION["contact_subject"], $form);
		$body = $this->parse_template($_SESSION["contact_body"], $form);
		$headers = [
			'From: ' . get_bloginfo('name') . ' <' . $form['email'] . '>',
			'Reply-To: ' . $form['first_name'] . ' ' . $form['last_name'] . ' <' . $form['email'] . '>',
		];
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		wp_mail($to, $subject, $body, $headers);

		// Auto respond
		$subject = $this->parse_template($_SESSION["autoreply_subject"], $form);
		$body = $this->parse_template($_SESSION["autoreply_body"], $form);
		$headers = [
			'From: ' . get_bloginfo('name') . ' <' . $to . '>',
			'Reply-To: ' . get_bloginfo('name') . ' <' . $to . '>',
		];
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		wp_mail($form['email'], $subject, $body, $headers);
	}

	public function submit_estate_form()
	{
		if (isset($_POST['submit-contact-estate']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = get_field('estate', get_the_ID())['id'];
			$negotiation = get_field('features')['negotiation'];

			switch ($negotiation) {
				case 'let':
					$negotiation = 'For rent';

					if (in_array('polylang-pro/polylang.php', apply_filters('active_plugins', get_option('active_plugins')))) {
						if (pll_current_language() === 'en') {
							$negotiation = 'For rent';
						} else if (pll_current_language() === 'nl') {
							$negotiation = 'Te huur';
						} else if (pll_current_language() === 'fr') {
							$negotiation = 'A louer';
						}
					}
					break;
				case 'sale':
					$negotiation = 'For sale';

					if (in_array('polylang-pro/polylang.php', apply_filters('active_plugins', get_option('active_plugins')))) {
						if (pll_current_language() === 'en') {
							$negotiation = 'For sale';
						} else if (pll_current_language() === 'nl') {
							$negotiation = 'Te koop';
						} else if (pll_current_language() === 'fr') {
							$negotiation = 'À vendre';
						}
					}
					break;
			}

			$form = [
				'title' => get_the_title(),
				'url' => '<a href="' . get_the_permalink() . '">View property</a>',
				'address' => get_field('location')['formatted_agency'],
				'negotiation' => $negotiation,
				'street' => stripslashes($this->validate_input($_POST['street'])),
				'city' => stripslashes($this->validate_input($_POST['city'])),
				'first_name' => stripslashes($this->validate_input($_POST['first_name'])),
				'last_name' => stripslashes($this->validate_input($_POST['last_name'])),
				'email' => $this->validate_input($_POST['email']),
				'phone' => $this->validate_input($_POST['phone']),
				'message' => stripslashes($this->validate_input($_POST['message'])),
				'locale' => $this->validate_input($_POST['locale']),
			];

			WP_SweepBright_Controller_Hook::get_client()->request('POST', "estates/$id/contacts", [
				'verify' => false,
				'json' => [
					'first_name' => $form['first_name'],
					'last_name' => $form['last_name'],
					'email' => $form['email'],
					'phone' => $form['phone'],
					'message' => $form['message'],
					'locale' => $form['locale'],
				],
			]);

			$this->autorespond($form);
			$this->emit_event('wp_contact_estate_sent', 'success');
			$_POST['wp_contact_estate_sent'] = true;
			$_POST['wp_contact_estate_error'] = false;
		}
	}

	public function submit_valuation_form()
	{
		if (isset($_POST['submit-contact-valuation']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$form = [
				'first_name' => $this->validate_input($_POST['first_name']),
				'last_name' => $this->validate_input($_POST['last_name']),
				'email' => $this->validate_input($_POST['email']),
				'phone' => $this->validate_input($_POST['phone']),
				'message' => $this->validate_input($_POST['message']),
				'street' => $this->validate_input($_POST['street']),
				'city' => $this->validate_input($_POST['city']),
			];

			$this->mail_form($form);
			$this->emit_event('wp_contact_estate_sent', 'success');
			$_POST['wp_contact_estate_sent'] = true;
			$_POST['wp_contact_estate_error'] = false;
		}
	}

	public function submit_contact_form()
	{
		if (isset($_POST['submit-contact-form']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$form = [];

			foreach ($_POST as $key => $value) {
				if (strpos($key, 'form_') === 0) {
					$form[str_replace('form_', '', $key)] = $value;
				}
			}

			$this->mail_form($form);
			$this->emit_event('wp_contact_estate_sent', 'success');
			$_POST['wp_contact_estate_sent'] = true;
			$_POST['wp_contact_estate_error'] = false;
		}
	}

	public function submit_general_form()
	{
		if (isset($_POST['submit-contact-general']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$form = [
				'title' => '-',
				'url' => '-',
				'address' => '-',
				'city' => '-',
				'street' => '-',
				'first_name' => $this->validate_input($_POST['first_name']),
				'last_name' => $this->validate_input($_POST['last_name']),
				'email' => $this->validate_input($_POST['email']),
				'phone' => $this->validate_input($_POST['phone']),
				'message' => $this->validate_input($_POST['message']),
				'locale' => $this->validate_input($_POST['locale']),
			];

			if (isset($_POST['negotiation']) && $_POST['negotiation'] !== '') {
				$form['negotiation'] = $this->validate_input($_POST['negotiation']);
			} else {
				$form['negotiation'] = false;
			}

			if (isset($_POST['types']) && $_POST['types'] !== '') {
				$form['types'] = $_POST['types'];
			} else {
				$form['types'] = [];
			}

			if (isset($_POST['max_price']) && $_POST['max_price'] !== '') {
				$form['max_price'] = $this->validate_input($_POST['max_price']);
			} else {
				$form['max_price'] = 0;
			}

			if (isset($_POST['min_price']) && $_POST['min_price'] !== '') {
				$form['min_price'] = $this->validate_input($_POST['min_price']);
			} else {
				$form['min_price'] = 0;
			}

			if (isset($_POST['min_rooms']) && $_POST['min_rooms'] !== '') {
				$form['min_rooms'] = $this->validate_input($_POST['min_rooms']);
			} else {
				$form['min_rooms'] = 0;
			}

			if (isset($_POST['country']) && $_POST['country'] !== '') {
				$form['country'] = $this->validate_input($_POST['country']);
			} else {
				$form['country'] = '';
			}

			if (isset($_POST['postal_codes']) && $_POST['postal_codes'] !== '') {
				$form['postal_codes'] = explode(',', $_POST['postal_codes']);

				function trim_value(&$value)
				{
					$value = trim($value);
				}
				array_walk($form['postal_codes'], 'trim_value');


				if (!is_array($form['postal_codes'])) {
					$form['postal_codes'] = [$form['postal_codes']];
				}
			} else {
				$form['postal_codes'] = [];
			}

			$data = [
				'verify' => false,
				'json' => [
					'first_name' => $form['first_name'],
					'last_name' => $form['last_name'],
					'email' => $form['email'],
					'phone' => $form['phone'],
					'message' => $form['message'],
					'locale' => $form['locale'],

					'preferences' => [
						'negotiation' => $form['negotiation'],
						'types' => $form['types'],
						'max_price' => $form['max_price'],
						'min_price' => $form['min_price'],
						'min_rooms' => $form['min_rooms'],
					],

					'location_preference' => [
						'country' => $form['country'],
						'postal_codes' => $form['postal_codes'],
					]
				],
			];

			WP_SweepBright_Controller_Hook::get_client()->request('POST', "contacts", $data);


			$this->autorespond($form);
			$this->emit_event('wp_contact_estate_sent', 'success');
			$_POST['wp_contact_estate_sent'] = true;
			$_POST['wp_contact_estate_error'] = false;
		}
	}
}
