<?php

/**
 * FieldSizes.
 *
 * @package FieldSizes
 */

class FieldSizes {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'sizes',
      'name' => 'sizes',
      'label' => 'Sizes',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'           => 'sizes_plot_area',
					'name'          => 'plot_area',
					'label'         => 'Plot area',
					'layout'       => 'table',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'plot_area_size',
							'label'  => 'Size',
							'name'   => 'size',
							'type'   => 'number',
						],
						[
							'key'    => 'plot_area_unit',
							'label'  => 'Unit',
							'name'   => 'unit',
							'type'   => 'text',
						],
					]
				],
				[
					'key'           => 'sizes_liveable_area',
					'name'          => 'liveable_area',
					'label'         => 'Liveable area',
					'layout'       => 'table',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'liveable_area_size',
							'label'  => 'Size',
							'name'   => 'size',
							'type'   => 'number',
						],
						[
							'key'    => 'liveable_area_unit',
							'label'  => 'Unit',
							'name'   => 'unit',
							'type'   => 'text',
						],
					]
				],
			],
    ];
	}

}
