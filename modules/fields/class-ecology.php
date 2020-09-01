<?php

/**
 * FieldEcology.
 *
 * @package FieldEcology
 */

class FieldEcology {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'ecology',
      'name' => 'ecology',
      'label' => 'Ecology',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'double_glazing',
					'label'  => 'Double glazing',
					'name'   => 'double_glazing',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'solar_panels',
					'label'  => 'Solar panels',
					'name'   => 'solar_panels',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'solar_boiler',
					'label'  => 'Solar boiler',
					'name'   => 'solar_boiler',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'rainwater_harvesting',
					'label'  => 'Rainwater harvesting',
					'name'   => 'rainwater_harvesting',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'insulated_roof',
					'label'  => 'Insulated roof',
					'name'   => 'insulated_roof',
					'type'   => 'true_false',
					'ui' => true,
				],
			],
    ];
	}

}
