<?php

/**
 * FieldEnergyUpdate.
 *
 * @package FieldEnergyUpdate
 */

class FieldEnergyUpdate
{

	public function __construct()
	{
	}

	public static function update($estate, $post_id)
	{
		update_field('energy_details', [
			'epc_value' => $estate['legal']['energy']['epc_value'],
		], $post_id);

		update_field('energy_details', [
			'epc_category' => $estate['legal']['energy']['epc_category'],
		], $post_id);

		update_field('energy_details', [
			'epc_reference' => $estate['legal']['energy']['epc_reference'],
		], $post_id);

		update_field('energy_details', [
			'total_epc_value' => $estate['legal']['energy']['total_epc_value'],
		], $post_id);

		update_field('energy_details', [
			'energy_dpe' => $estate['legal']['energy']['dpe'],
		], $post_id);

		update_field('energy_details', [
			'greenhouse_emissions' => $estate['legal']['energy']['greenhouse_emissions'],
		], $post_id);

		update_field('energy_details', [
			'co2_emissions' => $estate['legal']['energy']['co2_emissions'],
		], $post_id);

		update_field('energy_details', [
			'nabers' => [
				'nabers_description' => $estate['legal']['energy']['nabers']['description'],
			],
		], $post_id);

		update_field('energy_details', [
			'nabers' => [
				'nabers_type' => $estate['legal']['energy']['nabers']['type'],
			],
		], $post_id);

		update_field('energy_details', [
			'nabers' => [
				'nabers_maximum' => $estate['legal']['energy']['nabers']['maximum'],
			],
		], $post_id);

		update_field('energy_details', [
			'nabers' => [
				'nabers_minimum' => $estate['legal']['energy']['nabers']['minimum'],
			],
		], $post_id);

		update_field('energy_details', [
			'nathers' => [
				'nathers_description' => $estate['legal']['energy']['nathers']['description'],
			],
		], $post_id);

		update_field('energy_details', [
			'nathers' => [
				'nathers_type' => $estate['legal']['energy']['nathers']['type'],
			],
		], $post_id);

		update_field('energy_details', [
			'nathers' => [
				'nathers_maximum' => $estate['legal']['energy']['nathers']['maximum'],
			],
		], $post_id);

		update_field('energy_details', [
			'nathers' => [
				'nathers_minimum' => $estate['legal']['energy']['nathers']['minimum'],
			],
		], $post_id);

		update_field('energy_details', [
			'e_level' => $estate['legal']['energy']['e_level'],
		], $post_id);

		update_field('energy_details', [
			'report_electricity_gas' => $estate['legal']['energy']['report_electricity_gas'],
		], $post_id);

		update_field('energy_details', [
			'report_fuel_tank' => $estate['legal']['energy']['report_fuel_tank'],
		], $post_id);
	}
}
