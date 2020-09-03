<?php

/**
 * FieldEcologyUpdate.
 *
 * @package FieldEcologyUpdate
 */

class FieldEcologyUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('ecology', [
      'double_glazing' => $estate['features']['ecology']['double_glazing'],
    ], $post_id);

		update_field('ecology', [
      'solar_panels' => $estate['features']['ecology']['solar_panels'],
    ], $post_id);

		update_field('ecology', [
      'solar_boiler' => $estate['features']['ecology']['solar_boiler'],
    ], $post_id);

    update_field('ecology', [
      'rainwater_harvesting' => $estate['features']['ecology']['rainwater_harvesting'],
    ], $post_id);

    update_field('ecology', [
      'insulated_roof' => $estate['features']['ecology']['insulated_roof'],
    ], $post_id);
	}

}
