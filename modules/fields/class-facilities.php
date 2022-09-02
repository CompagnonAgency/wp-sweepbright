<?php

/**
 * FieldFacilities.
 *
 * @package FieldFacilities
 */

class FieldFacilities
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
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
					'type'   => 'number',
				],
				[
					'key'    => 'floors',
					'label'  => 'Floors',
					'name'   => 'floors',
					'type'   => 'number',
				],
				[
					'key'    => 'bedrooms',
					'label'  => 'Bedrooms',
					'name'   => 'bedrooms',
					'type'   => 'number',
				],
				[
					'key'    => 'living_rooms',
					'label'  => 'Living rooms',
					'name'   => 'living_rooms',
					'type'   => 'number',
				],
				[
					'key'    => 'storage_rooms',
					'label'  => 'Storage rooms',
					'name'   => 'storage_rooms',
					'type'   => 'number',
				],
				[
					'key'    => 'manufacturing_areas',
					'label'  => 'Manufacturing areas',
					'name'   => 'manufacturing_areas',
					'type'   => 'number',
				],
				[
					'key'    => 'showrooms',
					'label'  => 'Showrooms',
					'name'   => 'showrooms',
					'type'   => 'number',
				],
				[
					'key'    => 'shower_rooms',
					'label'  => 'Shower rooms',
					'name'   => 'shower_rooms',
					'type'   => 'number',
				],
			],
		];
	}
}
