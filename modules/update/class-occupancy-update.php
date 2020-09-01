<?php

/**
 * FieldOccupancyUpdate.
 *
 * @package FieldOccupancyUpdate
 */

class FieldOccupancyUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('occupancy', [
      'occupied' => $estate['occupancy']['occupied'],
    ], $post_id);

		update_field('occupancy', [
      'available_from' => $estate['occupancy']['available_from'],
    ], $post_id);

		update_field('occupancy', [
      'contact_details' => $estate['occupancy']['contact_details'],
    ], $post_id);

		update_field('occupancy', [
      'tenant_contract' => [
				'tenant_contract_end_date' => $estate['occupancy']['tenant_contract']['end_date']
			],
    ], $post_id);

		update_field('occupancy', [
      'tenant_contract' => [
				'tenant_contract_start_date' => $estate['occupancy']['tenant_contract']['start_date']
			],
    ], $post_id);
	}

}
