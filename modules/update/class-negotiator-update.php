<?php

/**
 * FieldNegotiatorUpdate.
 *
 * @package FieldNegotiatorUpdate
 */

class FieldNegotiatorUpdate
{

  public function __construct()
  {
  }

  public static function update($estate, $post_id)
  {
    update_field('negotiator', [
      'negotiator_first_name' => $estate['negotiator']['first_name'],
    ], $post_id);

    update_field('negotiator', [
      'negotiator_last_name' => $estate['negotiator']['last_name'],
    ], $post_id);

    update_field('negotiator', [
      'negotiator_email' => $estate['negotiator']['email'],
    ], $post_id);

    update_field('negotiator', [
      'negotiator_phone' => $estate['negotiator']['phone'],
    ], $post_id);

    if ($estate['negotiator']['photo_url']) {
      $estate['negotiator']['url'] = $estate['negotiator']['photo_url'];
      update_field('negotiator', [
        'negotiator_photo' => WP_SweepBright_Helpers::insert_attachment_from_url($estate['negotiator'], $post_id),
      ], $post_id);
    }
  }
}
