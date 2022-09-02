<?php

/**
 * FieldEnergy.
 *
 * @package FieldEnergy
 */

class FieldEnergy
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
		return [
			'key' => 'energy_details',
			'name' => 'energy_details',
			'label' => 'Energy details',
			'layout' => 'row',
			'type' => 'group',
			'sub_fields' => [
				[
					'key' => 'epc_value',
					'label' => 'EPC value',
					'name' => 'epc_value',
					'type' => 'number',
				],
				[
					'key' => 'epc_category',
					'label' => 'EPC category',
					'name' => 'epc_category',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'A++' => 'A++',
						'A+' => 'A+',
						'A' => 'A',
						'B' => 'B',
						'C' => 'C',
						'D' => 'D',
						'E' => 'E',
						'F' => 'F',
						'G' => 'G',
					],
				],
				[
					'key' => 'epc_reference',
					'label' => 'EPC reference',
					'name' => 'epc_reference',
					'type' => 'text',
				],
				[
					'key' => 'total_epc_value',
					'label' => 'Total EPC value',
					'name' => 'total_epc_value',
					'type' => 'number',
				],
				[
					'key' => 'energy_dpe',
					'label' => 'DPE category',
					'name' => 'energy_dpe',
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
						'E' => 'E',
						'F' => 'F',
						'G' => 'G',
					],
				],
				[
					'key' => 'greenhouse_emissions',
					'label' => 'Greenhouse emissions',
					'name' => 'greenhouse_emissions',
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
						'E' => 'E',
						'F' => 'F',
						'G' => 'G',
					],
				],
				[
					'key' => 'co2_emissions',
					'label' => 'CO2 emissions',
					'name' => 'co2_emissions',
					'type' => 'text',
				],
				[
					'key'           => 'nabers',
					'name'          => 'nabers',
					'label'         => 'Nabers',
					'display'       => 'block',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'nabers_description',
							'label'  => 'Description',
							'name'   => 'description',
							'type'   => 'text',
						],
						[
							'key'    => 'nabers_type',
							'label'  => 'Type',
							'name'   => 'type',
							'type'   => 'text',
						],
						[
							'key'    => 'nabers_maximum',
							'label'  => 'Maximum',
							'name'   => 'maximum',
							'type'   => 'number',
						],
						[
							'key'    => 'nabers_minimum',
							'label'  => 'Minimum',
							'name'   => 'minimum',
							'type'   => 'number',
						],
					]
				],
				[
					'key'           => 'nathers',
					'name'          => 'nathers',
					'label'         => 'Nathers',
					'display'       => 'block',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'nathers_description',
							'label'  => 'Description',
							'name'   => 'description',
							'type'   => 'text',
						],
						[
							'key'    => 'nathers_type',
							'label'  => 'Type',
							'name'   => 'type',
							'type'   => 'text',
						],
						[
							'key'    => 'nathers_maximum',
							'label'  => 'Maximum',
							'name'   => 'maximum',
							'type'   => 'number',
						],
						[
							'key'    => 'nathers_minimum',
							'label'  => 'Minimum',
							'name'   => 'minimum',
							'type'   => 'number',
						],
					]
				],
				[
					'key' => 'e_level',
					'label' => 'E level',
					'name' => 'e_level',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'E90' => 'E90',
						'E80' => 'E80',
						'E70' => 'E70',
						'E60' => 'E60',
						'E50' => 'E50',
						'E40' => 'E40',
						'E35' => 'E35',
						'E30' => 'E30',
					],
				],
				[
					'key' => 'report_electricity_gas',
					'label' => 'Report electricity gas',
					'name' => 'report_electricity_gas',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'conform' => 'Conform',
						'not_conform' => 'Not conform',
						'no_report' => 'No report',
						'not_applicable' => 'Not applicable',
					],
				],
				[
					'key' => 'report_fuel_tank',
					'label' => 'Report fuel tank',
					'name' => 'report_fuel_tank',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'conform' => 'Conform',
						'not_conform' => 'Not conform',
						'no_report' => 'No report',
						'not_applicable' => 'Not applicable',
					],
				],
			],
		];
	}
}
