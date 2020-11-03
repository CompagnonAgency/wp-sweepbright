<?php

/**
 * FieldPriceUpdate.
 *
 * @package FieldPriceUpdate
 */

class FieldPriceUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('price', [
      'amount' => $estate['price']['amount'],
    ], $post_id);

		update_field('price', [
      'currency' => $estate['price']['currency'],
    ], $post_id);

		update_field('price', [
      'price_hidden' => $estate['price']['hidden'],
    ], $post_id);

    update_field('price', [
      'price_costs' => [
        'price_costs_en' => $estate['price_costs']['en'],
        'price_costs_fr' => $estate['price_costs']['fr'],
        'price_costs_nl' => $estate['price_costs']['nl'],
      ],
    ], $post_id);

		update_field('price', [
      'custom_price' => $estate['custom_price'],
    ], $post_id);
	}

}
