<?php

/**
 * FieldOpenHomesUpdate.
 *
 * @package FieldOpenHomesUpdate
 */

class FieldOpenHomesUpdate
{

	public function __construct()
	{
	}

	public static function update($estate, $post_id)
	{
		$open_homes = [];
		foreach ($estate['open_homes'] as $key => $open_home) {
			$open_homes[] = [
				'acf_fc_layout' => 'open_home_layout',
				'open_homes_start_date' => $open_home['start_date'],
				'open_homes_end_date' => $open_home['end_date'],
			];
		}

		if (count($open_homes) > 0) {
			update_field('open_homes', [
				'hasOpenHome' => true,
			], $post_id);
		} else {
			update_field('open_homes', [
				'hasOpenHome' => false,
			], $post_id);
		}

		update_field('open_homes', [
			'open_home_date' => $open_homes,
		], $post_id);
	}
}
