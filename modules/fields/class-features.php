<?php

/**
 * FieldFeatures.
 *
 * @package FieldFeatures
 */

class FieldFeatures
{

	public function __construct()
	{
	}

	public static function retrieve()
	{
		return [
			'key' => 'features',
			'name' => 'features',
			'label' => 'Features',
			'layout' => 'row',
			'type' => 'group',
			'sub_fields' => [
				[
					'key' => 'rent_period',
					'label' => 'Rent period',
					'name' => 'rent_period',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'week' => 'Week',
						'month' => 'Month',
					],
				],
				[
					'key'    => 'video_url',
					'label'  => 'Video URL',
					'name'   => 'video_url',
					'type'   => 'text',
				],
				[
					'key'    => 'virtual_tour_url',
					'label'  => 'Virtual tour URL',
					'name'   => 'virtual_tour_url',
					'type'   => 'text',
				],
				[
					'key'    => 'appointment_service_url',
					'label'  => 'Appointment service url',
					'name'   => 'appointment_service_url',
					'type'   => 'text',
				],
				[
					'key' => 'images',
					'label' => 'Images',
					'name' => 'images',
					'type' => 'gallery',
					'preview_size' => 'thumbnail',
					'library' => 'uploadedTo',
				],
				[
					'key'    => 'documents',
					'label'  => 'Documents',
					'name'   => 'documents',
					'type'   => 'flexible_content',
					'button_label' => 'Add document',
					'layouts' => [
						[
							'key'           => 'document_layout',
							'name'          => 'document_layout',
							'label'         => 'Document',
							'display'       => 'block',
							'sub_fields'    => [
								[
									'key'    => 'document_content_type',
									'label'  => 'Content type',
									'name'   => 'content_type',
									'type'   => 'text',
								],
								[
									'key'    => 'document_description',
									'label'  => 'Description',
									'name'   => 'description',
									'type'   => 'text',
								],
								[
									'key'    => 'document_file',
									'label'  => 'File',
									'name'   => 'file',
									'type'   => 'file',
									'return_format' => 'array',
									'preview_size' => 'thumbnail',
									'library' => 'uploadedTo',
								]
							],
						],
					]
				],
				[
					'key'    => 'plans',
					'label'  => 'Plans',
					'name'   => 'plans',
					'type'   => 'flexible_content',
					'button_label' => 'Add plan',
					'layouts' => [
						[
							'key'           => 'plan_layout',
							'name'          => 'plan_layout',
							'label'         => 'Plan',
							'display'       => 'block',
							'sub_fields'    => [
								[
									'key'    => 'plan_description',
									'label'  => 'Description',
									'name'   => 'description',
									'type'   => 'text',
								],
								[
									'key'    => 'plan_file',
									'label'  => 'File',
									'name'   => 'file',
									'type'   => 'file',
									'return_format' => 'array',
									'preview_size' => 'thumbnail',
									'library' => 'uploadedTo',
								]
							],
						],
					]
				],
				[
					'key'           => 'basic_features_energy',
					'name'          => 'energy',
					'label'         => 'Energy',
					'layout'       => 'table',
					'type'          => 'group',
					'sub_fields'    => [
						[
							'key'    => 'gas',
							'label'  => 'Gas',
							'name'   => 'gas',
							'type'   => 'true_false',
							'ui' => true,
						],
						[
							'key'    => 'fuel',
							'label'  => 'Fuel',
							'name'   => 'fuel',
							'type'   => 'true_false',
							'ui' => true,
						],
						[
							'key'    => 'electricity',
							'label'  => 'Electricity',
							'name'   => 'electricity',
							'type'   => 'true_false',
							'ui' => true,
						],
						[
							'key'    => 'heat_pump',
							'label'  => 'Heat pump',
							'name'   => 'heat_pump',
							'type'   => 'true_false',
							'ui' => true,
						],
					]
				],
				[
					'key' => 'negotiation',
					'label' => 'Negotiation',
					'name' => 'negotiation',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'let' => 'For rent',
						'sale' => 'For sale',
					],
				],
				[
					'key' => 'type',
					'label' => 'Type',
					'name' => 'estateType',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'apartment' => 'Apartment',
						'commercial' => 'Commercial',
						'house' => 'House',
						'land' => 'Land',
						'office' => 'Office',
						'parking' => 'Parking',
					],
				],
				[
					'key' => 'subtype',
					'label' => 'Sub type',
					'name' => 'sub_type',
					'type' => 'select',
					'default_value' => '',
					'readonly' => 0,
					'disabled' => 0,
					'allow_null' => 1,
					'choices' => [
						'semi_detached' => 'Semi-detached',
						'detached' => 'Detached',
						'terraced' => 'Terraced',
						'bungalow' => 'Bungalow',
						'condo' => 'Condo',
						'loft' => 'Loft',
						'duplex' => 'Duplex',
						'penthouse' => 'Penthouse',
						'student_accommodation' => 'Student accomodation',
						'healthcare' => 'Healthcare',
						'industrial' => 'Industrial',
						'leasure_and_sports' => 'Leasure and sports',
						'restaurant_and_café' => 'Restaurant and café',
						'retail' => 'Retail',
						'shop' => 'Shop',
						'warehouse' => 'Warehouse',
						'townhouse' => 'Townhouse',
						'cottage' => 'Cottage',
						'mansion' => 'Mansion',
						'farm' => 'Farm',
						'investment_property' => 'Investment property',
						'agricultural' => 'Agricultural',
						'buildable' => 'Buildable',
						'recreational' => 'Recreational',
						'pasture_land' => 'Pasture land',
						'coworking' => 'Coworking',
						'flex_office' => 'Flex office',
						'open_office' => 'Open office',
						'investment_property' => 'Investment property',
						'private_garage' => 'Private garage',
						'indoor_parking_space' => 'Indoor parking space',
						'outdoor_parking_space' => 'Outdoor parking space',
						'covered_outdoor_space' => 'Covered outdoor space',
					],
				],
			],
		];
	}
}
