<?php

/**
 * FieldVendors.
 *
 * @package FieldVendors
 */

class FieldVendors {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'vendors',
      'name' => 'vendors',
      'label' => 'Vendors',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'vendor',
					'label'  => 'Vendors',
					'name'   => 'vendor',
					'type'   => 'flexible_content',
					'button_label' => 'Add vendor',
					'layouts' => [
						[
							'key'           => 'vendor_layout',
							'name'          => 'vendor_layout',
							'label'         => 'Vendor',
							'display'       => 'block',
							'sub_fields'    => [
								[
									'key'    => 'vendor_first_name',
									'label'  => 'First name',
									'name'   => 'first_name',
									'type'   => 'text',
								],
								[
									'key'    => 'vendor_last_name',
									'label'  => 'Last name',
									'name'   => 'last_name',
									'type'   => 'text',
								],
								[
									'key'    => 'vendor_email',
									'label'  => 'Email',
									'name'   => 'email',
									'type'   => 'text',
								],
								[
									'key'    => 'vendor_phone',
									'label'  => 'Phone',
									'name'   => 'phone',
									'type'   => 'text',
								],
							],
						],
					]
				],
			],
    ];
	}

}
