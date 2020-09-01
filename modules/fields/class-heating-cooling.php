<?php

/**
 * FieldHeatingCooling.
 *
 * @package FieldHeatingCooling
 */

class FieldHeatingCooling {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'heating_cooling',
      'name' => 'heating_cooling',
      'label' => 'Heating & cooling',
      'layout' => 'table',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'central_heating',
					'label'  => 'Central heating',
					'name'   => 'central_heating',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'floor_heating',
					'label'  => 'Floor heating',
					'name'   => 'floor_heating',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'air_conditioning',
					'label'  => 'Air conditioning',
					'name'   => 'air_conditioning',
					'type'   => 'true_false',
					'ui' => true,
				],
			],
    ];
	}

}
