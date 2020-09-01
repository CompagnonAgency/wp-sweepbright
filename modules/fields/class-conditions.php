<?php

/**
 * FieldConditions.
 *
 * @package FieldConditions
 */

class FieldConditions {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'conditions',
      'name' => 'conditions',
      'label' => 'Conditions',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key' => 'kitchen_condition',
					'label' => 'Kitchen condition',
					'name' => 'kitchen_condition',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'good' => 'Good',
						'poor' => 'Poor',
						'fair' => 'Fair',
						'new' => 'New',
						'mint' => 'Mint',
					],
				],
				[
					'key' => 'bathroom_condition',
					'label' => 'Bathroom condition',
					'name' => 'bathroom_condition',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'good' => 'Good',
						'poor' => 'Poor',
						'fair' => 'Fair',
						'new' => 'New',
						'mint' => 'Mint',
					],
				],
				[
					'key' => 'general_condition',
					'label' => 'General condition',
					'name' => 'general_condition',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'good' => 'Good',
						'poor' => 'Poor',
						'fair' => 'Fair',
						'new' => 'New',
						'mint' => 'Mint',
					],
				],
			],
    ];
	}

}
