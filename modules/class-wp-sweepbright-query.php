<?php

/**
 * WP_SweepBright_Query.
 *
 * This class contains helpers to display and manipulate data.
 *
 * @package    WP_SweepBright_Query
 */

use AnthonyMartin\GeoLocation\GeoPoint;

class WP_SweepBright_Query
{

	public function __construct()
	{
	}

	public static function estate_exists($estate_id)
	{
		$exists = false;
		$query = new WP_Query([
			'posts_per_page' => 1,
			'post_status' => 'publish',
			'post_type' => 'sweepbright_estates',
			'fields' => 'ids',
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => 'estate_id',
					'value' => $estate_id,
					'compare' => '='
				]
			],
		]);
		$posts = $query->posts;
		if (count($posts) > 0) {
			$exists = true;
		}
		return $exists;
	}

	public static function archive_units($estate_id, $units)
	{
		$properties = [];
		foreach ($units as $unit) {
			$properties[] = $unit['id'];
		}

		$loop = new WP_Query([
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post_type' => 'sweepbright_estates',
			'fields' => 'ids',
			'meta_query'	=> [
				'relation'		=> 'AND',
				[
					'key' => 'estate_project_id',
					'value' => $estate_id,
					'compare' => '=',
				],
				[
					'key' => 'estate_id',
					'value' => $properties,
					'compare' => 'NOT IN',
				]
			],
		]);
		$query = $loop->get_posts();

		if ($query) {
			foreach ($query as $item) {
				wp_delete_post($item, true);
			}
		}
		return true;
	}

	public static function min_max_living_area($iso)
	{
		// Easier formatting, used by the editor which uses single language codes
		if ($iso === 'nl') {
			$iso = 'nl_NL';
		} else if ($iso === 'fr') {
			$iso = 'fr_FR';
		} else if ($iso === 'en') {
			$iso = 'en_GB';
		}

		$units = WP_SweepBright_Query::list_units([
			'project_id' => get_field('estate')['id'],
			'ignore_self' => false,
			'is_paged' => false,
			'page' => false,
		])['results'];
		$result = false;
		$results = [];

		$sizeUnit = get_field('sizes', $units[0]['id'])['liveable_area']['unit'];

		foreach ($units as $unit) {
			if (get_field('sizes', $unit['id'])['liveable_area']['size']) {
				$results[] = floatval(get_field('sizes', $unit['id'])['liveable_area']['size']);
			}
		}
		if (count($results) >= 2) {
			$result = [
				'min' => WP_SweepBright_Query::format_number(min($results), $iso) . WP_SweepBright_Query::format_unit($sizeUnit),
				'max' => WP_SweepBright_Query::format_number(max($results), $iso) . WP_SweepBright_Query::format_unit($sizeUnit),
			];
		} else if (count($results) === 1) {
			$result = [
				'min' => WP_SweepBright_Query::format_number($results[0], $iso) . WP_SweepBright_Query::format_unit($sizeUnit),
				'max' => false,
			];
		}
		return $result;
	}

	public static function min_max_plot_area($iso)
	{
		// Easier formatting, used by the editor which uses single language codes
		if ($iso === 'nl') {
			$iso = 'nl_NL';
		} else if ($iso === 'fr') {
			$iso = 'fr_FR';
		} else if ($iso === 'en') {
			$iso = 'en_GB';
		}

		$units = WP_SweepBright_Query::list_units([
			'project_id' => get_field('estate')['id'],
			'ignore_self' => false,
			'is_paged' => false,
			'page' => false,
		])['results'];
		$result = false;
		$results = [];

		if ($iso === 'nl_BE') {
			$sizeUnit = 'sq_m';
		} else {
			$sizeUnit = get_field('sizes', $units[0]['id'])['plot_area']['unit'];
		}

		foreach ($units as $unit) {
			if (get_field('sizes', $unit['id'])['plot_area']['size']) {
				if ($iso === 'nl_BE') {
					$results[] = floatval(get_field('sizes', $unit['id'])['plot_area']['size'] * 100);
				} else {
					$results[] = floatval(get_field('sizes', $unit['id'])['plot_area']['size']);
				}
			}
		}
		if (count($results) >= 2) {
			$result = [
				'min' => WP_SweepBright_Query::format_number(min($results), $iso) . WP_SweepBright_Query::format_unit($sizeUnit),
				'max' => WP_SweepBright_Query::format_number(max($results), $iso) . WP_SweepBright_Query::format_unit($sizeUnit),
			];
		} else if (count($results) === 1) {
			$result = [
				'min' => WP_SweepBright_Query::format_number($results[0], $iso) . WP_SweepBright_Query::format_unit($sizeUnit),
				'max' => false,
			];
		}
		return $result;
	}

	public static function min_max_price($iso = false, $project_id = false, $is_currency = false)
	{
		// Easier formatting, used by the editor which uses single language codes
		if ($iso === 'nl') {
			$iso = 'nl_NL';
		} else if ($iso === 'fr') {
			$iso = 'fr_FR';
		} else if ($iso === 'en') {
			$iso = 'en_GB';
		}

		if ($project_id) {
			$id = $project_id;
		} else {
			$id = get_field('estate')['id'];
		}

		$units = WP_SweepBright_Query::list_units([
			'project_id' => $id,
			'ignore_self' => false,
			'is_paged' => false,
			'page' => false,
		])['results'];
		$result = false;
		$results = [];

		foreach ($units as $unit) {
			if (get_field('price', $unit['id'])['amount'] && !get_field('price', $unit['id'])['hidden']) {
				$results[] = floatval(get_field('price', $unit['id'])['amount']);
			}
		}
		if (count($results) >= 2) {
			if ($iso && !$is_currency) {
				$min = WP_SweepBright_Query::format_number(min($results), $iso);
				$max = WP_SweepBright_Query::format_number(max($results), $iso);
			} else if ($is_currency) {
				$min = WP_SweepBright_Query::format_price(min($results), $iso);
				$max = WP_SweepBright_Query::format_price(max($results), $iso);
			} else {
				$min = min($results);
				$max = max($results);
			}

			$result = [
				'min' => $min,
				'max' => $max,
			];
		} else if (count($results) === 1) {
			if ($iso && !$is_currency) {
				$min = WP_SweepBright_Query::format_number($results[0], $iso);
			} else if ($is_currency) {
				$min = WP_SweepBright_Query::format_price($results[0], $iso);
			} else {
				$min = $results[0];
			}

			$result = [
				'min' => $min,
				'max' => false,
			];
		}
		return $result;
	}

	public static function format_unit($unit)
	{
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

	public static function format_number($number, $iso)
	{
		// Easier formatting, used by the editor which uses single language codes
		if ($iso === 'nl') {
			$iso = 'nl_NL';
		} else if ($iso === 'fr') {
			$iso = 'fr_FR';
		} else if ($iso === 'en') {
			$iso = 'en_GB';
		}

		$format = new \NumberFormatter($iso, \NumberFormatter::DECIMAL);
		$output = $format->format($number);
		return $output;
	}

	public static function get_the_size($iso, $type)
	{
		// Easier formatting, used by the editor which uses single language codes
		if ($iso === 'nl') {
			$iso = 'nl_NL';
		} else if ($iso === 'fr') {
			$iso = 'fr_FR';
		} else if ($iso === 'en') {
			$iso = 'en_GB';
		}

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

	public static function format_price($val, $iso)
	{
		// Easier formatting, used by the editor which uses single language codes
		if ($iso === 'nl') {
			$iso = 'nl_NL';
		} else if ($iso === 'fr') {
			$iso = 'fr_FR';
		} else if ($iso === 'en') {
			$iso = 'en_GB';
		}

		$formatter = new \NumberFormatter($iso, \NumberFormatter::CURRENCY);
		$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
		$formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
		$formatter->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 0);
		$price = $formatter->formatCurrency($val, get_field('price')['currency']);

		if ($iso === 'en_GB') {
			$price = substr($price, 0, -3);
		}

		return $price;
	}

	public static function get_the_price($iso)
	{
		$price = '';

		// Easier formatting, used by the editor which uses single language codes
		if ($iso === 'nl') {
			$iso = 'nl_NL';
		} else if ($iso === 'fr') {
			$iso = 'fr_FR';
		} else if ($iso === 'en') {
			$iso = 'en_GB';
		}

		if (!get_field('price')['hidden']) {
			if (get_field('price')['custom_price']) {
				$price = get_field('price')['custom_price'];
			} else {
				$formatter = new \NumberFormatter($iso, \NumberFormatter::CURRENCY);
				$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
				$formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
				$formatter->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 0);
				$price = $formatter->formatCurrency(get_field('price')['amount'], get_field('price')['currency']);

				if ($iso === 'en_GB') {
					$price = substr($price, 0, -3);
				}
			}
		} else {
			$price = false;
		}
		return $price;
	}

	public static function filter_min_max_price($args)
	{
		for ($i = 0; $i < count($args['posts']); $i++) {
			if ($args['posts'][$i]['meta']['estate']['is_project']) {
				$args['posts'][$i]['meta']['price']['min_max'] = WP_SweepBright_Query::min_max_price(false, $args['posts'][$i]['meta']['estate']['id']);
			}
		}

		return $args['posts'];
	}

	public static function filter_favorites($args)
	{
		if (isset($args['params']['favorites']) && $args['params']['favorites']) {
			if (isset($_COOKIE['favorites']) && json_decode($_COOKIE['favorites'], true)) {
				$args['posts'] = array_filter($args['posts'], function ($estate) {
					return in_array($estate['id'], json_decode($_COOKIE['favorites'], true));
				}, ARRAY_FILTER_USE_BOTH);
			} else {
				$args['posts'] = [];
			}
		}

		return $args['posts'];
	}


	public static function filter_hide_units($args)
	{
		return $args['posts'] = array_filter($args['posts'], function ($estate) {
			return !$estate['meta']['estate']['project_id'];
		}, ARRAY_FILTER_USE_BOTH);
	}

	public static function filter_hide_prospects($args)
	{
		return $args['posts'] = array_filter($args['posts'], function ($estate) {
			return !$estate['meta']['features']['negotiation'] !== 'prospect';
		}, ARRAY_FILTER_USE_BOTH);
	}

	public static function filter_negotiation($args)
	{
		if (isset($args['params']['filters']['negotiation'])) {
			switch ($args['params']['filters']['negotiation']) {
				case 'sale':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['features']['negotiation'] == 'sale';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'let':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['features']['negotiation'] == 'let';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'projects':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['estate']['is_project'];
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'sale_non_projects':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['features']['negotiation'] == 'sale' && !$estate['meta']['estate']['is_project'];
					}, ARRAY_FILTER_USE_BOTH);
					break;
				default:
					break;
			}
		}
		return $args['posts'];
	}

	public static function filter_status($args)
	{
		if (isset($args['params']['filters']['status'])) {
			switch ($args['params']['filters']['status']) {
				case 'available':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['estate']['status'] == 'available';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'sold':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['estate']['status'] == 'sold';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'rented':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['estate']['status'] == 'rented';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'option':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['estate']['status'] == 'option';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'sold_stc':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['estate']['status'] == 'option';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				case 'under_contract':
					$args['posts'] = array_filter($args['posts'], function ($estate) {
						return $estate['meta']['estate']['status'] == 'under_contract';
					}, ARRAY_FILTER_USE_BOTH);
					break;
				default:
					break;
			}
		}
		return $args['posts'];
	}

	public static function filter_agent($args)
	{
		if (isset($args['params']['filters']['agent'])) {
			$args['posts'] = array_filter($args['posts'], function ($estate) use ($args) {
				return in_array($estate['meta']['negotiator']['email'], $args['params']['filters']['agent']);
			}, ARRAY_FILTER_USE_BOTH);
		}

		return $args['posts'];
	}

	public static function filter_new_home($args)
	{
		if (isset($args['params']['filters']['new_home'])) {
			if ($args['params']['filters']['new_home'] === 'new') {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($args) {
					return $estate['meta']['conditions']['general_condition'] === 'new';
				}, ARRAY_FILTER_USE_BOTH);
			} else if ($args['params']['filters']['new_home'] === 'used') {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($args) {
					return $estate['meta']['conditions']['general_condition'] !== 'new';
				}, ARRAY_FILTER_USE_BOTH);
			}
		}
		return $args['posts'];
	}

	public static function filter_category($args)
	{
		if (
			isset($args['params']['filters']['category']) &&
			count($args['params']['filters']['category']) > 0
		) {
			$args['posts'] = array_filter($args['posts'], function ($estate) use ($args) {
				return in_array($estate['meta']['features']['type'], $args['params']['filters']['category']);
			}, ARRAY_FILTER_USE_BOTH);
		}
		return $args['posts'];
	}

	public static function filter_subcategory($args)
	{
		if (
			isset($args['params']['filters']['subcategory']) &&
			count($args['params']['filters']['subcategory']) > 0
		) {
			$args['posts'] = array_filter($args['posts'], function ($estate) use ($args) {
				return in_array($estate['meta']['features']['sub_type'], $args['params']['filters']['subcategory']);
			}, ARRAY_FILTER_USE_BOTH);
		}
		return $args['posts'];
	}

	public static function filter_price($args)
	{
		if (isset($args['params']['filters']['price'])) {
			$params = [
				'min' => $args['params']['filters']['price']['min'],
				'max' => $args['params']['filters']['price']['max']
			];

			if ($params['min'] === 0) {
				$params['min'] = 1;
			}

			if (isset($params['min']) && is_numeric($params['min']) && isset($params['max']) && is_numeric($params['max'])) {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
					return empty($estate['meta']['price']['amount']) || ((intval($estate['meta']['price']['amount']) >= $params['min']) && (intval($estate['meta']['price']['amount']) <= $params['max']));
				}, ARRAY_FILTER_USE_BOTH);
			}

			if (empty($params['min']) && is_numeric($params['max']) && isset($params['max'])) {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
					return empty($estate['meta']['price']['amount']) || ((intval($estate['meta']['price']['amount']) <= $params['max']));
				}, ARRAY_FILTER_USE_BOTH);
			}

			if (empty($params['max']) && is_numeric($params['min']) && isset($params['min'])) {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
					return empty($estate['meta']['price']['amount']) || ((intval($estate['meta']['price']['amount']) >= $params['min']));
				}, ARRAY_FILTER_USE_BOTH);
			}
		}
		return $args['posts'];
	}

	public static function filter_bedrooms($args)
	{
		if (isset($args['params']['filters']['facilities']['bedrooms'])) {
			$params = [
				'min' => $args['params']['filters']['facilities']['bedrooms']['min'],
				'max' => $args['params']['filters']['facilities']['bedrooms']['max']
			];

			if ($params['min'] === 0) {
				$params['min'] = false;
			}

			if ($params['max'] === 0) {
				$params['max'] = false;
			}

			if (isset($params['min']) && is_numeric($params['min']) && isset($params['max']) && is_numeric($params['max'])) {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
					return empty($estate['meta']['facilities']['bedrooms']) || ((intval($estate['meta']['facilities']['bedrooms']) >= $params['min']) && (intval($estate['meta']['facilities']['bedrooms']) <= $params['max']));
				}, ARRAY_FILTER_USE_BOTH);
			}

			if (empty($params['min']) && is_numeric($params['max']) && isset($params['max'])) {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
					return empty($estate['meta']['facilities']['bedrooms']) || ((intval($estate['meta']['facilities']['bedrooms']) <= $params['max']));
				}, ARRAY_FILTER_USE_BOTH);
			}

			if (empty($params['max']) && is_numeric($params['min']) && isset($params['min'])) {
				$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
					return empty($estate['meta']['facilities']['bedrooms']) || ((intval($estate['meta']['facilities']['bedrooms']) >= $params['min']));
				}, ARRAY_FILTER_USE_BOTH);
			}
		}
		return $args['posts'];
	}

	public static function filter_plot_area($args)
	{
		if (
			isset($args['params']['filters']['plot_area']) &&
			is_numeric($args['params']['filters']['plot_area']['min']) &&
			is_numeric($args['params']['filters']['plot_area']['max'])
		) {
			$params = [
				'min' => $args['params']['filters']['plot_area']['min'],
				'max' => $args['params']['filters']['plot_area']['max']
			];

			if ($params['min'] === 0) {
				$params['min'] = 1;
			}

			$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
				if (isset($estate['meta']['sizes']['plot_area']['size'])) {
					$size = intval($estate['meta']['sizes']['plot_area']['size']);

					if ($estate['meta']['sizes']['plot_area']['unit'] === 'are') {
						$size = $size * 100;
					}
				} else {
					$size = false;
				}

				return empty($estate['meta']['sizes']['plot_area']['size']) || !$size || (($size >= $params['min']) && ($size <= $params['max']));
			}, ARRAY_FILTER_USE_BOTH);
		}
		return $args['posts'];
	}

	public static function filter_liveable_area($args)
	{
		if (
			isset($args['params']['filters']['liveable_area']) &&
			is_numeric($args['params']['filters']['liveable_area']['min']) &&
			is_numeric($args['params']['filters']['liveable_area']['max'])
		) {
			$params = [
				'min' => $args['params']['filters']['liveable_area']['min'],
				'max' => $args['params']['filters']['liveable_area']['max']
			];

			if ($params['min'] === 0) {
				$params['min'] = 1;
			}

			$args['posts'] = array_filter($args['posts'], function ($estate) use ($params) {
				if (isset($estate['meta']['sizes']['liveable_area']['size'])) {
					$size = intval($estate['meta']['sizes']['liveable_area']['size']);

					if ($estate['meta']['sizes']['liveable_area']['unit'] === 'are') {
						$size = $size * 100;
					}
				} else {
					$size = false;
				}

				return empty($estate['meta']['sizes']['liveable_area']['size']) || !$size || (($size >= $params['min']) && ($size <= $params['max']));
			}, ARRAY_FILTER_USE_BOTH);
		}
		return $args['posts'];
	}

	public static function filter_geolocation($args)
	{
		if (
			isset($args['params']['filters']['location']) &&
			$args['params']['filters']['location']['lat'] &&
			$args['params']['filters']['location']['lng']
		) {
			$geopoint = new GeoPoint($args['params']['filters']['location']['lat'], $args['params']['filters']['location']['lng']);
			$distance = WP_SweepBright_Helpers::settings_form()['geo_distance'];
			if (isset($args['params']['filters']['location']['distance']) && $args['params']['filters']['location']['distance']) {
				$distance = $args['params']['filters']['location']['distance'];
			}
			$boundingBox = $geopoint->boundingBox(intval($distance), 'km');

			$args['posts'] = array_filter($args['posts'], function ($estate) use ($boundingBox) {
				return (($estate['meta']['location']['latitude'] > $boundingBox->getMinLatitude()) && ($estate['meta']['location']['latitude'] <= $boundingBox->getMaxLatitude())) &&
					(($estate['meta']['location']['longitude'] > $boundingBox->getMinLongitude()) && ($estate['meta']['location']['longitude'] <= $boundingBox->getMaxLongitude()));
			}, ARRAY_FILTER_USE_BOTH);
		}
		return $args['posts'];
	}

	public static function order_by_date($args)
	{
		if (isset($args['params']['sort']) && $args['params']['sort']['orderBy'] === 'date') {
			usort($args['posts'], function ($a, $b) use ($args) {
				$order = $b['date'] - $a['date'];
				if ($args['params']['sort']['order'] === 'asc') {
					$order = $a['date'] - $b['date'];
				}
				return $order;
			});
		}
		return $args['posts'];
	}

	public static function order_by_price($args)
	{
		if (isset($args['params']['sort']) && $args['params']['sort']['orderBy'] === 'price') {
			usort($args['posts'], function ($a, $b) use ($args) {
				$order = floatval($b['meta']['price']['amount']) - floatval($a['meta']['price']['amount']);
				if ($args['params']['sort']['order'] === 'asc') {
					$order = floatval($a['meta']['price']['amount']) - floatval($b['meta']['price']['amount']);
				}
				return $order;
			});
		}
		return $args['posts'];
	}

	public static function negotiationValue($value)
	{
		switch ($value) {
			case 'sale':
				return 0;
			case 'let':
				return 1;
		}
		return $value;
	}

	public static function statusValue($value)
	{
		switch ($value) {
			case 'available':
				return 0;
			case 'option':
				return 1;
			case 'under_contract':
				return 2;
			case 'sold':
				return 3;
			case 'rented':
				return 4;
		}
		return $value;
	}

	public static function order_by_relevance($args)
	{
		if ((isset($args['params']['sort']) && $args['params']['sort']['orderBy'] === 'relevance') || $args['params']['recent']) {
			usort($args['posts'], function ($a, $b) {
				return ($b['date'] <=> $a['date']) * 2 +
					(WP_SweepBright_Query::negotiationValue($a['meta']['features']['negotiation']) <=> WP_SweepBright_Query::negotiationValue($b['meta']['features']['negotiation'])) * 100 +
					(WP_SweepBright_Query::statusValue($a['meta']['estate']['status']) <=> WP_SweepBright_Query::statusValue($b['meta']['estate']['status'])) * 500 +
					($b['meta']['open_homes']['hasOpenHome'] <=> $a['meta']['open_homes']['hasOpenHome']) * 1000;
			});
		}
		return $args['posts'];
	}

	public static function slugify($string)
	{
		return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

	public static function store_cache($args)
	{
		$tmp_count = 0;
		foreach ($args['post_chunks'] as $post_chunk) {
			$posts_cache = [];
			foreach ($post_chunk as $post_id) {
				// Images
				$images = [];
				if (get_post_meta($post_id, 'features_images', true)) {
					foreach (get_post_meta($post_id, 'features_images', true) as $image) {
						$src_thumb = wp_get_attachment_image_src($image, 'thumbnail');
						if ($src_thumb) {
							$src_thumb = $src_thumb[0];
						} else {
							$src_thumb = '';
						}

						$src_medium = wp_get_attachment_image_src($image, 'medium');
						if ($src_medium) {
							$src_medium = $src_medium[0];
						} else {
							$src_medium = '';
						}

						$images[]['sizes'] = [
							'thumbnail' => $src_thumb,
							'medium' => $src_medium,
							'medium_large' => $src_medium,
							'large' => $src_medium,
						];
					}
				}


				// Properties
				$properties = [];
				if (get_post_meta($post_id, 'estate_properties', true)) {
					foreach (get_post_meta($post_id, 'estate_properties', true) as $property) {
						if ($property) {
							$properties[] = $property;
						}
					}
				}

				// Build post object
				$post_item = [
					'id' => $post_id,
					'permalink' => get_the_permalink($post_id),
					'date' => get_the_time('U', $post_id),
					'meta' => [
						'conditions' => [
							'general_condition' => get_post_meta($post_id, 'conditions_general_condition', true),
						],
						'custom' => get_field('custom', $post_id),
						'estate' => [
							'id' => get_post_meta($post_id, 'estate_id', true),
							'status' => get_post_meta($post_id, 'estate_status', true),
							'title' => [
								'en' => get_post_meta($post_id, 'estate_title_en', true),
								'nl' => get_post_meta($post_id, 'estate_title_nl', true),
								'fr' => get_post_meta($post_id, 'estate_title_fr', true),
							],
							'description' => [
								'en' => get_post_meta($post_id, 'estate_description_en', true),
								'nl' => get_post_meta($post_id, 'estate_description_nl', true),
								'fr' => get_post_meta($post_id, 'estate_description_fr', true),
							],
							'is_project' => get_post_meta($post_id, 'estate_is_project', true),
							'project_id' => get_post_meta($post_id, 'estate_project_id', true),
							'properties' => $properties,
						],
						'facilities' => [
							'kitchens' => get_post_meta($post_id, 'facilities_kitchens', true),
							'bathrooms' => get_post_meta($post_id, 'facilities_bathrooms', true),
							'toilets' => get_post_meta($post_id, 'facilities_toilets', true),
							'floors' => get_post_meta($post_id, 'facilities_floors', true),
							'bedrooms' => get_post_meta($post_id, 'facilities_bedrooms', true),
							'living_rooms' => get_post_meta($post_id, 'facilities_living_rooms', true),
							'storage_rooms' => get_post_meta($post_id, 'facilities_storage_rooms', true),
							'manufacturing_areas' => get_post_meta($post_id, 'facilities_manufacturing_areas', true),
							'showrooms' => get_post_meta($post_id, 'facilities_showrooms', true),
						],
						'features' => [
							'images' => $images,
							'negotiation' => get_post_meta($post_id, 'features_negotiation', true),
							'type' => get_post_meta($post_id, 'features_type', true),
							'sub_type' => get_post_meta($post_id, 'features_sub_type', true),
						],
						'location' => [
							'hidden' => get_post_meta($post_id, 'location_hidden', true),
							'latitude' => get_post_meta($post_id, 'location_latitude', true),
							'longitude' => get_post_meta($post_id, 'location_longitude', true),
							'city' => get_post_meta($post_id, 'location_city', true),
							'street' => get_post_meta($post_id, 'location_street', true),
							'street_2' => get_post_meta($post_id, 'location_street_2', true),
							'number' => get_post_meta($post_id, 'location_number', true),
							'box' => get_post_meta($post_id, 'location_box', true),
							'addition' => get_post_meta($post_id, 'location_addition', true),
							'country' => get_post_meta($post_id, 'location_country', true),
							'postal_code' => get_post_meta($post_id, 'location_postal_code', true),
							'formatted' => get_post_meta($post_id, 'location_formatted', true),
							'formatted_agency' => get_post_meta($post_id, 'location_formatted_agency', true),
						],
						'negotiator' => [
							'email' => get_post_meta($post_id, 'negotiator_email', true),
						],
						'open_homes' => get_field('open_homes', $post_id),
						'price' => [
							'amount' => get_post_meta($post_id, 'price_amount', true),
							'currency' => get_post_meta($post_id, 'price_currency', true),
							'hidden' => get_post_meta($post_id, 'price_hidden', true),
							'price_costs' => get_post_meta($post_id, 'price_price_costs', true),
						],
						'sizes' => [
							'liveable_area' => [
								'size' => get_post_meta($post_id, 'sizes_liveable_area_size', true),
								'unit' => get_post_meta($post_id, 'sizes_liveable_area_unit', true),
							],
							'plot_area' => [
								'size' => get_post_meta($post_id, 'sizes_plot_area_size', true),
								'unit' => get_post_meta($post_id, 'sizes_plot_area_unit', true),
							],
						],
					],
				];
				$posts_cache[] = $post_item;
				$results['estates'][] = $post_item;
			}
			FileSystemCache::store($args['key_arr'][$tmp_count], $posts_cache);
			$tmp_count++;
		}
		return $results['estates'];
	}

	public static function list($params)
	{
		// Query
		$query = \PluginEver\QueryBuilder\Query::init();
		$posts = $query->select('ID')
			->from('posts')
			->where('post_type', 'sweepbright_estates')
			->group_by('ID')
			->andWhere('post_status', 'publish');
		$posts = $posts->get();

		// Set cache directory
		FileSystemCache::$cacheDir = WP_PLUGIN_DIR . '/wp-sweepbright/db';

		// Separate cache per 100 properties
		$results['estates'] = [];
		$id_arr = [];
		$key_arr = [];
		$cache_arr = [];
		$count = 0;

		foreach ($posts as $post) {
			// Create post array
			$id_arr[] = $post->ID;
			// Create cache key
			$key_arr[] = FileSystemCache::generateCacheKey('estates_' . $count, WP_SweepBright_Query::slugify(get_bloginfo('name')));
			$cache_arr[] = FileSystemCache::retrieve($key_arr[$count]);

			// Update count
			$count++;
		}
		$post_chunks = array_chunk($id_arr, 100);

		// Load cache
		if ($cache_arr[count($post_chunks) - 1]) {
			foreach ($cache_arr as $cache_chunk) {
				if (is_array($cache_chunk)) {
					foreach ($cache_chunk as $cache_item) {
						$results['estates'][] = $cache_item;
					}
				}
			}
		} else {
			$results['estates'] = WP_SweepBright_Query::store_cache([
				'post_chunks' => $post_chunks,
				'key_arr' => $key_arr,
			]);
		}

		// Filter: show favorites
		$results['estates'] = WP_SweepBright_Query::filter_favorites([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: hide prospects
		$results['estates'] = WP_SweepBright_Query::filter_hide_prospects([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: hide units
		$results['estates'] = WP_SweepBright_Query::filter_hide_units([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: negotiation
		$results['estates'] = WP_SweepBright_Query::filter_negotiation([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: status
		$results['estates'] = WP_SweepBright_Query::filter_status([
			'posts' => $results['estates'],
			'params' => $params,
		]);


		// Filter: agent
		$results['estates'] = WP_SweepBright_Query::filter_agent([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: new home
		$results['estates'] = WP_SweepBright_Query::filter_new_home([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: category
		$results['estates'] = WP_SweepBright_Query::filter_category([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: subcategory
		$results['estates'] = WP_SweepBright_Query::filter_subcategory([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: price
		$results['estates'] = WP_SweepBright_Query::filter_price([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: bedrooms
		$results['estates'] = WP_SweepBright_Query::filter_bedrooms([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: plot area
		$results['estates'] = WP_SweepBright_Query::filter_plot_area([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: liveable area
		$results['estates'] = WP_SweepBright_Query::filter_liveable_area([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Filter: geolocation
		$results['estates'] = WP_SweepBright_Query::filter_geolocation([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Order relevance
		$results['estates'] = WP_SweepBright_Query::order_by_relevance([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Order date
		$results['estates'] = WP_SweepBright_Query::order_by_date([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Order price
		$results['estates'] = WP_SweepBright_Query::order_by_price([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Min max pricing
		$results['estates'] = WP_SweepBright_Query::filter_min_max_price([
			'posts' => $results['estates'],
			'params' => $params,
		]);

		// Count totals
		if (empty($params['page'])) {
			$params['page'] = 1;
		}

		// Max per page
		if (isset($params['maxPerPage']) && $params['maxPerPage']) {
			$max_per_page = $params['maxPerPage'];
		} else {
			$max_per_page = WP_SweepBright_Helpers::settings_form()['max_per_page'];
		}

		// Totals
		$total_posts = count($results['estates']);
		$total_pages = abs(ceil($total_posts / $max_per_page));
		$offset = ($params['page'] * $max_per_page) - $max_per_page;

		// Set totals
		$results['totalPages'] = $total_pages;
		$results['totalPosts'] = $total_posts;

		// Pagination
		if ($params['recent']) {
			$results['estates'] = array_slice($results['estates'], $offset, $params['recent']);
		} else if ($params['mapMode'] && !$params['recent']) {
			$markers = [];
			foreach ($results['estates'] as $estate) {
				$image = '';
				if ($estate["meta"]["features"]["images"]) {
					$image = $estate["meta"]["features"]["images"][0]["sizes"]["medium"];
				}

				$markers[] = [
					'id' => $estate["id"],
					'status' => $estate["meta"]["estate"]["status"],
					'permalink' => $estate["permalink"],
					'title' => $estate["meta"]["estate"]["title"],
					'location' => $estate["meta"]["location"],
					'image' => $image,
					'price' => $estate["meta"]["price"]["amount"],
				];
			}
			$results['estates'] = $markers;
		} else if (!$params['showAll'] && !$params['recent']) {
			if ($total_posts > $max_per_page) {
				$results['estates'] = array_slice($results['estates'], $offset, $max_per_page);
			}
		}

		// Reset array keys
		$results['estates'] = array_values($results['estates']);

		return $results;
	}

	public static function list_units($params)
	{
		if ($params['is_paged']) {
			$posts_per_page = 10;
			$paged = $params['page'];
		} else {
			$posts_per_page = -1;
			$paged = false;
		}

		if ($params['ignore_self']) {
			$post_not_in = [$params['ignore_self']];
		} else {
			$post_not_in = [];
		}

		$results = [];
		$query = new WP_Query([
			'post__not_in' => $post_not_in,
			'posts_per_page' => $posts_per_page,
			'paged' => $paged,
			'post_status' => 'publish',
			'post_type' => 'sweepbright_estates',
			'fields' => 'ids',
			'order' => 'asc',
			'orderby' => 'meta_value',
			'meta_key' => 'estate_title_' . WP_SweepBright_Helpers::settings_form()['default_language'],
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => 'estate_project_id',
					'value' => $params['project_id'],
					'compare' => '='
				],
				[
					'key' => 'estate_status',
					'value' => 'prospect',
					'compare' => '!='
				]
			],
		]);
		$posts = $query->posts;
		foreach ($posts as $post) {
			$results[] = [
				'id' => $post,
				'permalink' => get_the_permalink($post),
				'date' => get_the_time('U', $post),
				'meta' => get_fields($post),
			];
		}
		return [
			'totalPages' => $query->max_num_pages,
			'totalPosts' => $query->found_posts,
			'results' => $results
		];
	}
}
