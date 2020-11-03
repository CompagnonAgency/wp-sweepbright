<?php

/**
 * WP_SweepBright_Query.
 *
 * This class contains helpers to display and manipulate data.
 *
 * @package    WP_SweepBright_Query
 */

use Brick\Money\Money;
use AnthonyMartin\GeoLocation\GeoPoint;

class WP_SweepBright_Query
{

	public function __construct()
	{
	}

	public static function min_max_living_area($iso)
	{
		$units = WP_SweepBright_Query::list_units(get_field('estate')['id'], false, false)['results'];
		$result = false;
		$results = [];

		foreach ($units as $unit) {
			if (get_field('sizes', $unit['id'])['liveable_area']['size']) {
				$results[] = floatval(get_field('sizes', $unit['id'])['liveable_area']['size']);
			}
		}
		if (count($results) === 2) {
			$result = [
				'min' => WP_SweepBright_Query::format_number(min($results), $iso),
				'max' => WP_SweepBright_Query::format_number(max($results), $iso),
			];
		} else if (count($results) === 1) {
			$result = [
				'min' => WP_SweepBright_Query::format_number($results[0], $iso),
				'max' => false,
			];
		}
		return $result;
	}

	public static function min_max_price($iso)
	{
		$units = WP_SweepBright_Query::list_units(get_field('estate')['id'], false, false)['results'];
		$result = false;
		$results = [];

		foreach ($units as $unit) {
			if (get_field('price', $unit['id'])['amount'] && !get_field('price', $unit['id'])['hidden']) {
				$results[] = floatval(get_field('price', $unit['id'])['amount']);
			}
		}
		if (count($results) === 2) {
			$result = [
				'min' => WP_SweepBright_Query::format_number(min($results), $iso),
				'max' => WP_SweepBright_Query::format_number(max($results), $iso),
			];
		} else if (count($results) === 1) {
			$result = [
				'min' => WP_SweepBright_Query::format_number($results[0], $iso),
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
		$format = new \NumberFormatter($iso, \NumberFormatter::DECIMAL);
		$output = $format->format($number);
		return $output;
	}

	public static function get_the_size($iso, $type)
	{
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

	public static function get_the_price($iso)
	{
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

	public static function filter_hide_units($filters)
	{
		array_push($filters, [
			'relation' => 'AND',
			[
				'key' => 'estate_project_id',
				'compare' => 'NOT EXISTS'
			]
		]);
		return $filters;
	}

	public static function filter_negotiation($params, $filters)
	{
		if (isset($params['filters']['negotiation']) && $params['filters']['negotiation']) {
			switch ($params['filters']['negotiation']) {
				case 'sale':
					array_push($filters, [
						'relation' => 'AND',
						[
							'key' => 'features_negotiation',
							'value' => 'sale',
							'compare' => '='
						]
					]);
					break;
				case 'let':
					array_push($filters, [
						'relation' => 'AND',
						[
							'key' => 'features_negotiation',
							'value' => 'let',
							'compare' => '='
						]
					]);
					break;
				default:
					break;
			}
		}
		return $filters;
	}

	public static function filter_category($params, $filters)
	{
		if (isset($params['filters']['category']) && count($params['filters']['category']) > 0) {
			array_push($filters, [
				'relation' => 'AND',
				[
					'key' => 'features_type',
					'value' => $params['filters']['category'],
					'compare' => 'IN'
				]
			]);
		}
		return $filters;
	}

	public static function filter_price($params, $filters)
	{
		if (isset($params['filters']['price']) && $params['filters']['price']['min']) {
			array_push($filters, [
				'relation' => 'AND',
				[
					'type' => 'NUMERIC',
					'key' => 'price_amount',
					'value' => $params['filters']['price']['min'],
					'compare' => '>='
				]
			]);
		}

		if (isset($params['filters']['price']) && $params['filters']['price']['max']) {
			array_push($filters, [
				'relation' => 'AND',
				[
					'type' => 'NUMERIC',
					'key' => 'price_amount',
					'value' => $params['filters']['price']['max'],
					'compare' => '<='
				]
			]);
		}
		return $filters;
	}

	public static function filter_plot_area($params, $filters)
	{
		if (isset($params['filters']['plot_area']) && $params['filters']['plot_area']['min']) {
			array_push($filters, [
				'relation' => 'AND',
				[
					'type' => 'NUMERIC',
					'key' => 'sizes_plot_area_size',
					'value' => $params['filters']['plot_area']['min'],
					'compare' => '>='
				]
			]);
		}

		if (isset($params['filters']['plot_area']) && $params['filters']['plot_area']['max']) {
			array_push($filters, [
				'relation' => 'AND',
				[
					'type' => 'NUMERIC',
					'key' => 'sizes_plot_area_size',
					'value' => $params['filters']['plot_area']['max'],
					'compare' => '<='
				]
			]);
		}
		return $filters;
	}

	public static function filter_liveable_area($params, $filters)
	{
		if (isset($params['filters']['liveable_area']) && $params['filters']['liveable_area']['min']) {
			array_push($filters, [
				'relation' => 'AND',
				[
					'type' => 'NUMERIC',
					'key' => 'sizes_liveable_area_size',
					'value' => $params['filters']['liveable_area']['min'],
					'compare' => '>='
				]
			]);
		}

		if (isset($params['filters']['liveable_area']) && $params['filters']['liveable_area']['max']) {
			array_push($filters, [
				'relation' => 'AND',
				[
					'type' => 'NUMERIC',
					'key' => 'sizes_liveable_area_size',
					'value' => $params['filters']['liveable_area']['max'],
					'compare' => '<='
				]
			]);
		}
		return $filters;
	}

	public static function filter_geolocation($params, $filters)
	{
		if (isset($params['filters']['location']) && $params['filters']['location']['lat'] && $params['filters']['location']['lng']) {
			$geopoint = new GeoPoint($params['filters']['location']['lat'], $params['filters']['location']['lng']);
			$boundingBox = $geopoint->boundingBox(intval(WP_SweepBright_Helpers::settings_form()['geo_distance']), 'km');

			array_push($filters, [
				'relation' => 'AND',
				[
					'key' => 'location_latitude',
					'value' => [$boundingBox->getMinLatitude(), $boundingBox->getMaxLatitude()],
					'compare' => 'BETWEEN',
				],
				[
					'key' => 'location_longitude',
					'value' => [$boundingBox->getMinLongitude(), $boundingBox->getMaxLongitude()],
					'compare' => 'BETWEEN',
				]
			]);
		}
		return $filters;
	}

	public static function order_by_date($params, $order_by)
	{
		if (isset($params['sort']) && $params['sort']['orderBy'] === 'date') {
			$order_by['order_by'] = 'date';
		}
		return $order_by;
	}

	public static function order_by_price($params, $order_by)
	{
		if (isset($params['sort']) && $params['sort']['orderBy'] === 'price') {
			$order_by['order_by'] = 'meta_value_num';
			$order_by['meta_key'] = 'price_amount';
		}
		return $order_by;
	}

	public static function order_by_relevance($params, $order_by, $filters)
	{
		if (isset($params['sort']) && $params['sort']['orderBy'] === 'relevance') {
			$filters[] = [
				'relation' => 'AND',
				[
					'relation' => 'OR',
					'query_sale' => [
						'key' => 'features_negotiation',
						'value' => 'sale',
					],
					'query_let' => [
						'key' => 'features_negotiation',
						'value' => 'let',
					],
				],
				[
					'relation' => 'OR',
					'query_rented' => [
						'key' => 'estate_status',
						'value' => 'rented',
					],
					'query_sold' => [
						'key' => 'estate_status',
						'value' => 'sold',
					],
					'query_option' => [
						'key' => 'estate_status',
						'value' => 'option',
					],
					'query_available' => [
						'key' => 'estate_status',
						'value' => 'available',
					],
				],
				[
					'relation' => 'OR',
					'query_open_home_disabled' => [
						'key' => 'open_homes_open_home_date',
						'compare' => 'NOT EXISTS',
					],
					'query_open_home_enabled' => [
						'key' => 'open_homes_open_home_date',
						'compare' => 'EXISTS',
					],
				]
			];

			$order_by['order_by'] = [
				'query_open_home_disabled' => 'DESC',
				'query_open_home_enabled' => 'ASC',

				'query_rented' => 'ASC',
				'query_sold' => 'ASC',
				'query_option' => 'ASC',
				'query_available' => 'DESC',

				'query_sale' => 'DESC',
				'query_let' => 'ASC',

				'date' => 'DESC',
			];
		}
		return [
			'filters' => $filters,
			'order_by' => $order_by
		];
	}

	public static function list($params)
	{
		// Default filters
		$filters = [];

		// Default sort
		$order_by = [
			'order_by' => 'date',
			'meta_key' => false,
		];

		// Recent posts
		if ($params['recent'] && empty($params['sort'])) {
			$params['sort']['order'] = 'desc';
			$params['sort']['orderBy'] = 'relevance';
		}

		// Filter: applied by default
		$filters = WP_SweepBright_Query::filter_hide_units($filters);

		// Filter: negotiation
		$filters = WP_SweepBright_Query::filter_negotiation($params, $filters);

		// Filter: category
		$filters = WP_SweepBright_Query::filter_category($params, $filters);

		// Filter: price
		$filters = WP_SweepBright_Query::filter_price($params, $filters);

		// Filter: plot_area
		$filters = WP_SweepBright_Query::filter_plot_area($params, $filters);

		// Filter: liveable_area
		$filters = WP_SweepBright_Query::filter_liveable_area($params, $filters);

		// Filter: geolocation
		$filters = WP_SweepBright_Query::filter_geolocation($params, $filters);

		// Order: date
		$order_by = WP_SweepBright_Query::order_by_date($params, $order_by);

		// Order: price
		$order_by = WP_SweepBright_Query::order_by_price($params, $order_by);

		// Order: relevance 
		$filters = WP_SweepBright_Query::order_by_relevance($params, $order_by, $filters)['filters'];
		$order_by = WP_SweepBright_Query::order_by_relevance($params, $order_by, $filters)['order_by'];

		// Recent posts
		if ($params['recent']) {
			$postsPerPage = $params['recent'];
			$paged = 1;
		} else {
			$postsPerPage = WP_SweepBright_Helpers::settings_form()['max_per_page'];
			$paged = $params['page'];
		}

		// WP Query
		$query = new WP_Query([
			'posts_per_page' => $postsPerPage,
			'paged' => $paged,
			'post_status' => 'publish',
			'post_type' => 'sweepbright_estates',
			'fields' => 'ids',
			'meta_key' => $order_by['meta_key'],
			'meta_query' => $filters,
			'order' => $params['sort']['order'],
			'orderby' => $order_by['order_by'],
		]);
		$posts = $query->posts;

		// Build array with the ACF custom fields
		$results = [
			'totalPages' => $query->max_num_pages,
			'totalPosts' => $query->found_posts,
		];

		foreach ($posts as $post) {
			$results['estates'][] = [
				'id' => $post,
				'permalink' => get_the_permalink($post),
				'date' => get_the_time('U', $post),
				'meta' => get_fields($post),
			];
		}

		if (isset($params['ids'])) {
			$results = $posts;
		}

		return $results;
	}

	public static function list_units($project_id, $is_paged, $page)
	{
		if ($is_paged) {
			$posts_per_page = 10;
			$paged = $page;
		} else {
			$posts_per_page = -1;
			$paged = false;
		}

		$results = [];
		$query = new WP_Query([
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
					'value' => $project_id,
					'compare' => '='
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
