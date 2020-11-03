<?php

/**
 * FieldLegal.
 *
 * @package FieldLegal
 */

class FieldLegal {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'legal_mentions',
      'label' => 'Legal mentions',
      'name' => 'legal_mentions',
			'type' => 'group',
      'layout' => 'row',
			'sub_fields' => [
				[
					'key'    => 'legal_mentions_en',
					'label'  => 'English',
					'name'   => 'en',
					'type'   => 'text',
				],
				[
					'key'    => 'legal_mentions_fr',
					'label'  => 'French',
					'name'   => 'fr',
					'type'   => 'text',
				],
				[
					'key'    => 'legal_mentions_nl',
					'label'  => 'Dutch',
					'name'   => 'nl',
					'type'   => 'text',
				]
			],
    ];
	}

}
