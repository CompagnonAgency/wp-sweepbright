<?php

/**
 * FieldBuildingUpdate.
 *
 * @package FieldBuildingUpdate
 */

class FieldBuildingUpdate
{

	public function __construct()
	{
	}

	public static function update($estate, $post_id)
	{
		update_field('building', [
			'renovation' => [
				'renovation_year' => $estate['building']['renovation']['year']
			],
		], $post_id);

		update_field('building', [
			'renovation' => [
				'renovation_description' => $estate['building']['renovation']['description']
			],
		], $post_id);

		update_field('building', [
			'construction' => [
				'construction_year' => $estate['building']['construction']['year']
			],
		], $post_id);

		update_field('building', [
			'construction' => [
				'construction_architect' => $estate['building']['construction']['architect']
			],
		], $post_id);
	}
}
