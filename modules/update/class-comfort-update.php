<?php

/**
 * FieldComfortUpdate.
 *
 * @package FieldComfortUpdate
 */

class FieldComfortUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('comfort', [
      'home_automation' => $estate['features']['comfort']['home_automation'],
    ], $post_id);

		update_field('comfort', [
      'water_softener' => $estate['features']['comfort']['water_softener'],
    ], $post_id);

		update_field('comfort', [
      'fireplace' => $estate['features']['comfort']['fireplace'],
    ], $post_id);

		update_field('comfort', [
      'walk_in_closet' => $estate['features']['comfort']['walk_in_closet'],
    ], $post_id);

		update_field('comfort', [
      'home_cinema' => $estate['features']['comfort']['home_cinema'],
    ], $post_id);

		update_field('comfort', [
      'wine_cellar' => $estate['features']['comfort']['wine_cellar'],
    ], $post_id);

		update_field('comfort', [
      'sauna' => $estate['features']['comfort']['sauna'],
    ], $post_id);

		update_field('comfort', [
      'fitness_room' => $estate['features']['comfort']['fitness_room'],
    ], $post_id);

		update_field('comfort', [
      'furnished' => $estate['features']['comfort']['furnished'],
    ], $post_id);
	}

}
