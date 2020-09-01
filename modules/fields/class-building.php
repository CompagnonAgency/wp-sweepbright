<?php

/**
 * FieldBuilding.
 *
 * @package FieldBuilding
 */

class FieldBuilding {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'building',
      'name' => 'building',
      'label' => 'Building',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'           => 'renovation',
					'name'          => 'renovation',
					'label'         => 'Renovation',
					'layout'       => 'table',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'renovation_year',
							'label'  => 'Year',
							'name'   => 'year',
							'type'   => 'number',
						],
						[
							'key'    => 'renovation_description',
							'label'  => 'Description',
							'name'   => 'description',
							'type'   => 'text',
						],
					]
				],
				[
					'key'           => 'construction',
					'name'          => 'construction',
					'label'         => 'Construction',
					'layout'       => 'table',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'construction_year',
							'label'  => 'Year',
							'name'   => 'year',
							'type'   => 'number',
						],
						[
							'key'    => 'construction_architect',
							'label'  => 'Architect',
							'name'   => 'architect',
							'type'   => 'text',
						],
					]
				]
			],
    ];
	}

}
