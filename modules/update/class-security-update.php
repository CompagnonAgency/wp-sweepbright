<?php

/**
 * FieldSecurityUpdate.
 *
 * @package FieldSecurityUpdate
 */

class FieldSecurityUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('security', [
      'alarm' => $estate['features']['security']['alarm'],
    ], $post_id);

		update_field('security', [
      'concierge' => $estate['features']['security']['concierge'],
    ], $post_id);

		update_field('security', [
      'video_surveillance' => $estate['features']['security']['video_surveillance'],
    ], $post_id);
	}

}
