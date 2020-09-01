<?php

/**
 * FieldOrientationUpdate.
 *
 * @package FieldOrientationUpdate
 */

class FieldOrientationUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('orientation', [
      'garden_orientation' => $estate['garden_orientation'],
    ], $post_id);

		update_field('orientation', [
      'terrace_orientation' => $estate['terrace_orientation'],
    ], $post_id);
	}

}
