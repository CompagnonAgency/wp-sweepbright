<?php

/**
 * FieldAmenities.
 *
 * @package FieldAmenities
 */

class FieldAmenities {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'amenities',
      'name' => 'amenities',
      'label' => 'Amenities',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'amenity',
					'label'  => 'Amenities',
					'name'   => 'amenity',
					'type'   => 'flexible_content',
					'button_label' => 'Add amenity',
					'layouts' => [
						[
							'key'           => 'amenity_layout',
							'name'          => 'amenity_layout',
							'label'         => 'Amenity',
							'display'       => 'block',
							'sub_fields'    => [
								[
									'key' => 'amenity_type',
									'label' => 'Type',
									'name' => 'amenity_type',
									'type' => 'select',
									'default_value' => '',
									'readonly' => 0,
									'disabled' => 0,
									'choices' => [
										'pool' => 'Pool',
										'basement' => 'Basement',
										'terrace' => 'Terrace',
										'garden' => 'Garden',
										'parking' => 'Parking',
										'lift' => 'Lift',
										'cooling_room' => 'Cooling room',
										'display_window' => 'Display window',
										'reception_area' => 'Reception area',
										'waiting_area' => 'Waiting area',
										'guesthouse' => 'Guesthouse',
										'attic' => 'Attic',
										'water_access' => 'Water access',
										'utilities_access' => 'Utilities access',
										'sewer_access' => 'Sewer access',
										'drainage' => 'Drainage',
										'road_access' => 'Road access',
										'print_and_copy_area' => 'Print and copy area',
										'server_room' => 'Server room',
										'storage_space' => 'Storage space',
										'electrical_gate' => 'Electrical gate',
										'manual_gate' => 'Manual gate',
										'fence' => 'Fence',
										'remote_control' => 'Remote control',
										'key_card' => 'Key card',
										'code' => 'Code',
										'climate_control' => 'Climate control',
									],
								],
							],
						],
					]
				],
			],
    ];
	}

}
