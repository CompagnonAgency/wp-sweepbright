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
					'key'    => 'tag',
					'label'  => 'Tag',
					'name'   => 'tag',
					'type'   => 'text',
				],
				[
					'key'    => 'style_estate',
					'label'  => 'Style',
					'name'   => 'style',
					'type'   => 'text',
				],
				[
					'key'    => 'type_estate',
					'label'  => 'Type',
					'name'   => 'type',
					'type'   => 'text',
				],
				[
					'key' => 'contact',
					'label' => 'Contact',
		      'name' => 'contact',
		      'type' => 'group',
		      'sub_fields' => [
						[
							'key'    => 'contact_title',
							'label'  => 'Title',
							'name'   => 'title',
							'type'   => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
						],
						[
							'key'    => 'contact_description',
							'label'  => 'Description',
							'name'   => 'description',
							'type'   => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
						],
					],
				],
				[
					'key' => 'contact_unit',
					'label' => 'Contact unit',
		      'name' => 'contact_unit',
		      'type' => 'group',
		      'sub_fields' => [
						[
							'key'    => 'contact_title_unit',
							'label'  => 'Title',
							'name'   => 'title',
							'type'   => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
						],
						[
							'key'    => 'contact_description_unit',
							'label'  => 'Description',
							'name'   => 'description',
							'type'   => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
						],
					],
				],
				[
					'key' => 'cta',
					'label' => 'Call to action',
		      'name' => 'cta',
		      'type' => 'group',
		      'sub_fields' => [
						[
							'key'    => 'cta_label',
							'label'  => 'Label',
							'name'   => 'label',
							'type'   => 'text',
						],
						[
							'key'    => 'cta_action',
							'label'  => 'Action',
							'name'   => 'action',
							'type'   => 'text',
							'default_value' => 'default',
						],
					],
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
