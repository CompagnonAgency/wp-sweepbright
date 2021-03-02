<?php

/**
 * FieldVendorsUpdate.
 *
 * @package FieldVendorsUpdate
 */

class FieldVendorsUpdate
{

	public function __construct()
	{
	}

	public static function update($estate, $post_id)
	{
		if (isset($estate['vendors']) && is_countable($estate['vendors'])) {
			if (count($estate['vendors']) > 0) {
				$vendors = [];
				foreach ($estate['vendors'] as $key =>  $vendor) {
					$vendors[] = [
						'acf_fc_layout' => 'vendor_layout',
						'vendor_first_name' => $vendor['first_name'],
						'vendor_last_name' => $vendor['last_name'],
						'vendor_email' => $vendor['email'],
						'vendor_phone' => $vendor['phone'],
					];
				}
				update_field('vendors', [
					'vendor' => $vendors,
				], $post_id);
			}
		}
	}
}
