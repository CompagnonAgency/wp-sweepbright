<?php

/**
 * FieldNegotiator.
 *
 * @package FieldNegotiator
 */

class FieldNegotiator {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'negotiator',
      'name' => 'negotiator',
      'label' => 'Negotiator',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'negotiator_first_name',
					'label'  => 'First name',
					'name'   => 'first_name',
					'type'   => 'text',
				],
				[
					'key'    => 'negotiator_last_name',
					'label'  => 'Last name',
					'name'   => 'last_name',
					'type'   => 'text',
				],
				[
					'key'    => 'negotiator_email',
					'label'  => 'Email',
					'name'   => 'email',
					'type'   => 'text',
				],
				[
					'key'    => 'negotiator_phone',
					'label'  => 'Phone',
					'name'   => 'phone',
					'type'   => 'text',
				],
				[
					'key'    => 'negotiator_photo',
					'label'  => 'Photo',
					'name'   => 'photo',
					'type'   => 'image',
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				],
			],
    ];
	}

}
