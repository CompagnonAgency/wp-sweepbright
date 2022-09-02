<?php

/**
 * FieldMandate.
 *
 * @package FieldMandate
 */

class FieldMandate
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
		return [
			'key' => 'mandate',
			'name' => 'mandate',
			'label' => 'Mandate',
			'layout' => 'row',
			'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'mandate_start_date',
					'label'  => 'Start date',
					'name'   => 'start_date',
					'type'   => 'text',
				],
				[
					'key'    => 'mandate_end_date',
					'label'  => 'End date',
					'name'   => 'end_date',
					'type'   => 'text',
				],
				[
					'key'    => 'mandate_exclusive',
					'label'  => 'Exclusive',
					'name'   => 'exclusive',
					'type'   => 'true_false',
					'ui' => true,
				],
			],
		];
	}
}
