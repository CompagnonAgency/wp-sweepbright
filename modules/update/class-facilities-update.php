<?php

/**
 * FieldFacilitiesUpdate.
 *
 * @package FieldFacilitiesUpdate
 */

class FieldFacilitiesUpdate
{

  public function __construct()
  {
  }

  public static function update($estate, $post_id)
  {
    update_field('facilities', [
      'kitchens' => $estate['kitchens'],
    ], $post_id);

    update_field('facilities', [
      'bathrooms' => $estate['bathrooms'],
    ], $post_id);

    update_field('facilities', [
      'toilets' => $estate['toilets'],
    ], $post_id);

    update_field('facilities', [
      'floors' => $estate['floors'],
    ], $post_id);

    update_field('facilities', [
      'bedrooms' => $estate['bedrooms'],
    ], $post_id);

    update_field('facilities', [
      'living_rooms' => $estate['living_rooms'],
    ], $post_id);

    update_field('facilities', [
      'storage_rooms' => $estate['storage_rooms'],
    ], $post_id);

    update_field('facilities', [
      'manufacturing_areas' => $estate['manufacturing_areas'],
    ], $post_id);

    update_field('facilities', [
      'showrooms' => $estate['showrooms'],
    ], $post_id);

    update_field('facilities', [
      'shower_rooms' => $estate['shower_rooms'],
    ], $post_id);
  }
}
