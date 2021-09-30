<?php

/**
 * FieldLocation.
 *
 * @package FieldLocation
 */

class FieldLocation
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
		return [
			'key' => 'location',
			'name' => 'location',
			'label' => 'Location',
			'layout' => 'row',
			'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'location_hidden',
					'label'  => 'Hidden',
					'name'   => 'hidden',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'latitude',
					'label'  => 'Latitude',
					'name'   => 'latitude',
					'type'   => 'number',
				],
				[
					'key'    => 'longitude',
					'label'  => 'Longitude',
					'name'   => 'longitude',
					'type'   => 'number',
				],
				[
					'key'    => 'city',
					'label'  => 'City',
					'name'   => 'city',
					'type'   => 'text',
				],
				[
					'key'    => 'street',
					'label'  => 'Street',
					'name'   => 'street',
					'type'   => 'text',
				],
				[
					'key'    => 'street_2',
					'label'  => 'Street 2',
					'name'   => 'street_2',
					'type'   => 'text',
				],
				[
					'key'    => 'number',
					'label'  => 'Number',
					'name'   => 'number',
					'type'   => 'text',
				],
				[
					'key'    => 'box',
					'label'  => 'Box',
					'name'   => 'box',
					'type'   => 'text',
				],
				[
					'key'    => 'addition',
					'label'  => 'Addition',
					'name'   => 'addition',
					'type'   => 'text',
				],
				[
					'key'    => 'floor',
					'label'  => 'Floor',
					'name'   => 'floor',
					'type'   => 'text',
				],
				[
					'key'    => 'country',
					'label'  => 'Country',
					'name'   => 'country',
					'type'   => 'text',
				],
				[
					'key'    => 'postal_code',
					'label'  => 'Postal code',
					'name'   => 'postal_code',
					'type'   => 'text',
				],
				[
					'key'    => 'formatted',
					'label'  => 'Formatted',
					'name'   => 'formatted',
					'type'   => 'text',
				],
				[
					'key'    => 'formatted_agency',
					'label'  => 'Formatted agency',
					'name'   => 'formatted_agency',
					'type'   => 'text',
				],
			],
		];
	}
}
