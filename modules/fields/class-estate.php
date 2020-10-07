<?php

/**
 * FieldEstate.
 *
 * @package FieldEstate
 */

class FieldEstate {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'estate',
      'name' => 'estate',
      'label' => 'Estate',
      'layout' => 'row',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'estate_id',
          'label' => 'ID',
          'name' => 'id',
          'type' => 'text',
          'default_value' => '',
          'readonly' => 0,
          'disabled' => 0,
        ],
        [
          'key' => 'estate_status',
          'label' => 'Status',
          'name' => 'status',
          'type' => 'select',
          'default_value' => '',
          'readonly' => 0,
          'disabled' => 0,
					'allow_null' => 1,
          'choices' => [
            'option' => 'Option',
            'rented' => 'Rented',
            'sold' => 'Sold',
            'available' => 'Available',
            'prospect' => 'Prospect',
            'bid' => 'Bid',
            'under_contract' => 'Under contract',
          ],
        ],
				[
					'key' => 'estate_title',
					'label' => 'Title',
		      'name' => 'title',
		      'type' => 'group',
		      'sub_fields' => [
						[
							'key'    => 'estate_title_en',
							'label'  => 'English',
							'name'   => 'en',
							'type'   => 'text',
						],
						[
							'key'    => 'estate_title_fr',
							'label'  => 'French',
							'name'   => 'fr',
							'type'   => 'text',
						],
						[
							'key'    => 'estate_title_nl',
							'label'  => 'Dutch',
							'name'   => 'nl',
							'type'   => 'text',
						]
					],
				],
				[
					'key' => 'estate_description',
					'label' => 'Description',
		      'name' => 'description',
		      'type' => 'group',
		      'sub_fields' => [
						[
							'key'    => 'estate_description_en',
							'label'  => 'English',
							'name'   => 'en',
							'type'   => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
						],
						[
							'key'    => 'estate_description_fr',
							'label'  => 'French',
							'name'   => 'fr',
							'type'   => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
						],
						[
							'key'    => 'estate_description_nl',
							'label'  => 'Dutch',
							'name'   => 'nl',
							'type'   => 'wysiwyg',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
						]
					],
				],
				[
					'key'    => 'is_project',
					'label'  => 'Is project',
					'name'   => 'is_project',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'project_id',
					'label'  => 'Project ID',
					'name'   => 'project_id',
					'type' => 'text',
          'default_value' => '',
          'readonly' => 0,
          'disabled' => 0,
				],
				[
					'key'    => 'properties',
					'label'  => 'Properties',
					'name'   => 'properties',
					'type'   => 'flexible_content',
					'button_label' => 'Add property',
					'layouts' => [
						[
							'key'           => 'properties',
							'name'          => 'property_layout',
							'label'         => 'Property',
							'display'       => 'block',
							'sub_fields'    => [
								[
                  'key'    => 'property_item',
                  'label'  => 'Property item',
                  'name'   => 'property_item',
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
