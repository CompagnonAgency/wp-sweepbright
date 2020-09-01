<?php

/**
 * FieldComfort.
 *
 * @package FieldComfort
 */

class FieldComfort {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'comfort',
      'name' => 'comfort',
      'label' => 'Comfort',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'home_automation',
					'label'  => 'Home automation',
					'name'   => 'home_automation',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'water_softener',
					'label'  => 'Water softener',
					'name'   => 'water_softener',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'fireplace',
					'label'  => 'Fireplace',
					'name'   => 'fireplace',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'walk_in_closet',
					'label'  => 'Walk in closet',
					'name'   => 'walk_in_closet',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'home_cinema',
					'label'  => 'Home cinema',
					'name'   => 'home_cinema',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'wine_cellar',
					'label'  => 'Wine cellar',
					'name'   => 'wine_cellar',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'sauna',
					'label'  => 'Sauna',
					'name'   => 'sauna',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'fitness_room',
					'label'  => 'Fitness room',
					'name'   => 'fitness_room',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'furnished',
					'label'  => 'Furnished',
					'name'   => 'furnished',
					'type'   => 'true_false',
					'ui' => true,
				],
			],
    ];
	}

}
