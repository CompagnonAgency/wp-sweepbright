<?php

/**
 * FieldAmenitiesUpdate.
 *
 * @package FieldAmenitiesUpdate
 */

class FieldAmenitiesUpdate
{

	public function __construct()
	{
	}

	public static function update($estate, $post_id)
	{
		if (isset($estate['amenities']) && is_countable($estate['amenities'])) {
			if (count($estate['amenities']) > 0) {
				$amenities = [];
				foreach ($estate['amenities'] as $amenity) {
					$amenities[] = [
						'acf_fc_layout' => 'amenity_layout',
						'amenity_type' => $amenity,
					];
				}
				update_field('amenities', [
					'amenity' => $amenities,
				], $post_id);
			}
		}
	}
}
