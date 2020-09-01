<?php

/**
 * FieldOrientation.
 *
 * @package FieldOrientation
 */

class FieldOrientation {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'orientation',
      'name' => 'orientation',
      'label' => 'Orientation',
      'layout' => 'table',
      'type' => 'group',
			'sub_fields'    => [
				[
					'key'    => 'garden_orientation',
					'label'  => 'Garden orientation',
					'name'   => 'garden_orientation',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'N' => 'N',
						'NE' => 'NE',
						'E' => 'E',
						'SE' => 'SE',
						'S' => 'S',
						'SW' => 'SW',
						'W' => 'W',
						'NW' => 'NW',
					]
				],
				[
					'key'    => 'terrace_orientation',
					'label'  => 'Terrace orientation',
					'name'   => 'terrace_orientation',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'N' => 'N',
						'NE' => 'NE',
						'E' => 'E',
						'SE' => 'SE',
						'S' => 'S',
						'SW' => 'SW',
						'W' => 'W',
						'NW' => 'NW',
					]
				],
			],
    ];
	}

}
