<?php

/**
 * FieldSizesUpdate.
 *
 * @package FieldSizesUpdate
 */

class FieldSizesUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('sizes', [
      'sizes_plot_area' => [
				'plot_area_size' => $estate['sizes']['plot_area']['size']
			],
    ], $post_id);

		update_field('sizes', [
      'sizes_plot_area' => [
				'plot_area_unit' => $estate['sizes']['plot_area']['unit']
			],
    ], $post_id);

		update_field('sizes', [
      'sizes_liveable_area' => [
				'liveable_area_size' => $estate['sizes']['liveable_area']['size']
			],
    ], $post_id);

		update_field('sizes', [
      'sizes_liveable_area' => [
				'liveable_area_unit' => $estate['sizes']['liveable_area']['unit']
			],
    ], $post_id);
	}

}
