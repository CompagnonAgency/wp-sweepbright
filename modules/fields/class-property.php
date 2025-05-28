<?php

/**
 * FieldProperty.
 *
 * @package FieldProperty
 */

class FieldProperty
{

  public function __construct()
  {
  }

  public static function retrieve()
  {
    return [
      'key' => 'property_and_land',
      'name' => 'property_and_land',
      'label' => 'Property and land',
      'layout' => 'row',
      'type' => 'group',
      'sub_fields' => [
        [
          'key'    => 'purchased_year',
          'label'  => 'Purchased year',
          'name'   => 'purchased_year',
          'type'   => 'text',
        ],
        [
          'key'    => 'cadastral_income',
          'label'  => 'Cadastral income',
          'name'   => 'cadastral_income',
          'type'   => 'number',
        ],
        [
          'key' => 'flood_risk',
          'label' => 'Flood risk',
          'name' => 'flood_risk',
          'type' => 'select',
          'default_value' => '',
          'readonly' => 0,
          'disabled' => 0,
          'allow_null' => 1,
          'choices' => [
            'no_flood_risk_area' => 'No flood risk area',
            'potential_flood_sensitive_area' => 'Potential flood sensitive area',
            'effective_flood_sensitive_area' => 'Effective flood sensitive area',
          ],
        ],
	      [
		      'key' => 'flood_risk_building_score',
		      'label' => 'Flood risk building score',
		      'name' => 'flood_risk_building_score',
		      'type' => 'select',
		      'default_value' => '',
		      'readonly' => 0,
		      'disabled' => 0,
		      'allow_null' => 1,
		      'choices' => [
			      'A' => 'A',
			      'B' => 'B',
			      'C' => 'C',
			      'D' => 'D',
		      ],
	      ],
	      [
		      'key' => 'flood_risk_plot_score',
		      'label' => 'Flood risk plot score',
		      'name' => 'flood_risk_plot_score',
		      'type' => 'select',
		      'default_value' => '',
		      'readonly' => 0,
		      'disabled' => 0,
		      'allow_null' => 1,
		      'choices' => [
			      'A' => 'A',
			      'B' => 'B',
			      'C' => 'C',
			      'D' => 'D',
		      ],
	      ],
        [
          'key' => 'land_use_designation',
          'label' => 'Land use designation',
          'name' => 'land_use_designation',
          'type' => 'select',
          'default_value' => '',
          'readonly' => 0,
          'disabled' => 0,
          'allow_null' => 1,
          'choices' => [
            'residential' => 'Residential',
            'mixed_residential' => 'Mixed residential',
            'industrial' => 'Industrial',
            'recreational' => 'Recreational',
            'park' => 'Park',
            'agricultural_area_with_scenic_value' =>'Agricultural area with scenic value',
            'area_with_economical_activity' => 'Area with economical activity',
            'area_for_leisure_accomodation' => 'Area for leisure accommodation',
            'forest_area' => 'Forest area',
            'agricultural' => 'Agricultural',
            'nature_area' => 'Nature area',
            'natural_reserve' => 'Natural reserve',
            'residential_area_with_cultural_historical_value' => 'Residential area with cultural historical value',
            'residential_area_with_a_rural_character' => 'Residential area with a rural character',
            'residential_expansion_area' => 'Residential expansion area',
            'residential_park' => 'Residential park',
            'industrial_area_for_sme' => 'Industrial area for SME',
            'day_recreation_area' => 'Day recreation area',
            'other' => 'Other',
          ],
        ],
      ],
    ];
  }
}
