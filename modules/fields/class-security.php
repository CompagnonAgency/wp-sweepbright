<?php

/**
 * FieldSecurity.
 *
 * @package FieldSecurity
 */

class FieldSecurity
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
		return [
			'key' => 'security',
			'name' => 'security',
			'label' => 'Security',
			'layout' => 'table',
			'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'alarm',
					'label'  => 'Alarm',
					'name'   => 'alarm',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'concierge',
					'label'  => 'Concierge',
					'name'   => 'concierge',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'video_surveillance',
					'label'  => 'Video surveillance',
					'name'   => 'video_surveillance',
					'type'   => 'true_false',
					'ui' => true,
				],
			],
		];
	}
}
