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

	public static function filter($args, $data) {
		// Defaults
		$MAX_INT = 4294967295; // Todo: is this integer too large?
		$data['price_min'] = $data['price_min'] ? $data['price_min'] : 0;
		$data['price_max'] = $data['price_max'] ? $data['price_max'] : $MAX_INT;
		$data['plot_area_min'] = $data['plot_area_min'] ? $data['plot_area_min'] : 0;
		$data['plot_area_max'] = $data['plot_area_max'] ? $data['plot_area_max'] : $MAX_INT;
		$data['liveable_area_min'] = $data['liveable_area_min'] ? $data['liveable_area_min'] : 0;
		$data['liveable_area_max'] = $data['liveable_area_max'] ? $data['liveable_area_max'] : $MAX_INT;

		// Filter parameters
		$filter_params = [
			'ids' => $data['ids'],
			'recent' => $data['recent'],
			'show_all' => $data['show_all'],
			'filtered' => $data['filtered'],
			'current_page' => $data['current_page'],
			'sort' => $data['sort'],
			'type' => $data['type'],
			'price_min' => (float)$data['price_min'],
			'price_max' => (float)$data['price_max'],
			'negotiation' => $data['negotiation'],
			'plot_area_min' => (float)$data['plot_area_min'],
			'plot_area_max' => (float)$data['plot_area_max'],
			'liveable_area_min' => (float)$data['liveable_area_min'],
			'liveable_area_max' => (float)$data['liveable_area_max'],
			'region' => $data['region'],
			'lat' => (float)$data['lat'],
			'lng' => (float)$data['lng'],
		];

		// Default relations
		$args['meta_query'] = [
			'relation' => 'AND',
			[
				'relation' => 'OR',
				[
					'key' => 'estate_status',
					'value' => 'available',
					'compare' => 'LIKE',
				],
				[
					'key' => 'estate_status',
					'value' => 'option',
					'compare' => 'LIKE',
				],
				[
					'key' => 'estate_status',
					'value' => 'rented',
					'compare' => 'LIKE',
				],
				[
					'key' => 'estate_status',
					'value' => 'sold',
					'compare' => 'LIKE',
				],
			],
		];

		// Get `order`
		if ($filter_params['sort']) {
			$order = strtoupper(explode('_', $filter_params['sort'])[1]);
			$order_by = explode('_', $filter_params['sort'])[0];
	
			// Order by date & price
			if ($order_by === 'date') {
				$args['orderby'] = [
					'meta_value' => 'DESC',
					'date' => $order
				];
			} else if ($order_by === 'price') {
				$args['meta_type'] = 'NUMERIC';
				$args['meta_key'] = 'price_amount';
				$args['orderby'] = [
					'price_amount' => $order
				];
			}
		}

		// Filters
		$args['meta_query'][] = [
			'relation' => 'AND',
			[
				'key' => 'features_type',
				'value' => $filter_params['type'],
				'compare' => 'LIKE'
			],
			[
				'key' => 'features_negotiation',
				'value' => $filter_params['negotiation'],
				'compare' => 'LIKE'
			],
			[
				'type' => 'NUMERIC',
				'key' => 'price_amount',
				'value' => $filter_params['price_min'],
				'compare' => '>='
			],
			[
				'type' => 'NUMERIC',
				'key' => 'price_amount',
				'value' => $filter_params['price_max'],
				'compare' => '<='
			],
			[
				'type' => 'NUMERIC',
				'key' => 'sizes_plot_area_size',
				'value' => $filter_params['plot_area_min'],
				'compare' => '>='
			],
			[
				'type' => 'NUMERIC',
				'key' => 'sizes_plot_area_size',
				'value' => $filter_params['plot_area_max'],
				'compare' => '<='
			],
			[
				'type' => 'NUMERIC',
				'key' => 'sizes_liveable_area_size',
				'value' => $filter_params['liveable_area_min'],
				'compare' => '>='
			],
			[
				'type' => 'NUMERIC',
				'key' => 'sizes_liveable_area_size',
				'value' => $filter_params['liveable_area_max'],
				'compare' => '<='
			],
		];

		// Pagination
		$args['paged'] = $filter_params['current_page'];
		if ($filter_params['filtered'] === 'true') {
			$args['paged'] = 1;
		}
	
		// Map view shows all results
		if ($filter_params['show_all'] === 'true') {
			$args['posts_per_page'] = -1;
			$args['nopaging'] = true;
		}

		// Geo distance
		if ($filter_params['lat'] && $filter_params['lng']) {
			$distance = WP_SweepBright_Helpers::settings_form()['geo_distance'];
			$wp_sweepbright_geo = new WP_SweepBright_Geo();
			$bbox = $wp_sweepbright_geo->get_bounding_box_deg($filter_params['lat'], $filter_params['lng'], $distance);

			$args['meta_query'][] = [
				'key' => 'location_latitude',
				'value' => [$bbox[1], $bbox[0]],
				'compare' => 'BETWEEN'
			];
	
			$args['meta_query'][] = [
				'key' => 'location_longitude',
				'value' => [$bbox[2], $bbox[3]],
				'compare' => 'BETWEEN'
			];
		}

		// Favorites
		if ($filter_params['ids'] && count($filter_params['ids']) > 0) {
			$args['post__in'] = $filter_params['ids'];
		}

		return $args;
	}

	public static function list($params) {
		// Default
		$args = [
			'post_status' => 'publish',
			'nopaging' => false,
			'post_type' => 'sweepbright_estates',
			'posts_per_page' => WP_SweepBright_Helpers::settings_form()['max_per_page'],
		];

		// Params
		if (isset($params['recent'])) {
			$args = array_merge($args, [
				'posts_per_page' => WP_SweepBright_Helpers::settings_form()['recent_total'],
				'order' => 'DESC',
				'orderby' => 'date',
			]);
		}

		// Filter parameters
		if (isset($params['params'])) {
			$args = WP_SweepBright_Query::filter($args, $params['params']);
		}

		// Query
		$loop = new WP_Query($args);

		// JSON return
		if ($params['json']) {
			$estates = [
				'total' => $loop->max_num_pages,
				'data' => [],
			];
			$query = $loop->get_posts();

			// Build query
			foreach ($query as $item) {
				$estates['data'][] = [
					'id' => $item->ID,
					'permalink' => get_the_permalink($item->ID),
					'meta' => get_fields($item->ID),
				];
			}

			// Don't encode JSON when called from a REST route
			if (isset($params['ajax'])) {
				$loop = $estates;
			} else {
				$loop = json_encode($estates);
			}
		}

		return $loop;
	}

}
