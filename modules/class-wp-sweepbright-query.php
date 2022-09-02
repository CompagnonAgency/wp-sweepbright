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
				// Delete posts
				wp_delete_post($item, true);

				// Delete cache
				require_once plugin_dir_path(__DIR__) . 'modules/class-wp-sweepbright-cache.php';
				WP_SweepBright_Cache::remove($post_id);
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

			if (get_field('price', $unit['id'])['currency'] === 'EUR') {
				$iso = 'nl_BE';
			} else if (get_field('price', $unit['id'])['currency'] === 'GBP' || get_field('price', $unit['id'])['currency'] === 'USD') {
				$iso = 'en_GB';
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
		if (get_field('price')['currency'] === 'EUR') {
			$iso = 'nl_BE';
		} else if (get_field('price')['currency'] === 'GBP' || get_field('price')['currency'] === 'USD') {
			$iso = 'en_GB';
		}

		if ($iso === 'nl_BE') {
			$currency = 'EUR';
		} else {
			$currency = 'GBP';
		}

		$formatter = new \NumberFormatter($iso, \NumberFormatter::CURRENCY);
		$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
		$formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
		$formatter->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 0);
		$price = $formatter->formatCurrency($val, $currency);

		if ($iso === 'en_GB') {
			$price = substr($price, 0, -3);
		}

		return $price;
	}

	public static function get_the_price($iso)
	{
		$price = '';

		// Easier formatting, used by the editor which uses single language codes
		if (get_field('price')['currency'] === 'EUR') {
			$iso = 'nl_BE';
		} else if (get_field('price')['currency'] === 'GBP' || get_field('price')['currency'] === 'USD') {
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
					// $price = substr($price, 0, -3);
				}
			}
		} else {
			$price = false;
		}
		return $price;
	}

	public static function process_filter_min_max_price($posts)
	{
		// Set cache directory
		FileSystemCache::$cacheDir = WP_PLUGIN_DIR . '/wp-sweepbright/db';

		foreach ($posts as &$post) {
			// Initialize caching
			$price_cache = FileSystemCache::generateCacheKey('post_' . $post['id'] . '_price', WP_SweepBright_Query::slugify(get_bloginfo('name')));

			if ($post['meta']['estate']['is_project']) {
				if (!FileSystemCache::retrieve($price_cache)) {
					$min_max_price = WP_SweepBright_Query::min_max_price(false, $post['meta']['estate']['id']);
					FileSystemCache::store($price_cache, $min_max_price);
				} else {
					$min_max_price = FileSystemCache::retrieve($price_cache);
				}
			} else {
				$min_max_price = [
					'min' => false,
					'max' => false,
				];
			}

			$post['meta']['price']['min_max'] = $min_max_price;
		}
		return $posts;
	}

	public static function sql_filter_hide_prospects($args)
	{
		$args['posts'] = $args['posts']->andWhere('status', '!=', 'prospect');
		return $args['posts'];
	}

	public static function sql_filter_hide_available($args)
	{
		if (WP_SweepBright_Helpers::setting('available_properties') === 'under_contract') {
			$args['posts'] = $args['posts']->andWhere('status', '!=', 'available');
		}
		return $args['posts'];
	}

	public static function sql_filter_hide_lost($args)
	{
		$args['posts'] = $args['posts']->andWhere('status', '!=', 'lost');
		return $args['posts'];
	}

	public static function sql_filter_hide_units($args)
	{
		$args['posts'] = $args['posts']->andWhere('is_unit', 0);
		return $args['posts'];
	}

	public static function sql_filter_status($args)
	{
		if (isset($args['params']['filters']['status'])) {
			if ($args['params']['filters']['status'] !== 'all') {
				$status = $args['params']['filters']['status'];

				if (!is_array($args['params']['filters']['status'])) {
					$status = explode(',', $args['params']['filters']['status']);
				}

				foreach ($status as $key => $status_item) {
					if (!$status_item) {
						unset($status[$key]);
					}

					if ($status_item === 'sold_stc') {
						$status[$key] = 'option';
					}
				}

				$args['posts'] = $args['posts']->whereIn('status', $status);
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_new_home($args)
	{
		if (isset($args['params']['filters']['new_home'])) {
			if ($args['params']['filters']['new_home'] === 'new') {
				$args['posts'] = $args['posts']->andWhere('general_condition', 'new');
			} else if ($args['params']['filters']['new_home'] === 'used') {
				$args['posts'] = $args['posts']->andWhere('general_condition', '!=', 'new');
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_negotiation($args)
	{
		if (isset($args['params']['filters']['negotiation'])) {
			switch ($args['params']['filters']['negotiation']) {
				case 'sale':
					$args['posts'] = $args['posts']->andWhere('negotiation', 'sale');
					break;
				case 'let':
					$args['posts'] = $args['posts']->andWhere('negotiation', 'let');
					break;
				case 'projects':
					$args['posts'] = $args['posts']->andWhere('is_project', 1);
					break;
				case 'sale_non_projects':
					$args['posts'] = $args['posts']->andWhere('negotiation', 'sale')
						->andWhere('is_project', 0);
					break;
				default:
					break;
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_category($args)
	{
		if (
			isset($args['params']['filters']['category']) &&
			count($args['params']['filters']['category']) > 0
		) {
			$category = '';
			if ($args['params']['filters']['category'] !== 'all') {
				$category = $args['params']['filters']['category'];
				$args['posts'] = $args['posts']->whereIn('category', $category);
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_subcategory($args)
	{
		if (
			isset($args['params']['filters']['subcategory']) &&
			count($args['params']['filters']['subcategory']) > 0
		) {
			$subcategory = '';
			if ($args['params']['filters']['subcategory'] !== 'all') {
				$subcategory = $args['params']['filters']['subcategory'];
				$args['posts'] = $args['posts']->whereIn('subcategory', $subcategory);
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_agent($args)
	{
		if (isset($args['params']['filters']['agent']) && $args['params']['filters']['agent']) {
			$args['posts'] = $args['posts']->andWhere('negotiator_email', $args['params']['filters']['agent']);
		}
		return $args['posts'];
	}

	public static function sql_filter_office($args)
	{
		if (isset($args['params']['filters']['office']) && $args['params']['filters']['office']) {
			$args['posts'] = $args['posts']->andWhere('office_name', $args['params']['filters']['office']);
		}
		return $args['posts'];
	}

	public static function sql_filter_price($args)
	{
		if (isset($args['params']['filters']['price'])) {
			$params = [
				'min' => $args['params']['filters']['price']['min'],
				'max' => $args['params']['filters']['price']['max']
			];

			if ($params['min'] === 0) {
				$params['min'] = false;
			}

			if ($params['max'] === 0) {
				$params['max'] = false;
			}

			if (isset($params['min']) && is_numeric($params['min']) && isset($params['max']) && is_numeric($params['max'])) {
				$args['posts'] = $args['posts']->andWhere('price', '>=', $params['min'])
					->andWhere('price', '<=', $params['max']);
			}

			if (empty($params['min']) && is_numeric($params['max']) && isset($params['max'])) {
				$args['posts'] = $args['posts']->andWhere('price', '<=', $params['max']);
			}

			if (empty($params['max']) && is_numeric($params['min']) && isset($params['min'])) {
				$args['posts'] = $args['posts']->andWhere('price', '>=', $params['min']);
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_plot_area($args)
	{
		if (isset($args['params']['filters']['plot_area'])) {
			$params = [
				'min' => $args['params']['filters']['plot_area']['min'],
				'max' => $args['params']['filters']['plot_area']['max']
			];

			if ($params['min'] === 0) {
				$params['min'] = false;
			}

			if ($params['max'] === 0) {
				$params['max'] = false;
			}

			if (isset($params['min']) && is_numeric($params['min']) && isset($params['max']) && is_numeric($params['max'])) {
				$args['posts'] = $args['posts']->andWhere('plot_area', '!=', 0)
					->andWhere('plot_area', '>=', $params['min'])
					->andWhere('plot_area', '<=', $params['max']);
			}

			if (empty($params['min']) && is_numeric($params['max']) && isset($params['max'])) {
				$args['posts'] = $args['posts']->andWhere('plot_area', '!=', 0)
					->andWhere('plot_area', '<=', $params['max']);
			}

			if (empty($params['max']) && is_numeric($params['min']) && isset($params['min'])) {
				$args['posts'] = $args['posts']->andWhere('plot_area', '!=', 0)
					->andWhere('plot_area', '>=', $params['min']);
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_liveable_area($args)
	{
		if (isset($args['params']['filters']['liveable_area'])) {
			$params = [
				'min' => $args['params']['filters']['liveable_area']['min'],
				'max' => $args['params']['filters']['liveable_area']['max']
			];

			if ($params['min'] === 0) {
				$params['min'] = false;
			}

			if ($params['max'] === 0) {
				$params['max'] = false;
			}

			if (isset($params['min']) && is_numeric($params['min']) && isset($params['max']) && is_numeric($params['max'])) {
				$args['posts'] = $args['posts']->andWhere('liveable_area', '!=', 0)
					->andWhere('liveable_area', '>=', $params['min'])
					->andWhere('liveable_area', '<=', $params['max']);
			}

			if (empty($params['min']) && is_numeric($params['max']) && isset($params['max'])) {
				$args['posts'] = $args['posts']->andWhere('liveable_area', '!=', 0)
					->andWhere('liveable_area', '<=', $params['max']);
			}

			if (empty($params['max']) && is_numeric($params['min']) && isset($params['min'])) {
				$args['posts'] = $args['posts']->andWhere('liveable_area', '!=', 0)
					->andWhere('liveable_area', '>=', $params['min']);
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_bedrooms($args)
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
				$args['posts'] = $args['posts']->andWhere('bedrooms', '>=', $params['min'])
					->andWhere('bedrooms', '<=', $params['max']);
			}

			if (empty($params['min']) && is_numeric($params['max']) && isset($params['max'])) {
				$args['posts'] = $args['posts']->andWhere('bedrooms', '<=', $params['max']);
			}

			if (empty($params['max']) && is_numeric($params['min']) && isset($params['min'])) {
				$args['posts'] = $args['posts']->andWhere('bedrooms', '>=', $params['min']);
			}
		}
		return $args['posts'];
	}

	public static function sql_filter_geolocation($args)
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

			$args['posts'] = $args['posts']->andWhere('lat', '>', $boundingBox->getMinLatitude())
				->andWhere('lat', '<=', $boundingBox->getMaxLatitude());

			$args['posts'] = $args['posts']->andWhere('lng', '>', $boundingBox->getMinLongitude())
				->andWhere('lng', '<=', $boundingBox->getMaxLongitude());
		}
		return $args['posts'];
	}

	public static function sql_filter_multi_geolocation($args)
	{
		if (isset($args['params']['filters']['locations']) && count($args['params']['filters']['locations']) > 0) {
			$args['posts'] = $args['posts']->andWhere(function ($q) use ($args) {
				$count = 0;
				foreach ($args['params']['filters']['locations'] as $location) {
					$geopoint = new GeoPoint($location['latLng']['lat'], $location['latLng']['lng']);
					$distance = WP_SweepBright_Helpers::settings_form()['geo_distance'];
					if (isset($args['params']['filters']['location']['distance']) && $args['params']['filters']['location']['distance']) {
						$distance = $args['params']['filters']['location']['distance'];
					}
					$boundingBox = $geopoint->boundingBox(intval($distance), 'km');

					if ($count === 0) {
						$q->andWhere(function ($w) use ($boundingBox) {
							$w->andWhere('lat', '>', $boundingBox->getMinLatitude());
							$w->andWhere('lat', '<=', $boundingBox->getMaxLatitude());

							$w->andWhere('lng', '>', $boundingBox->getMinLongitude());
							$w->andWhere('lng', '<=', $boundingBox->getMaxLongitude());
						});
					} else {
						$q->orWhere(function ($w) use ($boundingBox) {
							$w->andWhere('lat', '>', $boundingBox->getMinLatitude());
							$w->andWhere('lat', '<=', $boundingBox->getMaxLatitude());

							$w->andWhere('lng', '>', $boundingBox->getMinLongitude());
							$w->andWhere('lng', '<=', $boundingBox->getMaxLongitude());
						});
					}

					$count += 1;
				}
			});
		}
		return $args['posts'];
	}

	public static function sql_filter_favorites($args)
	{
		if (isset($args['params']['favorites']) && $args['params']['favorites']) {
			if (isset($_COOKIE['favorites']) && json_decode($_COOKIE['favorites'], true)) {
				$args['posts'] = $args['posts']->whereIn('post_id', json_decode($_COOKIE['favorites'], true));
			} else {
				$args['posts'] = $args['posts']->whereIn('post_id', [0]);
			}
		}
		return $args['posts'];
	}


	public static function sql_order_by_relevance($args)
	{
		if ((isset($args['params']['sort']) && $args['params']['sort']['orderBy'] === 'relevance') || $args['params']['recent']) {
			$has_location = false;

			if (isset($args['params']['filters']['location']['lat']) && isset($args['params']['filters']['location']['lng']) && $args['params']['filters']['location']['lat'] && $args['params']['filters']['location']['lng']) {
				$has_location = true;
				$lat = $args['params']['filters']['location']['lat'];
				$lng = $args['params']['filters']['location']['lng'];
			}

			// Order by status
			$condition_let = "CASE WHEN status = 'lost' THEN 8.5 WHEN status = 'prospect' THEN 7.5 WHEN status = 'rented' THEN 6.5 WHEN status = 'bid' THEN 5.5 WHEN status = 'sold' THEN 4.5 WHEN status = 'option' THEN 3.5 WHEN status = 'under_contract' THEN 2.5 WHEN status = 'available' THEN 1.5 END";
			$condition_sale = "CASE WHEN status = 'lost' THEN 8 WHEN status = 'prospect' THEN 7 WHEN status = 'rented' THEN 6 WHEN status = 'bid' THEN 5 WHEN status = 'sold' THEN 4 WHEN status = 'option' THEN 3 WHEN status = 'under_contract' THEN 2 WHEN status = 'available' THEN 1 END";

			if (WP_SweepBright_Helpers::setting('available_properties') === 'available') {
				$args['posts'] = $args['posts']
					// Order by open homes
					->order_by("CASE WHEN has_open_home = 1 AND status = 'available' THEN 0 END", 'DESC');
			} else {
				$args['posts'] = $args['posts']
					// Order by open homes
					->order_by("CASE WHEN has_open_home = 1 AND status = 'under_contract' THEN 0 END", 'DESC');
			}

			$args['posts'] = $args['posts']
				// Order by negotiation
				->order_by("CASE WHEN negotiation = 'sale' THEN " . $condition_sale . " WHEN negotiation = 'let' THEN " . $condition_let . " END", 'ASC');

			// Order by location
			if ($has_location) {
				$args['posts'] = $args['posts']
					->order_by("ABS(lat-$lat) + ABS(lng - $lng)", "ASC");
			}

			// Order by date
			$args['posts'] = $args['posts']
				->order_by("date", "DESC");
		}
		return $args['posts'];
	}

	public static function sql_order_by_date($args)
	{
		if (isset($args['params']['sort']) && $args['params']['sort']['orderBy'] === 'date') {
			$args['posts'] = $args['posts']->order_by("date", $args['params']['sort']['order']);
		}
		return $args['posts'];
	}

	public static function sql_order_by_price($args)
	{
		if (isset($args['params']['sort']) && $args['params']['sort']['orderBy'] === 'price') {
			$args['posts'] = $args['posts']
				->order_by("ABS(price)", $args['params']['sort']['order']);
		}
		return $args['posts'];
	}

	public static function slugify($string)
	{
		return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

	public static function get_estates($posts, $params)
	{
		$results = [];

		foreach ($posts as $post) {
			// Get the ID
			$id = $post->post_id;

			if (isset($params['mapMode']) && $params['mapMode']) {
				if (empty(get_post_meta($id, 'location_hidden', true))) {
					$results = WP_SweepBright_Query::add_marker($id, $results);
				}
			} else {
				$results = WP_SweepBright_Query::add_estate($id, $results);
			}
		}
		return $results;
	}

	public static function add_marker($id, $results)
	{
		$image = '';
		if (get_post_meta($id, 'features_images', true)) {
			$attach_id = get_post_meta($id, 'features_images', true)[0];
			$image = wp_get_attachment_image_url($attach_id, 'medium');
		}

		$results[] = [
			'id' => get_post_meta($id, 'estate_id', true),
			'status' => get_post_meta($id, 'estate_status', true),
			'is_project' => get_post_meta($id, 'estate_is_project', true),
			'permalink' => get_the_permalink($id),
			'title' => [
				'en' => get_post_meta($id, 'estate_title_en', true),
				'nl' => get_post_meta($id, 'estate_title_nl', true),
				'fr' => get_post_meta($id, 'estate_title_fr', true),
			],
			'location' => [
				'hidden' => get_post_meta($id, 'location_hidden', true),
				'latitude' => get_post_meta($id, 'location_latitude', true),
				'longitude' => get_post_meta($id, 'location_longitude', true),
				'city' => get_post_meta($id, 'location_city', true),
				'street' => get_post_meta($id, 'location_street', true),
				'street_2' => get_post_meta($id, 'location_street_2', true),
				'number' => get_post_meta($id, 'location_number', true),
				'box' => get_post_meta($id, 'location_box', true),
				'addition' => get_post_meta($id, 'location_addition', true),
				'country' => get_post_meta($id, 'location_country', true),
				'postal_code' => get_post_meta($id, 'location_postal_code', true),
				'formatted' => get_post_meta($id, 'location_formatted', true),
				'formatted_agency' => get_post_meta($id, 'location_formatted_agency', true),
			],
			'image' => $image,
			'images' => [
				[
					'sizes' => [
						'medium_large' => $image,
					]
				]
			],
			'price' => get_post_meta($id, 'price_amount', true),
		];

		return $results;
	}

	public static function add_estate($id, $results)
	{
		// Properties
		$properties = [];
		if (get_post_meta($id, 'estate_properties', true)) {
			foreach (get_field('estate', $id)['properties'] as $property) {
				if ($property) {
					$unit_id = WP_SweepBright_Helpers::get_post_ID_from_estate($property['property_item']);
					if ($unit_id) {
						$properties[] = [
							'id' => $property['property_item'],
							'status' => get_post_meta($unit_id, 'estate_status', true),
						];
					}
				}
			}
		}

		// Images
		$images = [];
		if (get_post_meta($id, 'features_images', true)) {
			foreach (array_slice(get_post_meta($id, 'features_images', true), 0, 5) as $image) {
				if ($image) {
					$images[]['sizes'] = [
						'thumbnail' => wp_get_attachment_image_url($image, 'thumbnail'),
						'medium' => wp_get_attachment_image_url($image, 'medium'),
						'medium_large' => wp_get_attachment_image_url($image, 'medium'),
						'large' => wp_get_attachment_image_url($image, 'large'),
					];
				}
			}
		}

		// Render card information
		$results[] = [
			'id' => $id,
			'permalink' => get_the_permalink($id),
			'date' => get_the_time('U', $id),
			'meta' => [
				'conditions' => [
					'general_condition' => get_post_meta($id, 'conditions_general_condition', true),
				],
				'custom_fields' => get_field('custom_fields', $id),
				'estate' => [
					'id' => get_post_meta($id, 'estate_id', true),
					'status' => get_post_meta($id, 'estate_status', true),
					'title' => [
						'en' => get_post_meta($id, 'estate_title_en', true),
						'nl' => get_post_meta($id, 'estate_title_nl', true),
						'fr' => get_post_meta($id, 'estate_title_fr', true),
					],
					'description' => [
						'en' => get_post_meta($id, 'estate_description_en', true),
						'nl' => get_post_meta($id, 'estate_description_nl', true),
						'fr' => get_post_meta($id, 'estate_description_fr', true),
					],
					'is_project' => get_post_meta($id, 'estate_is_project', true) === '1' ? true : false,
					'project_id' => get_post_meta($id, 'estate_project_id', true) === '1' ? true : false,
					'properties' => $properties,
				],
				'facilities' => [
					'kitchens' => get_post_meta($id, 'facilities_kitchens', true),
					'bathrooms' => get_post_meta($id, 'facilities_bathrooms', true),
					'toilets' => get_post_meta($id, 'facilities_toilets', true),
					'floors' => get_post_meta($id, 'facilities_floors', true),
					'bedrooms' => get_post_meta($id, 'facilities_bedrooms', true),
					'living_rooms' => get_post_meta($id, 'facilities_living_rooms', true),
					'storage_rooms' => get_post_meta($id, 'facilities_storage_rooms', true),
					'manufacturing_areas' => get_post_meta($id, 'facilities_manufacturing_areas', true),
					'showrooms' => get_post_meta($id, 'facilities_showrooms', true),
				],
				'features' => [
					'images' => $images,
					'negotiation' => get_post_meta($id, 'features_negotiation', true),
					'type' => get_post_meta($id, 'features_type', true),
					'sub_type' => get_post_meta($id, 'features_sub_type', true),
				],
				'location' => [
					'hidden' => get_post_meta($id, 'location_hidden', true),
					'latitude' => get_post_meta($id, 'location_latitude', true),
					'longitude' => get_post_meta($id, 'location_longitude', true),
					'city' => get_post_meta($id, 'location_city', true),
					'street' => get_post_meta($id, 'location_street', true),
					'street_2' => get_post_meta($id, 'location_street_2', true),
					'number' => get_post_meta($id, 'location_number', true),
					'box' => get_post_meta($id, 'location_box', true),
					'addition' => get_post_meta($id, 'location_addition', true),
					'country' => get_post_meta($id, 'location_country', true),
					'postal_code' => get_post_meta($id, 'location_postal_code', true),
					'formatted' => get_post_meta($id, 'location_formatted', true),
					'formatted_agency' => get_post_meta($id, 'location_formatted_agency', true),
				],
				'negotiator' => [
					'email' => get_post_meta($id, 'negotiator_email', true),
				],
				'open_homes' => get_field('open_homes', $id),
				'price' => [
					'amount' => get_post_meta($id, 'price_amount', true),
					'currency' => get_post_meta($id, 'price_currency', true),
					'hidden' => get_post_meta($id, 'price_hidden', true),
					'price_costs' => get_post_meta($id, 'price_price_costs', true),
				],
				'sizes' => [
					'liveable_area' => [
						'size' => get_post_meta($id, 'sizes_liveable_area_size', true),
						'unit' => get_post_meta($id, 'sizes_liveable_area_unit', true),
					],
					'plot_area' => [
						'size' => get_post_meta($id, 'sizes_plot_area_size', true),
						'unit' => get_post_meta($id, 'sizes_plot_area_unit', true),
					],
				],
			]
		];
		return $results;
	}

	public static function list($params)
	{
		if (isset($params['maxPerPage']) && $params['maxPerPage']) {
			$max_per_page = $params['maxPerPage'];
		} else {
			$max_per_page = WP_SweepBright_Helpers::settings_form()['max_per_page'];
		}
		if (empty($params['page'])) {
			$params['page'] = 1;
		}
		$offset = ($params['page'] * $max_per_page) - $max_per_page;

		// Query
		$query = \PluginEver\QueryBuilder\Query::init();

		// Get posts
		$posts = $query->from('sweepbright_estates')
			->select('post_id');

		// Filter: hide prospects
		$posts = WP_SweepBright_Query::sql_filter_hide_prospects([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: hide available
		$posts = WP_SweepBright_Query::sql_filter_hide_available([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: hide lost
		$posts = WP_SweepBright_Query::sql_filter_hide_lost([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: hide units
		$posts = WP_SweepBright_Query::sql_filter_hide_units([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: status
		$posts = WP_SweepBright_Query::sql_filter_status([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: new home
		$posts = WP_SweepBright_Query::sql_filter_new_home([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: negotiation
		$posts = WP_SweepBright_Query::sql_filter_negotiation([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: category
		$posts = WP_SweepBright_Query::sql_filter_category([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: subcategory
		$posts = WP_SweepBright_Query::sql_filter_subcategory([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: agent
		$posts = WP_SweepBright_Query::sql_filter_agent([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: office
		$posts = WP_SweepBright_Query::sql_filter_office([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: price
		$posts = WP_SweepBright_Query::sql_filter_price([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: plot area
		$posts = WP_SweepBright_Query::sql_filter_plot_area([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: liveable area
		$posts = WP_SweepBright_Query::sql_filter_liveable_area([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: bedrooms
		$posts = WP_SweepBright_Query::sql_filter_bedrooms([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: geolocation
		$posts = WP_SweepBright_Query::sql_filter_geolocation([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: multi geolocations
		$posts = WP_SweepBright_Query::sql_filter_multi_geolocation([
			'posts' => $posts,
			'params' => $params,
		]);

		// Filter: show favorites
		$posts = WP_SweepBright_Query::sql_filter_favorites([
			'posts' => $posts,
			'params' => $params,
		]);

		// Order: relevance
		$posts = WP_SweepBright_Query::sql_order_by_relevance([
			'posts' => $posts,
			'params' => $params,
		]);

		// Order: date
		$posts = WP_SweepBright_Query::sql_order_by_date([
			'posts' => $posts,
			'params' => $params,
		]);

		// Order: price
		$posts = WP_SweepBright_Query::sql_order_by_price([
			'posts' => $posts,
			'params' => $params,
		]);

		// Count posts
		$total_posts = count($posts->get());
		$total_pages = abs(ceil($total_posts / $max_per_page));

		// Output
		if ($params['recent']) {
			$posts = $posts->limit($params['recent'])
				->offset($offset)
				->get();
		} else if ($params['mapMode']) {
			$posts = $posts->get();
		} else {
			$posts = $posts->limit($max_per_page)
				->offset($offset)
				->get();
		}

		// Render results
		$results['estates'] = WP_SweepBright_Query::get_estates($posts, $params);

		if (empty($params['mapMode'])) {
			// Process: add min max pricing to projects
			$results['estates'] = WP_SweepBright_Query::process_filter_min_max_price($results['estates']);
		}

		$results['totalPages'] = $total_pages;
		$results['totalPosts'] = $total_posts;

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
