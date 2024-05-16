<?php

/**
 * FieldMandateUpdate.
 *
 * @package FieldMandateUpdate
 */

class FieldMandateUpdate
{

  public function __construct()
  {
  }

  public static function update($estate, $post_id)
  {
    update_field('mandate', [
      'mandate_start_date' => $estate['mandate']['start_date'],
    ], $post_id);

    update_field('mandate', [
      'mandate_end_date' => $estate['mandate']['end_date'],
    ], $post_id);

    update_field('mandate', [
      'mandate_exclusive' => $estate['mandate']['exclusive'],
    ], $post_id);

    update_field('mandate', [
      'mandate_number' => $estate['mandate']['number'],
    ], $post_id);
  }
}
