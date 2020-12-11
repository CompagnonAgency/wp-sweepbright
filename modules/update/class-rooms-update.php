<?php

/**
 * FieldRoomsUpdate.
 *
 * @package FieldRoomsUpdate
 */

class FieldRoomsUpdate
{

	public function __construct()
	{
	}

	public static function update($estate, $post_id)
	{
		if (isset($estate['rooms']) && is_countable($estate['rooms'])) {
			if (count($estate['rooms'] > 0)) {
				$rooms = [];
				foreach ($estate['rooms'] as $key => $room) {
					$rooms[] = [
						'acf_fc_layout' => 'room_layout',
						'room_type' => $room['type'],
						'room_size_description' => $room['size_description'],
						'room_size' => $room['size'],
						'room_unit' => $room['unit'],
						'room_ordinal' => $room['ordinal'],
					];
				}
				update_field('rooms', [
					'room' => $rooms,
				], $post_id);
			}
		}
	}
}
