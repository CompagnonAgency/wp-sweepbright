<?php

/**
 * FieldFacilities.
 *
 * @package FieldFacilities
 */

class FieldFacilities {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'facilities',
      'name' => 'facilities',
      'label' => 'Facilities',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'kitchens',
					'label'  => 'Kitchens',
					'name'   => 'kitchens',
					'type'   => 'number',
				],
				[
					'key'    => 'bathrooms',
					'label'  => 'Bathrooms',
					'name'   => 'bathrooms',
					'type'   => 'number',
				],
				[
					'key'    => 'toilets',
					'label'  => 'Toilets',
					'name'   => 'toilets',
					'type'   => 'text',
				],
				[
					'key'    => 'floors',
					'label'  => 'Floors',
					'name'   => 'floors',
					'type'   => 'text',
				],
				[
					'key'    => 'bedrooms',
					'label'  => 'Bedrooms',
					'name'   => 'bedrooms',
					'type'   => 'text',
				],
				[
					'key'    => 'living_rooms',
					'label'  => 'Living rooms',
					'name'   => 'living_rooms',
					'type'   => 'text',
				],
				[
					'key'    => 'storage_rooms',
					'label'  => 'Storage rooms',
					'name'   => 'storage_rooms',
					'type'   => 'text',
				],
				[
					'key'    => 'manufacturing_areas',
					'label'  => 'Manufacturing areas',
					'name'   => 'manufacturing_areas',
					'type'   => 'text',
				],
				[
					'key'    => 'showrooms',
					'label'  => 'Showrooms',
					'name'   => 'showrooms',
					'type'   => 'text',
				],
			],
    ];
	}

}
