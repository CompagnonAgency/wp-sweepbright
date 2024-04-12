<?php

/**
 * FieldPrice.
 *
 * @package FieldPrice
 */

class FieldPrice
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
		return [
			'key' => 'price',
			'name' => 'price',
			'label' => 'Price',
			'layout' => 'row',
			'type' => 'group',
			'sub_fields' => [
				[
					'key'    => 'amount',
					'label'  => 'Amount',
					'name'   => 'amount',
					'type'   => 'number',
				],
				[
					'key'    => 'currency',
					'label'  => 'Currency',
					'name'   => 'currency',
					'type'   => 'text',
				],
				[
					'key'    => 'price_hidden',
					'label'  => 'Hidden',
					'name'   => 'hidden',
					'type'   => 'true_false',
					'ui' => true,
				],
				[
					'key'    => 'price_costs',
					'label'  => 'Price costs',
					'name'   => 'price_costs',
					'type' => 'group',
					'sub_fields' => [
						[
							'key'    => 'price_costs_en',
							'label'  => 'English',
							'name'   => 'en',
							'type'   => 'text',
						],
						[
							'key'    => 'price_costs_fr',
							'label'  => 'French',
							'name'   => 'fr',
							'type'   => 'text',
						],
						[
							'key'    => 'price_costs_nl',
							'label'  => 'Dutch',
							'name'   => 'nl',
							'type'   => 'text',
						]
					],
				],
				[
					'key' => 'price_taxes',
					'label' => 'Price taxes',
					'name' => 'price_taxes',
					'type' => 'group',
					'layout' => 'row',
					'sub_fields' => [
						[
							'key'    => 'price_taxes_en',
							'label'  => 'English',
							'name'   => 'en',
							'type'   => 'text',
						],
						[
							'key'    => 'price_taxes_fr',
							'label'  => 'French',
							'name'   => 'fr',
							'type'   => 'text',
						],
						[
							'key'    => 'price_taxes_nl',
							'label'  => 'Dutch',
							'name'   => 'nl',
							'type'   => 'text',
						]
					],
				],
				[
					'key'    => 'price_vat_regime',
					'label'  => 'Price VAT regime',
					'name'   => 'price_vat_regime',
					'type'   => 'number',
				],
				[
					'key'    => 'price_yearly_budgeted_building_costs',
					'label'  => 'Price yearly budgeted building costs',
					'name'   => 'price_yearly_budgeted_building_costs',
					'type' => 'group',
					'sub_fields' => [
						[
							'key'    => 'amount',
							'label'  => 'Amount',
							'name'   => 'amount',
							'type'   => 'number',
						],
						[
							'key'    => 'currency',
							'label'  => 'Currency',
							'name'   => 'currency',
							'type'   => 'text',
						],
					],
				],
				[
					'key'    => 'price_property_taxes',
					'label'  => 'Price property taxes',
					'name'   => 'price_property_taxes',
					'type' => 'group',
					'sub_fields' => [
						[
							'key'    => 'amount',
							'label'  => 'Amount',
							'name'   => 'amount',
							'type'   => 'number',
						],
						[
							'key'    => 'currency',
							'label'  => 'Currency',
							'name'   => 'currency',
							'type'   => 'text',
						],
					],
				],
				[
					'key'    => 'price_recurring_costs',
					'label'  => 'Price recurring costs',
					'name'   => 'price_recurring_costs',
					'type' => 'group',
					'sub_fields' => [
						[
							'key'    => 'amount',
							'label'  => 'Amount',
							'name'   => 'amount',
							'type'   => 'number',
						],
						[
							'key'    => 'currency',
							'label'  => 'Currency',
							'name'   => 'currency',
							'type'   => 'text',
						],
					],
				],
				[
					'key'    => 'buyer_percentage',
					'label'  => 'Buyer percentage',
					'name'   => 'buyer_percentage',
					'type'   => 'number',
				],
				[
					'key'    => 'buyer_fixed_fee',
					'label'  => 'Buyer fixed fee',
					'name'   => 'buyer_fixed_fee',
					'type'   => 'number',
				],
				[
					'key'    => 'vendor_percentage',
					'label'  => 'Vendor percentage',
					'name'   => 'vendor_percentage',
					'type'   => 'number',
				],
				[
					'key'    => 'vendor_fixed_fee',
					'label'  => 'Vendor fixed fee',
					'name'   => 'vendor_fixed_fee',
					'type'   => 'number',
				],
				[
					'key'    => 'agency_commission',
					'label'  => 'Agency commission',
					'name'   => 'agency_commission',
					'type' => 'group',
					'sub_fields' => [
						[
							'key'    => 'fixed_fee',
							'label'  => 'Fixed fee',
							'name'   => 'fixed_fee',
							'type'   => 'number',
						],
						[
							'key'    => 'percentage',
							'label'  => 'Percentage',
							'name'   => 'percentage',
							'type'   => 'number',
						],
					],
				],
				[
					'key'    => 'custom_price',
					'label'  => 'Custom price',
					'name'   => 'custom_price',
					'type'   => 'text',
				],
				[
					'key'    => 'price_inventory_report_cost',
					'label'  => 'Price inventory report cost',
					'name'   => 'price_inventory_report_cost',
					'type' => 'group',
					'sub_fields' => [
						[
							'key'    => 'amount',
							'label'  => 'Amount',
							'name'   => 'amount',
							'type'   => 'number',
						],
						[
							'key'    => 'currency',
							'label'  => 'Currency',
							'name'   => 'currency',
							'type'   => 'text',
						],
					],
				],
				[
					'key'    => 'price_guarantee',
					'label'  => 'Price guarantee',
					'name'   => 'price_guarantee',
					'type' => 'group',
					'sub_fields' => [
						[
							'key'    => 'amount',
							'label'  => 'Amount',
							'name'   => 'amount',
							'type'   => 'number',
						],
						[
							'key'    => 'currency',
							'label'  => 'Currency',
							'name'   => 'currency',
							'type'   => 'text',
						],
					],
				],
			],
		];
	}
}
