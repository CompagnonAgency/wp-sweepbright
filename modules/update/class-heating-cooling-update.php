<?php

/**
 * FieldHeatingCoolingUpdate.
 *
 * @package FieldHeatingCoolingUpdate
 */

class FieldHeatingCoolingUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('heating_cooling', [
      'central_heating' => $estate['features']['heating_cooling']['central_heating'],
    ], $post_id);

		update_field('heating_cooling', [
      'floor_heating' => $estate['features']['heating_cooling']['floor_heating'],
    ], $post_id);

		update_field('heating_cooling', [
      'air_conditioning' => $estate['features']['heating_cooling']['air_conditioning'],
    ], $post_id);
	}

}
