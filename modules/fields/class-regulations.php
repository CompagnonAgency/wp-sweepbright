<?php

/**
 * FieldRegulations.
 *
 * @package FieldRegulations
 */

class FieldRegulations
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
		return [
			'key' => 'regulations',
			'name' => 'regulations',
			'label' => 'Regulations',
			'layout' => 'row',
			'type' => 'group',
			'sub_fields' => [
				[
					'key' => 'building_permit',
					'label' => 'Building permit',
					'name' => 'building_permit',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key' => 'priority_purchase_right',
					'label'  => 'Priority purchase right',
					'name' => 'priority_purchase_right',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'subdivision_authorisation',
					'label'  => 'Subdivision authorisation',
					'name'   => 'subdivision_authorisation',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'urban_planning_breach',
					'label'  => 'Urban planning breach',
					'name'   => 'urban_planning_breach',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'as_built_report',
					'label'  => 'As built report',
					'name'   => 'as_built_report',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'expropriation_plan',
					'label'  => 'Expropriation plan',
					'name'   => 'expropriation_plan',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'heritage_list',
					'label'  => 'Heritage list',
					'name'   => 'heritage_list',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'pending_legal_proceedings',
					'label'  => 'Pending legal proceedings',
					'name'   => 'pending_legal_proceedings',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'registered_building',
					'label'  => 'Registered building',
					'name'   => 'registered_building',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'site_untapped_activity',
					'label'  => 'Site untapped activity',
					'name'   => 'site_untapped_activity',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
				[
					'key'    => 'urban_planning_certificate',
					'label'  => 'Urban planning certificate',
					'name'   => 'urban_planning_certificate',
					'type' => 'select',
					'default_value' => null,
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						1 => 'Yes',
						0 => 'No',
					],
				],
			],
		];
	}
}
