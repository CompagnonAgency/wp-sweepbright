<?php

/**
 * FieldLocationUpdate.
 *
 * @package FieldLocationUpdate
 */

class FieldLocationUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('location', [
      'location_hidden' => $estate['location']['hidden'],
    ], $post_id);

		update_field('location', [
      'latitude' => $estate['location']['geo']['latitude'],
    ], $post_id);

		update_field('location', [
      'longitude' => $estate['location']['geo']['longitude'],
    ], $post_id);

		update_field('location', [
      'city' => $estate['location']['city'],
    ], $post_id);

		update_field('location', [
      'street' => $estate['location']['street'],
    ], $post_id);

		update_field('location', [
      'street_2' => $estate['location']['street_2'],
    ], $post_id);

		update_field('location', [
      'number' => $estate['location']['number'],
    ], $post_id);

		update_field('location', [
      'box' => $estate['location']['box'],
    ], $post_id);

		update_field('location', [
      'addition' => $estate['location']['addition'],
    ], $post_id);

		update_field('location', [
      'country' => $estate['location']['country'],
    ], $post_id);

		update_field('location', [
      'postal_code' => $estate['location']['postal_code'],
    ], $post_id);

		update_field('location', [
      'formatted' => $estate['location']['formatted'],
    ], $post_id);

		update_field('location', [
      'formatted_agency' => $estate['location']['formatted_agency'],
    ], $post_id);
	}

}
