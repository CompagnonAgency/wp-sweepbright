<?php

/**
 * WP_SweepBright_Controller_Estate.
 *
 * !IMPORTANT! This is for testing and debugging purposes only.
 * This is not linked or synced to SweepBright.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Estate
 */

class WP_SweepBright_Controller_Estate
{

	public function __construct()
	{
	}

	public function init($data)
	{
		// Get POST request arguments
		$estate_id = $data['estate_id'];

		// Output
		return rest_ensure_response([
			"id" => $estate_id,
			"is_project" => false,
			"project_id" => "staging-0000-0000-0000-0000003",
			"type" => "house",
			"sub_type" => "condo",
			"negotiation" => "sale",
			"rent_period" => "month",
			"status" => "available",
			"description" => [
				"en" => "",
				"fr" => "",
				"nl" => "In het West-Vlaamse Ruiselede, rustig gelegen halfweg Tielt en Aalter, realiseert IPON een nieuwe verkaveling nabij de Poekestraat. In een volgende fase worden op de loten 17, 18 en 19 drie woningen in landelijke stijl aangeboden."
			],
			"description_title" => [
				"en" => "",
				"fr" => "",
				"nl" => "Zonhoven"
			],
			"living_rooms" => 1,
			"kitchens" => 1,
			"bedrooms" => 1,
			"bathrooms" => 1,
			"toilets" => 1,
			"floors" => 1,
			"showrooms" => 1,
			"manufacturing_areas" => 1,
			"storage_rooms" => 1,
			"kitchen_condition" => "good",
			"bathroom_condition" => "good",
			"garden_orientation" => "N",
			"terrace_orientation" => "N",
			"video_url" => "http://my-video-url.be",
			"appointment_service_url" => "http://my-calendar-url.com",
			"general_condition" => "poor",
			"legal" => [
				"energy" => [
					"epc_value" => 200,
					"epc_category" => "A++",
					"epc_reference" => "20081101-0000000245-00000015",
					"total_epc_value" => 1000,
					"nabers" => [
						"description" => "Historical and current water and energy use",
						"type" => "number",
						"maximum" => 6,
						"minimum" => 0
					],
					"nathers" => [
						"description" => "All features of a building, including location",
						"type" => "number",
						"maximum" => 10,
						"minimum" => 0
					],
					"co2_emissions" => "",
					"e_level" => "E90",
					"report_electricity_gas" => "conform",
					"report_fuel_tank" => "conform"
				],
				"regulations" => [
					"building_permit" => true,
					"priority_purchase_right" => true,
					"subdivision_authorisation" => true,
					"urban_planning_breach" => true,
					"as_built_report" => true,
					"expropriation_plan" => true,
					"heritage_list" => true,
					"pending_legal_proceedings" => true,
					"registered_building" => true,
					"site_untapped_activity" => true,
					"urban_planning_certificate" => true
				],
				"legal_mentions" => [
					"en" => "Legal regulations English",
					"fr" => "Legal regulations French",
					"nl" => "Legal regulations Dutch"
				],
				"property_and_land" => [
					"purchased_year" => "1987",
					"cadastral_income" => 785,
					"flood_risk" => "no_flood_risk_area",
					"land_use_designation" => "residential"
				]
			],
			"auction" => [
				"start_date" => "2018-01-01T10:00:00+00:00"
			],
			"open_homes" => [
				[
					"start_date" => "2021-01-01T10:00:00+00:00",
					"end_date" => "2021-01-01T11:00:00+00:00"
				]
			],
			"price" => [
				"amount" => 980000,
				"currency" => "EUR",
				"hidden" => false
			],
			"price_negotiated" => 100000,
			"price_costs" => [
				"en" => "Free text field English",
				"fr" => "Free text field French",
				"nl" => "Free text field Dutch"
			],
			"price_taxes" => [
				"en" => "Free text field English",
				"fr" => "Free text field French",
				"nl" => "Free text field Dutch"
			],
			"custom_price" => "Price available on request",
			"location" => [
				"geo" => [
					"latitude" => 1.2345,
					"longitude" => 1.2345
				],
				"city" => "Ruiselede",
				"street" => "Street name",
				"street_2" => "Street name",
				"number" => "123",
				"box" => "7",
				"addition" => "ABD",
				"country" => "BE",
				"formatted" => "Street name, Brussels",
				"formatted_agency" => "Formatted Agency",
				"postal_code" => "1000",
				"hidden" => false
			],
			"amenities" => [
				"pool"
			],
			"sizes" => [
				"plot_area" => [
					"size" => 100,
					"unit" => "sq_ft"
				],
				"liveable_area" => [
					"size" => 100,
					"unit" => "sq_ft"
				]
			],
			"permissions" => [
				"farming" => true,
				"fishing" => false,
				"planning" => false,
				"construction" => true
			],
			"rooms" => [
				[
					"type" => "living_room",
					"size_description" => "Living room",
					"size" => 100,
					"unit" => "sq_ft",
					"ordinal" => 1
				]
			],
			"images" => [
				[
					"id" => "1234-5678-9012",
					"filename" => "my-image.jpeg",
					"description" => "My image",
					"url" => "https://www.s3a.be/wp-content/uploads/2014/03/S3A-nieuwbouw-woning-Schiplaken-01-850x550.jpg",
					"url_expires_on" => "2017-01-01T12:12:12+00:00",
					"ordinal" => 1
				],
				[
					"id" => "1234-5678-9012",
					"filename" => "my-image.jpeg",
					"description" => "My image",
					"url" => "https://cf.bstatic.com/images/hotel/max1280x900/217/217392260.jpg",
					"url_expires_on" => "2017-01-01T12:12:12+00:00",
					"ordinal" => 1
				],
			],
			"plans" => [
				[
					"id" => "1234-5678-9012",
					"filename" => "my-plan.pdf",
					"description" => "My plan",
					"url" => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
					"url_expires_on" => "2017-01-01T12:12:12+00:00"
				]
			],
			"documents" => [
				[
					"id" => "1234-5678-9012",
					"filename" => "my-plan.pdf",
					"description" => "My plan",
					"content_type" => "application/pdf",
					"url" => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
					"url_expires_on" => "2017-01-01T12:12:12+00:00"
				]
			],
			"features" => [
				"energy" => [
					"gas" => true,
					"fuel" => true,
					"electricity" => true,
					"heat_pump" => true
				],
				"comfort" => [
					"home_automation" => true,
					"water_softener" => true,
					"fireplace" => true,
					"walk_in_closet" => true,
					"home_cinema" => true,
					"wine_cellar" => true,
					"sauna" => true,
					"fitness_room" => true,
					"furnished" => true
				],
				"ecology" => [
					"double_glazing" => true,
					"solar_panels" => true,
					"solar_boiler" => true,
					"rainwater_harvesting" => true,
					"insulated_roof" => true
				],
				"security" => [
					"alarm" => true,
					"concierge" => true,
					"video_surveillance" => true
				],
				"heating_cooling" => [
					"central_heating" => true,
					"floor_heating" => true,
					"air_conditioning" => true
				]
			],
			"building" => [
				"renovation" => [
					"year" => 2012,
					"description" => "New windows"
				],
				"construction" => [
					"year" => 1970,
					"architect" => "Mr. Architect"
				]
			],
			"negotiator" => [
				"first_name" => "John",
				"last_name" => "Doe",
				"email" => "john@doe",
				"phone" => "+123456789",
				"photo_url" => "https://pbs.twimg.com/profile_images/984678118509109248/_lKShg6s.jpg",
				"photo_url_expires_on" => "2017-01-01T12:12:12+00:00"
			],
			"agency_commission" => [
				"fixed_fee" => 500.2,
				"percentage" => 5
			],
			"mandate" => [
				"start_date" => "2018-01-01",
				"end_date" => "2019-01-01",
				"exclusive" => true
			],
			"internal_note" => "Internal note",
			"occupancy" => [
				"occupied" => false,
				"available_from" => "2018-01-01",
				"contact_details" => "Available next week",
				"tenant_contract" => [
					"end_date" => "2018-12-31",
					"start_date" => "2018-01-01"
				]
			],
			"office" => [
				"id" => "2e8caade-eb1e-4499-994f-4aed28906826",
				"name" => "Team 1"
			],
			"buyers" => [
				[
					"first_name" => "John",
					"last_name" => "Doe",
					"email" => "john@doe.com",
					"phone" => "+123456789"
				]
			],
			"vendors" => [
				[
					"first_name" => "John",
					"last_name" => "Doe",
					"email" => "john@doe.com",
					"phone" => "+123456789"
				]
			],
			"settings" => [
				"reference" => "internal reference"
			],
		]);
	}
}
