<?php

/**
 * FieldOfficeUpdate.
 *
 * @package FieldOfficeUpdate
 */

class FieldOfficeUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('agency_office', [
      'office_id' => $estate['office']['id'],
    ], $post_id);

		update_field('agency_office', [
      'office_name' => $estate['office']['name'],
    ], $post_id);
	}

}
