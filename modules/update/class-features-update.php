<?php

/**
 * FieldFeaturesUpdate.
 *
 * @package FieldFeaturesUpdate
 */

class FieldFeaturesUpdate
{

	public function __construct()
	{
	}

	public static function update($estate, $post_id)
	{
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
		if (isset($estate['images']) && is_countable($estate['images'])) {
			if (count($estate['images'])  > 0) {
				$images = [];
				foreach ($estate['images'] as $key => $image) {
					$images[] = WP_SweepBright_Helpers::insert_attachment_from_url($image, $post_id);
				}
				update_field('features', [
					'images' => $images,
				], $post_id);
			}
		}

		// Documents
		if (isset($estate['documents']) && is_countable($estate['documents'])) {
			if (count($estate['documents']) > 0) {
				$documents = [];
				foreach ($estate['documents'] as $key => $document) {
					if (!$document['description']) {
						$description = $document['filename'];
					} else {
						$description = $document['description'];
					}
					$documents[] = [
						'acf_fc_layout' => 'document_layout',
						'document_description' => $description,
						'document_content_type' => $document['content_type'],
						'document_file' => WP_SweepBright_Helpers::insert_attachment_from_url($document, $post_id),
					];
				}
				update_field('features', [
					'documents' => $documents,
				], $post_id);
			}
		}

		// Plans
		if (isset($estate['plans']) && is_countable($estate['plans'])) {
			if (count($estate['plans']) > 0) {
				$plans = [];
				foreach ($estate['plans'] as $key => $plan) {
					if (!$plan['description']) {
						$description = $plan['filename'];
					} else {
						$description = $plan['description'];
					}
					$plans[] = [
						'acf_fc_layout' => 'plan_layout',
						'plan_description' => $description,
						'plan_file' => WP_SweepBright_Helpers::insert_attachment_from_url($plan, $post_id),
					];
				}
				update_field('features', [
					'plans' => $plans,
				], $post_id);
			}
		}

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
			'type' => $estate['type']
		], $post_id);

		update_field('features', [
			'subtype' => $estate['sub_type']
		], $post_id);
	}
}
