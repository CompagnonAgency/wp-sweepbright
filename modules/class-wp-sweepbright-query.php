<?php

/**
 * WP_SweepBright_Query.
 *
 * This class contains helpers to display and manipulate data.
 *
 * @package    WP_SweepBright_Query
 */

use Brick\Money\Money;

class WP_SweepBright_Query {

	public function __construct() {
	}

	public static function format_unit($unit) {
		switch ($unit) {
			case 'sq_ft':
				$unit = 'ft²';
				break;
			case 'sq_m':
				$unit = 'm²';
				break;
			case 'are':
				$unit = 'are';
				break;
			case 'acre':
				$unit = 'acre';
				break;
		}
		return $unit;
	}

	public static function format_number($number, $iso) {
		$format = new \NumberFormatter($iso, \NumberFormatter::DECIMAL);
		$output = $format->format($number);
		return $output;
	}

	public static function get_the_size($iso, $type) {
		$size = '';

		if ($type === 'plot_area') {
			if (get_field('sizes')['plot_area']['size']) {
				$size =  WP_SweepBright_Query::format_number(get_field('sizes')['plot_area']['size'], $iso) . WP_SweepBright_Query::format_unit(get_field('sizes')['plot_area']['unit']);
			}
		}

		if ($type === 'liveable_area') {
			if (get_field('sizes')['liveable_area']['size']) {
				$size =  WP_SweepBright_Query::format_number(get_field('sizes')['liveable_area']['size'], $iso) . WP_SweepBright_Query::format_unit(get_field('sizes')['liveable_area']['unit']);
			}
		}
		return $size;
	}

	public static function get_the_price($iso) {
		$price = '';

		if (!get_field('price')['hidden']) {
			if (get_field('price')['custom_price']) {
			 	$price = get_field('price')['custom_price'];
		 	} else {
				$formatter = new \NumberFormatter($iso, \NumberFormatter::CURRENCY);
				$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
				$formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
				$formatter->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 0);
				$price = $formatter->formatCurrency(get_field('price')['amount'], get_field('price')['currency']);
		 	}
		} else {
			$price = false;
		}
		return $price;
	}

	public static function list($params) {
		if (empty($params['recent']) && file_exists(plugin_dir_path( __DIR__ ). 'cache/cache.json')) {
			$loop = json_decode(file_get_contents(plugin_dir_path( __DIR__ ). 'cache/cache.json'));
			if (empty($loop)) {
				$loop = json_encode([]);
			}
		} else {
			// Default
			$args = [
				'post_status' => 'publish',
				'nopaging' => true,
				'no_found_rows' => true,
				'update_post_meta_cache' => false, 
				'update_post_term_cache' => false,
				'post_type' => 'sweepbright_estates',
				'fields' => 'ids'
			];

			// Recent posts
			if (isset($params['recent'])) {
				$args = array_merge($args, [
					'nopaging' => false,
					'posts_per_page' => WP_SweepBright_Helpers::settings_form()['recent_total'],
					'order' => 'DESC',
					'orderby' => 'date',
				]);
			}

			// Query caching
			$loop = WP_SweepBright_Helpers::cached_query($args);

			// JSON return
			if ($params['json']) {
				$params['persistent'] = true;
				$loop = WP_SweepBright_Helpers::render_json($loop, $params);
			}
		}
		return $loop;
	}

}
