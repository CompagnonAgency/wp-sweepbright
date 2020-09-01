<?php

/**
 * FieldConditionsUpdate.
 *
 * @package FieldConditionsUpdate
 */

class FieldConditionsUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('conditions', [
      'kitchen_condition' => $estate['kitchen_condition'],
    ], $post_id);

		update_field('conditions', [
      'bathroom_condition' => $estate['bathroom_condition'],
    ], $post_id);

		update_field('conditions', [
      'general_condition' => $estate['general_condition'],
    ], $post_id);
	}

}
