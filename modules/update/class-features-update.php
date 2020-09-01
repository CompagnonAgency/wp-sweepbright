<?php

/**
 * FieldFeaturesUpdate.
 *
 * @package FieldFeaturesUpdate
 */

class FieldFeaturesUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('features', [
      'rent_period' => $estate['rent_period'],
    ], $post_id);

		update_field('features', [
      'video_url' => $estate['video_url'],
    ], $post_id);

		update_field('features', [
      'appointment_service_url' => $estate['appointment_service_url'],
    ], $post_id);

		// Images
		$images = [];
		foreach ($estate['images'] as $key => $image) {
			$images[] = WP_SweepBright_Helpers::insert_attachment_from_url($image['url'], $post_id);
		}

		update_field('features', [
			'images' => $images,
		], $post_id);

		// Documents
		$documents = [];
	  foreach ($estate['documents'] as $key => $document) {
	    $documents[] = [
	      'acf_fc_layout' => 'document_layout',
	      'document_description' => $document['description'],
	      'document_content_type' => $document['content_type'],
	      'document_file' => WP_SweepBright_Helpers::insert_attachment_from_url($document['url'], $post_id),
	    ];
	  }
	  update_field('features', [
	    'documents' => $documents,
	  ], $post_id);

		// Plans
		$plans = [];
	  foreach ($estate['plans'] as $key => $plan) {
	    $plans[] = [
	      'acf_fc_layout' => 'plan_layout',
	      'plan_description' => $plan['description'],
	      'plan_file' => WP_SweepBright_Helpers::insert_attachment_from_url($plan['url'], $post_id),
	    ];
	  }
	  update_field('features', [
	    'plans' => $plans,
	  ], $post_id);

		update_field('features', [
      'basic_features_energy' => [
				'gas' => $estate['features']['energy']['gas']
			],
    ], $post_id);

		update_field('features', [
      'basic_features_energy' => [
				'fuel' => $estate['features']['energy']['fuel']
			],
    ], $post_id);

		update_field('features', [
      'basic_features_energy' => [
				'electricity' => $estate['features']['energy']['electricity']
			],
    ], $post_id);

		update_field('features', [
      'basic_features_energy' => [
				'heat_pump' => $estate['features']['energy']['heat_pump']
			],
    ], $post_id);

		update_field('features', [
      'negotiation' => $estate['negotiation']
    ], $post_id);

		update_field('features', [
      'estate_type' => $estate['type']
    ], $post_id);

		update_field('features', [
      'estate_sub_type' => $estate['sub_type']
    ], $post_id);
	}

}
