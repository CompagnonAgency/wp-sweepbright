<?php

/**
 * FieldRegulations.
 *
 * @package FieldRegulations
 */

class FieldRegulations {

	public function __construct() {
	}

	public static function retrieve() {
    return [
      'key' => 'regulations',
      'name' => 'regulations',
      'label' => 'Regulations',
      'layout' => 'row',
      'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'building_permit',
					'label'  => 'Building permit',
					'name'   => 'building_permit',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'priority_purchase_right',
					'label'  => 'Priority purchase right',
					'name'   => 'priority_purchase_right',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'subdivision_authorisation',
					'label'  => 'Subdivision authorisation',
					'name'   => 'subdivision_authorisation',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'urban_planning_breach',
					'label'  => 'Urban planning breach',
					'name'   => 'urban_planning_breach',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'as_built_report',
					'label'  => 'As built report',
					'name'   => 'as_built_report',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'expropriation_plan',
					'label'  => 'Expropriation plan',
					'name'   => 'expropriation_plan',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'heritage_list',
					'label'  => 'Heritage list',
					'name'   => 'heritage_list',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'pending_legal_proceedings',
					'label'  => 'Pending legal proceedings',
					'name'   => 'pending_legal_proceedings',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'registered_building',
					'label'  => 'Registered building',
					'name'   => 'registered_building',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'site_untapped_activity',
					'label'  => 'Site untapped activity',
					'name'   => 'site_untapped_activity',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'urban_planning_certificate',
					'label'  => 'Urban planning certificate',
					'name'   => 'urban_planning_certificate',
					'type'   => 'true_false',
					'ui' => true,
				],
			],
    ];
	}

}
