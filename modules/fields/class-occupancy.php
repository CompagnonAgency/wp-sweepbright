<?php

/**
 * FieldOccupancy.
 *
 * @package FieldOccupancy
 */

class FieldOccupancy {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'occupancy',
      'name' => 'occupancy',
      'label' => 'Occupancy',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'occupied',
					'label'  => 'Occupied',
					'name'   => 'occupied',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'available_from',
					'label'  => 'Available from',
					'name'   => 'available_from',
					'type'   => 'text',
				],
				[
					'key'    => 'contact_details',
					'label'  => 'Contact details',
					'name'   => 'contact_details',
					'type'   => 'text',
				],
				[
					'key'           => 'tenant_contract',
					'name'          => 'tenant_contract',
					'label'         => 'Tenant contract',
					'layout'       => 'table',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'tenant_contract_end_date',
							'label'  => 'End date',
							'name'   => 'end_date',
							'type'   => 'text',
						],
						[
							'key'    => 'tenant_contract_start_date',
							'label'  => 'Start date',
							'name'   => 'start_date',
							'type'   => 'text',
						],
					]
				],
			],
    ];
	}

}
