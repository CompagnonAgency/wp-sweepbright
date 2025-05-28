<?php

/**
 * FieldPropertyUpdate.
 *
 * @package FieldPropertyUpdate
 */

class FieldPropertyUpdate
{

  public function __construct()
  {
  }

  public static function update($estate, $post_id)
  {
    update_field('property_and_land', [
      'purchased_year' => $estate['legal']['property_and_land']['purchased_year'],
    ], $post_id);

    update_field('property_and_land', [
      'cadastral_income' => $estate['legal']['property_and_land']['cadastral_income'],
    ], $post_id);

    update_field('property_and_land', [
      'flood_risk' => $estate['legal']['property_and_land']['flood_risk'],
    ], $post_id);

	  update_field('property_and_land', [
		  'flood_risk_building_score' => $estate['legal']['property_and_land']['flood_risk_building_score'],
	  ], $post_id);

	  update_field('property_and_land', [
		  'flood_risk_plot_score' => $estate['legal']['property_and_land']['flood_risk_plot_score'],
	  ], $post_id);

    update_field('property_and_land', [
      'land_use_designation' => $estate['legal']['property_and_land']['land_use_designation'],
    ], $post_id);
  }
}
