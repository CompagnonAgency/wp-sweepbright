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
      'layout' => 'row',
			'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'hasOpenHome',
					'label'  => 'Enabled',
					'name'   => 'hasOpenHome',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'open_home_date',
					'label'  => 'Dates',
					'name'   => 'open_home_date',
					'type'   => 'flexible_content',
					'button_label' => 'Add open home',
					'layouts' => [
						[
							'key'           => 'open_home_layout',
							'name'          => 'open_home_layout',
							'label'         => 'Open home',
							'display'       => 'block',
							'sub_fields'    => [
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
						],
					]
				],
      ],
    ];
	}

}
