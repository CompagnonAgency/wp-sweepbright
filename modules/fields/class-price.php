<?php

/**
 * FieldPrice.
 *
 * @package FieldPrice
 */

class FieldPrice {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'price',
      'name' => 'price',
      'label' => 'Price',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'amount',
					'label'  => 'Amount',
					'name'   => 'amount',
					'type'   => 'number',
				],
				[
					'key'    => 'currency',
					'label'  => 'Currency',
					'name'   => 'currency',
					'type'   => 'text',
				],
				[
					'key'    => 'price_hidden',
					'label'  => 'Hidden',
					'name'   => 'hidden',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'custom_price',
					'label'  => 'Custom price',
					'name'   => 'custom_price',
					'type'   => 'text',
				],
			],
    ];
	}

}
