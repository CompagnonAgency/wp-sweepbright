<?php

/**
 * FieldRegulationsUpdate.
 *
 * @package FieldRegulationsUpdate
 */

class FieldRegulationsUpdate
{

  public function __construct()
  {
  }

  public static function update($estate, $post_id)
  {
    // building_permit
    if (isset($estate['legal']['regulations']['building_permit'])) {
      $val = 0;
      if ($estate['legal']['regulations']['building_permit']) {
        $val = 1;
      }
      update_field('regulations', [
        'building_permit' => $val,
      ], $post_id);
    }

    // priority_purchase_right
    if (isset($estate['legal']['regulations']['priority_purchase_right'])) {
      $val = 0;
      if ($estate['legal']['regulations']['priority_purchase_right']) {
        $val = 1;
      }
      update_field('regulations', [
        'priority_purchase_right' => $val,
      ], $post_id);
    }

    // subdivision_authorisation
    if (isset($estate['legal']['regulations']['subdivision_authorisation'])) {
      $val = 0;
      if ($estate['legal']['regulations']['subdivision_authorisation']) {
        $val = 1;
      }
      update_field('regulations', [
        'subdivision_authorisation' => $val,
      ], $post_id);
    }

    // urban_planning_breach
    if (isset($estate['legal']['regulations']['urban_planning_breach'])) {
      $val = 0;
      if ($estate['legal']['regulations']['urban_planning_breach']) {
        $val = 1;
      }
      update_field('regulations', [
        'urban_planning_breach' => $val,
      ], $post_id);
    }

    // as_built_report
    if (isset($estate['legal']['regulations']['as_built_report'])) {
      $val = 0;
      if ($estate['legal']['regulations']['as_built_report']) {
        $val = 1;
      }
      update_field('regulations', [
        'as_built_report' => $val,
      ], $post_id);
    }

    // expropriation_plan
    if (isset($estate['legal']['regulations']['expropriation_plan'])) {
      $val = 0;
      if ($estate['legal']['regulations']['expropriation_plan']) {
        $val = 1;
      }
      update_field('regulations', [
        'expropriation_plan' => $val,
      ], $post_id);
    }

    // heritage_list
    if (isset($estate['legal']['regulations']['heritage_list'])) {
      $val = 0;
      if ($estate['legal']['regulations']['heritage_list']) {
        $val = 1;
      }
      update_field('regulations', [
        'heritage_list' => $val,
      ], $post_id);
    }

    // pending_legal_proceedings
    if (isset($estate['legal']['regulations']['pending_legal_proceedings'])) {
      $val = 0;
      if ($estate['legal']['regulations']['pending_legal_proceedings']) {
        $val = 1;
      }
      update_field('regulations', [
        'pending_legal_proceedings' => $val,
      ], $post_id);
    }

    // registered_building
    if (isset($estate['legal']['regulations']['registered_building'])) {
      $val = 0;
      if ($estate['legal']['regulations']['registered_building']) {
        $val = 1;
      }
      update_field('regulations', [
        'registered_building' => $val,
      ], $post_id);
    }

    // site_untapped_activity
    if (isset($estate['legal']['regulations']['site_untapped_activity'])) {
      $val = 0;
      if ($estate['legal']['regulations']['site_untapped_activity']) {
        $val = 1;
      }
      update_field('regulations', [
        'site_untapped_activity' => $val,
      ], $post_id);
    }

    // urban_planning_certificate
    if (isset($estate['legal']['regulations']['urban_planning_certificate'])) {
      $val = 0;
      if ($estate['legal']['regulations']['urban_planning_certificate']) {
        $val = 1;
      }
      update_field('regulations', [
        'urban_planning_certificate' => $val,
      ], $post_id);
    }
  }
}
