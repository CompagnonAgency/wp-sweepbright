<?php

/**
 * FieldRegulationsUpdate.
 *
 * @package FieldRegulationsUpdate
 */

class FieldRegulationsUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('regulations', [
      'building_permit' => $estate['legal']['regulations']['building_permit'],
    ], $post_id);

    update_field('regulations', [
      'priority_purchase_right' => $estate['legal']['regulations']['priority_purchase_right'],
    ], $post_id);

    update_field('regulations', [
      'subdivision_authorisation' => $estate['legal']['regulations']['subdivision_authorisation'],
    ], $post_id);

    update_field('regulations', [
      'urban_planning_breach' => $estate['legal']['regulations']['urban_planning_breach'],
    ], $post_id);

    update_field('regulations', [
      'as_built_report' => $estate['legal']['regulations']['as_built_report'],
    ], $post_id);

    update_field('regulations', [
      'expropriation_plan' => $estate['legal']['regulations']['expropriation_plan'],
    ], $post_id);

    update_field('regulations', [
      'heritage_list' => $estate['legal']['regulations']['heritage_list'],
    ], $post_id);

    update_field('regulations', [
      'pending_legal_proceedings' => $estate['legal']['regulations']['pending_legal_proceedings'],
    ], $post_id);

    update_field('regulations', [
      'registered_building' => $estate['legal']['regulations']['registered_building'],
    ], $post_id);

    update_field('regulations', [
      'site_untapped_activity' => $estate['legal']['regulations']['site_untapped_activity'],
    ], $post_id);

    update_field('regulations', [
      'urban_planning_certificate' => $estate['legal']['regulations']['urban_planning_certificate'],
    ], $post_id);
	}

}
