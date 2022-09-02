<?php

/**
 * FieldPriceUpdate.
 *
 * @package FieldPriceUpdate
 */

class FieldPriceUpdate
{

  public function __construct()
  {
  }

  public static function update($estate, $post_id)
  {
    update_field('price', [
      'amount' => $estate['price']['amount'],
    ], $post_id);

    update_field('price', [
      'currency' => $estate['price']['currency'],
    ], $post_id);

    update_field('price', [
      'price_hidden' => $estate['price']['hidden'],
    ], $post_id);

    update_field('price', [
      'price_costs' => [
        'price_costs_en' => $estate['price_costs']['en'],
        'price_costs_fr' => $estate['price_costs']['fr'],
        'price_costs_nl' => $estate['price_costs']['nl'],
      ],
    ], $post_id);

    update_field('price', [
      'price_taxes' => [
        'price_taxes_en' => $estate['price_taxes']['en'],
        'price_taxes_fr' => $estate['price_taxes']['fr'],
        'price_taxes_nl' => $estate['price_taxes']['nl'],
      ],
    ], $post_id);

    update_field('price', [
      'price_vat_regime' => $estate['price_vat_regime'],
    ], $post_id);

    update_field('price', [
      'price_yearly_budgeted_building_costs' => [
        'amount' => $estate['price_yearly_budgeted_building_costs']['amount'],
        'currency' => $estate['price_yearly_budgeted_building_costs']['currency'],
      ],
    ], $post_id);

    update_field('price', [
      'price_property_taxes' => [
        'amount' => $estate['price_property_taxes']['amount'],
        'currency' => $estate['price_property_taxes']['currency'],
      ],
    ], $post_id);

    update_field('price', [
      'price_recurring_costs' => [
        'amount' => $estate['price_recurring_costs']['amount'],
        'currency' => $estate['price_recurring_costs']['currency'],
      ],
    ], $post_id);

    update_field('price', [
      'buyer_percentage' => $estate['buyer_percentage'],
    ], $post_id);

    update_field('price', [
      'buyer_fixed_fee' => $estate['buyer_fixed_fee'],
    ], $post_id);

    update_field('price', [
      'vendor_percentage' => $estate['vendor_percentage'],
    ], $post_id);

    update_field('price', [
      'vendor_fixed_fee' => $estate['vendor_fixed_fee'],
    ], $post_id);

    update_field('price', [
      'agency_commission' => [
        'fixed_fee' => $estate['agency_commission']['fixed_fee'],
        'percentage' => $estate['agency_commission']['percentage'],
      ],
    ], $post_id);

    update_field('price', [
      'custom_price' => $estate['custom_price'],
    ], $post_id);
  }
}
