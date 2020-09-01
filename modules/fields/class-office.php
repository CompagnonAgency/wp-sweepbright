<?php

/**
 * FieldOffice.
 *
 * @package FieldOffice
 */

class FieldOffice {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'agency_office',
      'name' => 'office',
      'label' => 'Office',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'office_id',
					'label'  => 'ID',
					'name'   => 'id',
					'type'   => 'text',
				],
				[
					'key'    => 'office_name',
					'label'  => 'Name',
					'name'   => 'name',
					'type'   => 'text',
				],
			],
    ];
	}

}
