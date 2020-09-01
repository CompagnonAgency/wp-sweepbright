<?php

/**
 * FieldRooms.
 *
 * @package FieldRooms
 */

class FieldRooms {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'rooms',
      'name' => 'rooms',
      'label' => 'Rooms',
      'layout' => 'row',
      'type' => 'group',
      'sub_fields' => [
				[
					'key'    => 'room',
					'label'  => 'Rooms',
					'name'   => 'room',
					'type'   => 'flexible_content',
					'button_label' => 'Add room',
					'layouts' => [
						[
							'key'           => 'room_layout',
							'name'          => 'room_layout',
							'label'         => 'Room',
							'display'       => 'block',
							'sub_fields'    => [
								[
									'key'    => 'room_type',
									'label'  => 'Type',
									'name'   => 'type',
									'type'   => 'text',
								],
								[
									'key'    => 'room_size_description',
									'label'  => 'Description',
									'name'   => 'size_description',
									'type'   => 'text',
								],
								[
									'key'    => 'room_size',
									'label'  => 'Size',
									'name'   => 'size',
									'type'   => 'text',
								],
								[
									'key' => 'room_unit',
									'label' => 'Unit',
									'name' => 'unit',
									'allow_null' => 1,
									'type' => 'select',
									'default_value' => '',
									'readonly' => 0,
									'disabled' => 0,
									'choices' => [
										'sq_ft' => 'Square feet',
										'sq_m' => 'Square meter',
										'are' => 'Are',
										'acre' => 'Acre',
									],
								],
								[
									'key'    => 'room_ordinal',
									'label'  => 'Ordinal',
									'name'   => 'ordinal',
									'type'   => 'number',
								],
							],
						],
					]
				],
      ],
    ];
	}

}
