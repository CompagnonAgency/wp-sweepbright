<?php

/**
 * FieldOpenHomes.
 *
 * @package FieldOpenHomes
 */

class FieldOpenHomes {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'open_homes',
      'name' => 'open_homes',
      'label' => 'Open homes',
      'layout' => 'table',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'open_homes_start_date',
					'label'  => 'Start date',
					'name'   => 'start_date',
					'type'   => 'text',
				],
				[
					'key'    => 'open_homes_end_date',
					'label'  => 'End date',
					'name'   => 'end_date',
					'type'   => 'text',
				]
      ],
    ];
	}

}
