<?php

/**
 * FieldCustom.
 *
 * @package FieldCustom
 */

class FieldCustom {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'custom_fields',
      'name' => 'custom_fields',
      'label' => 'Custom fields',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
        [
					'key'    => 'phase',
					'label'  => 'Phase',
					'name'   => 'phase',
					'type'   => 'text',
				],
				[
					'key'    => 'usp',
					'label'  => 'Unique selling points',
					'name'   => 'usp',
					'type'   => 'flexible_content',
					'button_label' => 'Add USP',
					'layouts' => [
						[
							'key'           => 'usp_layout',
							'name'          => 'usp_layout',
							'label'         => 'USP item',
							'display'       => 'block',
							'sub_fields'    => [
								[
                  'key'    => 'usp_item',
                  'label'  => 'USP item',
                  'name'   => 'usp_item',
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
