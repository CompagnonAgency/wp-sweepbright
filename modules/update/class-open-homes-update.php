<?php

/**
 * FieldOpenHomesUpdate.
 *
 * @package FieldOpenHomesUpdate
 */

class FieldOpenHomesUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('open_homes', [
      'open_homes_start_date' => $estate['open_homes']['start_date'],
    ], $post_id);

		update_field('open_homes', [
      'open_homes_end_date' => $estate['open_homes']['end_date'],
    ], $post_id);
	}

}
