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

	public function create_property($estate_id, $is_unit, $index, $project_type)
	{
		$faker = Faker\Factory::create('en_US');
		$faker->addProvider(new Faker\Provider\en_US\Address($faker));
		$faker->addProvider(new Faker\Provider\en_US\Person($faker));
		$faker->addProvider(new Faker\Provider\en_US\Text($faker));
		$faker->addProvider(new Faker\Provider\en_US\PhoneNumber($faker));
		$faker->addProvider(new Faker\Provider\en_US\Company($faker));

		// Property type
		$property_type = $faker->randomElement(['default', 'default', 'default', 'project']);

		// Project
		$is_project = false;
		if ($property_type === 'project' && !$is_unit) {
			$is_project = true;
		}

		// Unit
		$project_id = false;
		if ($is_unit) {
			$project_id = $estate_id;
		}

		// Estate ID
		if ($is_unit) {
			$estate_id = $faker->bothify('esate-??????????-##########');
		}

		if ($is_unit || $is_project) {
			// Type
			if ($is_unit) {
				$type = $project_type;
			} else {
				$type = $faker->randomElement(['apartment', 'house']);
			}

			// Negotiation
			$negotiation = 'sale';
		} else {
			// Type
			$type = $faker->randomElement(['apartment', 'house', 'land']);

			// Negotiation
			$negotiation = $faker->randomElement(['let', 'sale']);
		}

		// Status
		if ($negotiation === 'let') {
			$status = $faker->randomElement(['available', 'available', 'rented']);
		} else {
			if ($is_project) {
				$status = $faker->randomElement(['available', 'available', 'available', 'sold']);
			} else {
				$status = $faker->randomElement(['available', 'available', 'under_contract', 'option', 'sold']);
			}
		}

		// Rooms
		$rooms = [];

		// Documents
		$documents = [];

		$has_documents = $faker->boolean();
		if ($has_documents) {
			$documents = [
				[
					"id" => "1234-5678-9012",
					"filename" => "building-permit.pdf",
					"description" => "Building permit",
					"content_type" => "application/pdf",
					"url" => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
					"url_expires_on" => "2017-01-01T12:12:12+00:00"
				],
				[
					"id" => "1234-5678-9012",
					"filename" => "city-permit.pdf",
					"description" => "City permit",
					"content_type" => "application/pdf",
					"url" => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
					"url_expires_on" => "2017-01-01T12:12:12+00:00"
				]
			];
		}

		// Plans
		$plans = [];

		$has_plans = $faker->boolean();
		if ($has_plans) {
			$plans = [
				[
					"id" => "1234-5678-9012",
					"filename" => "architect-plans.pdf",
					"description" => "Architect plans",
					"content_type" => "application/pdf",
					"url" => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
					"url_expires_on" => "2017-01-01T12:12:12+00:00"
				],
				[
					"id" => "1234-5678-9012",
					"filename" => "city-plans.pdf",
					"description" => "City plans",
					"content_type" => "application/pdf",
					"url" => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
					"url_expires_on" => "2017-01-01T12:12:12+00:00"
				]
			];
		}

		// Subtype
		switch ($type) {
			case 'apartment':
				$subtype = $faker->randomElement(['condo', 'duplex', 'loft']);
				$amenities = $faker->randomElements(['pool', 'basement', 'garden', 'parking', 'attic']);

				// Rooms
				$has_rooms = $faker->boolean();
				if ($has_rooms) {
					$rooms = [
						[
							"type" => "living_room",
							"size_description" => "Living room",
							"size" => $faker->numberBetween(10, 30),
							"unit" => "sq_m",
							"ordinal" => 1
						],
						[
							"type" => "bathrooms",
							"size_description" => "Bathroom",
							"size" => $faker->numberBetween(10, 30),
							"unit" => "sq_m",
							"ordinal" => 1
						],
						[
							"type" => "terrace",
							"size_description" => "Terrace",
							"size" => $faker->numberBetween(10, 30),
							"unit" => "sq_m",
							"ordinal" => 1
						]
					];
				}

				// Negotiation
				switch ($negotiation) {
					case 'sale':
						$title = [
							'nl' => $faker->randomElement(['Appartement te koop met vlotte verbinding', 'Rustig gelegen appartement te koop', 'Gerenoveerd apartement te koop']),
							'en' => $faker->randomElement(['Apartment for sale with good connections', 'Quietly located apartment for sale', 'Renovated apartment for sale']),
							'fr' => $faker->randomElement(['Appartement à vendre avec de bonnes connexions', 'Appartement situé au calme à vendre', 'Appartement rénové à vendre']),
						];
						$price = $faker->randomElement([150000, 189000, 225000, 350000]);
						break;
					case 'let':
						$title = [
							'nl' => $faker->randomElement(['Gezellig appartement te huur', 'Ideaal appartement voor starters te huur', 'Luxe apartement te huur']),
							'en' => $faker->randomElement(['Cozy apartment for rent', 'Ideal apartment for starters for rent', 'Luxury apartment for rent']),
							'fr' => $faker->randomElement(['Appartement confortable à louer', 'Appartement idéal pour les débutants à louer', 'Appartement de luxe à louer']),
						];
						$price = $faker->randomElement([650, 700, 860, 1200]);
						break;
				}

				// Images
				$images = [
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/439391/pexels-photo-439391.jpeg', 'https://images.pexels.com/photos/3277921/pexels-photo-3277921.jpeg', 'https://images.pexels.com/photos/3597087/pexels-photo-3597087.jpeg', 'https://images.pexels.com/photos/2119714/pexels-photo-2119714.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/2089698/pexels-photo-2089698.jpeg', 'https://images.pexels.com/photos/3214064/pexels-photo-3214064.jpeg', 'https://images.pexels.com/photos/3926542/pexels-photo-3926542.jpeg', 'https://images.pexels.com/photos/1599791/pexels-photo-1599791.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/1454804/pexels-photo-1454804.jpeg', 'https://images.pexels.com/photos/1909791/pexels-photo-1909791.jpeg', 'https://images.pexels.com/photos/1910472/pexels-photo-1910472.jpeg', 'https://images.pexels.com/photos/1571462/pexels-photo-1571462.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/3797991/pexels-photo-3797991.jpeg', 'https://images.pexels.com/photos/4138152/pexels-photo-4138152.jpeg', 'https://images.pexels.com/photos/584399/living-room-couch-interior-room-584399.jpeg', 'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
				];
				break;
			case 'house':
				$subtype = $faker->randomElement(['semi_detached', 'detached', 'terraced', 'bungalow']);
				$amenities = $faker->randomElements(['pool', 'basement', 'terrace', 'garden', 'parking', 'attic']);

				// Rooms
				$has_rooms = $faker->boolean();
				if ($has_rooms) {
					$rooms = [
						[
							"type" => "living_room",
							"size_description" => "Living room",
							"size" => $faker->numberBetween(10, 30),
							"unit" => "sq_m",
							"ordinal" => 1
						],
						[
							"type" => "bathroom",
							"size_description" => "Bathroom",
							"size" => $faker->numberBetween(10, 30),
							"unit" => "sq_m",
							"ordinal" => 1
						],
						[
							"type" => "terrace",
							"size_description" => "Terrace",
							"size" => $faker->numberBetween(10, 30),
							"unit" => "sq_m",
							"ordinal" => 1
						],
						[
							"type" => "garden",
							"size_description" => "Garden",
							"size" => $faker->numberBetween(10, 30),
							"unit" => "sq_m",
							"ordinal" => 1
						]
					];
				}

				// Negotiation
				switch ($negotiation) {
					case 'sale':
						$title = [
							'nl' => $faker->randomElement(['Instapklare woning te koop', 'Energiezuinige woning te koop', 'Unieke woning op centrale ligging']),
							'en' => $faker->randomElement(['Ready-to-move-in house for sale', 'Energy efficient house for sale', 'Unique house in a central location']),
							'fr' => $faker->randomElement(['Maison prête à emménager à vendre', 'Maison écoénergétique à vendre', 'Maison unique dans un emplacement central']),
						];
						$price = $faker->randomElement([290000, 360000, 450000, 600000, 1500000]);
						break;
					case 'let':
						$title = [
							'nl' => $faker->randomElement(['Goed onderhouden woning te huur', 'Woning te huur met zongericht terras', 'Alleenstaande woning te huur']),
							'en' => $faker->randomElement(['Well maintained house for rent', 'House for rent with sunny terrace', 'Detached house for rent']),
							'fr' => $faker->randomElement(['Maison bien entretenue à louer', 'Maison à louer avec terrasse ensoleillée', 'Maison individuelle à louer']),
						];
						$price = $faker->randomElement([750, 820, 980, 1100]);
						break;
				}

				// Images
				$images = [
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/164522/pexels-photo-164522.jpeg', 'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg', 'https://images.pexels.com/photos/259593/pexels-photo-259593.jpeg', 'https://images.pexels.com/photos/1115804/pexels-photo-1115804.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/2089698/pexels-photo-2089698.jpeg', 'https://images.pexels.com/photos/3214064/pexels-photo-3214064.jpeg', 'https://images.pexels.com/photos/3926542/pexels-photo-3926542.jpeg', 'https://images.pexels.com/photos/1599791/pexels-photo-1599791.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/1454804/pexels-photo-1454804.jpeg', 'https://images.pexels.com/photos/1909791/pexels-photo-1909791.jpeg', 'https://images.pexels.com/photos/1910472/pexels-photo-1910472.jpeg', 'https://images.pexels.com/photos/1571462/pexels-photo-1571462.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/3797991/pexels-photo-3797991.jpeg', 'https://images.pexels.com/photos/4138152/pexels-photo-4138152.jpeg', 'https://images.pexels.com/photos/584399/living-room-couch-interior-room-584399.jpeg', 'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
				];
				break;
			case 'land':
				$subtype = $faker->randomElement(['pasture_land', 'agricultural']);
				$amenities = [];
				$title = [
					'nl' => $faker->randomElement(['Bouwgrond te koop', 'Bouwgrond voor alleenstaande woning', 'Zeer groot hoekperceel']),
					'en' => $faker->randomElement(['Building plot for sale', 'Building plot for a detached house', 'Very large corner lot']),
					'fr' => $faker->randomElement(['Terrain à bâtir à vendre', 'Terrain à bâtir pour une maison individuelle', 'Très grand terrain de coin']),
				];
				// Images
				$images = [
					[
						"id" => "1234-5678-9012",
						"filename" => "my-image.jpeg",
						"description" => "My image",
						"url" => $faker->randomElement(['https://images.pexels.com/photos/102728/pexels-photo-102728.jpeg', 'https://images.pexels.com/photos/391831/pexels-photo-391831.jpeg', 'https://images.pexels.com/photos/914682/pexels-photo-914682.jpeg']),
						"url_expires_on" => "2017-01-01T12:12:12+00:00",
						"ordinal" => 1
					],
				];
				break;
		}

		// Has open days
		$has_open_days = $faker->boolean();
		$open_homes = [];

		if ($has_open_days) {
			$open_homes = [
				[
					"start_date" => $faker->dateTimeBetween('now', '+40 days')->format('c'),
					"end_date" => $faker->dateTimeBetween('now', '+80 days')->format('c'),
				],
				[
					"start_date" => $faker->dateTimeBetween('now', '+40 days')->format('c'),
					"end_date" => $faker->dateTimeBetween('now', '+80 days')->format('c'),
				],
				[
					"start_date" => $faker->dateTimeBetween('-40 days', '-10 days')->format('c'),
					"end_date" => $faker->dateTimeBetween('-40 days', '-10 days')->format('c'),
				]
			];
		}

		if ($is_unit) {
			$title['nl'] = "Unit " . $index . ": " . $title['nl'];
			$title['en'] = "Unit " . $index . ": " . $title['en'];
			$title['fr'] = "Unit " . $index . ": " . $title['fr'];
		}

		if ($is_project) {
			$title['nl'] = "Nieuwbouw: " . $title['nl'];
			$title['en'] = "New build: " . $title['en'];
			$title['fr'] = "Nouvelle construction: " . $title['fr'];
		}

		// Property
		$property = [
			"id" => $estate_id,
			"is_project" => $is_project,
			"project_id" => $project_id,
			"type" => $type,
			"sub_type" => $subtype,
			"negotiation" => $negotiation,
			"rent_period" => $faker->randomElement(['month', 'week']),
			"status" => $status,
			"description" => [
				"en" => $faker->realText(400),
				"fr" => $faker->realText(400),
				"nl" => $faker->realText(400)
			],
			"description_title" => $title,
			"living_rooms" => $faker->randomDigit(),
			"kitchens" => $faker->randomDigit(),
			"bedrooms" => $faker->randomDigit(),
			"bathrooms" => $faker->randomDigit(),
			"toilets" => $faker->randomDigit(),
			"floors" => $faker->randomDigit(),
			"showrooms" => $faker->randomDigit(),
			"manufacturing_areas" => $faker->randomDigit(),
			"storage_rooms" => $faker->randomDigit(),
			"kitchen_condition" => $faker->randomElement(['good', 'poor', 'fair', 'new', 'mint']),
			"bathroom_condition" => $faker->randomElement(['good', 'poor', 'fair', 'new', 'mint']),
			"garden_orientation" => $faker->randomElement(['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW']),
			"terrace_orientation" => $faker->randomElement(['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW']),
			"video_url" => $faker->randomElement(['', 'https://www.youtube.com/watch?v=kkEPkKF0XGA', 'https://www.youtube.com/watch?v=hr9KR6t_mEM']),
			"virtual_tour_url" => $faker->randomElement(['', 'https://host.drawbotics.com/revo/demos/hauts-du-port/#/building/20']),
			"appointment_service_url" => $faker->randomElement(['https://calendly.com/', '']),
			"general_condition" => $faker->randomElement(['good', 'new']),
			"legal" => [
				"energy" => [
					"epc_value" => $faker->randomElement([100, 200, 300, 400, 500, 600, 700]),
					"epc_category" => $faker->randomElement(['A++', 'A+', 'A', 'B', 'C', 'D', 'E', 'F', 'G']),
					"epc_reference" => "20081101-0000000245-00000015",
					"total_epc_value" => $faker->randomElement([100, 200, 300, 400, 500, 600, 700]),
					"nabers" => [
						"description" => "Historical and current water and energy use",
						"type" => "number",
						"maximum" => $faker->numberBetween(5, 10),
						"minimum" => $faker->numberBetween(0, 4),
					],
					"nathers" => [
						"description" => "All features of a building, including location",
						"type" => "number",
						"maximum" => $faker->numberBetween(5, 10),
						"minimum" => $faker->numberBetween(0, 4),
					],
					"co2_emissions" => $faker->numberBetween(50, 500),
					"e_level" => $faker->randomElement(['E90', 'E80', 'E70', 'E60', 'E50', 'E40', 'E30', 'E20', 'E10', '']),
					"report_electricity_gas" => $faker->randomElement(['conform', 'not_conform', 'no_report', 'not_applicable', '']),
					"report_fuel_tank" => $faker->randomElement(['conform', 'not_conform', 'no_report', 'not_applicable', '']),
				],
				"regulations" => [
					"building_permit" => $faker->randomElement([1, 0, '']),
					"priority_purchase_right" => $faker->randomElement([1, 0, '']),
					"subdivision_authorisation" => $faker->randomElement([1, 0, '']),
					"urban_planning_breach" => $faker->randomElement([1, 0, '']),
					"as_built_report" => $faker->randomElement([1, 0, '']),
					"expropriation_plan" => $faker->randomElement([1, 0, '']),
					"heritage_list" => $faker->randomElement([1, 0, '']),
					"pending_legal_proceedings" => $faker->randomElement([1, 0, '']),
					"registered_building" => $faker->randomElement([1, 0, '']),
					"site_untapped_activity" => $faker->randomElement([1, 0, '']),
					"urban_planning_certificate" => $faker->randomElement([1, 0, ''])
				],
				"legal_mentions" => [
					"en" => "Legal regulations English",
					"fr" => "Legal regulations French",
					"nl" => "Legal regulations Dutch"
				],
				"property_and_land" => [
					"purchased_year" => "1987",
					"cadastral_income" => 785,
					"flood_risk" => $faker->randomElement(['no_flood_risk_area', 'potential_flood_sensitive_area', 'effective_flood_sensitive_area', '']),
					"land_use_designation" => $faker->randomElement(['residential', 'mixed_residential', '']),
				]
			],
			"auction" => [
				"start_date" => "2018-01-01T10:00:00+00:00"
			],
			"open_homes" => $open_homes,
			"price" => [
				"amount" => $price,
				"currency" => "EUR",
				"hidden" => $faker->boolean(),
			],
			"price_negotiated" => 100000,
			"price_costs" => [
				"en" => "Price costs English",
				"fr" => "Le prix coûte le français",
				"nl" => "Prijs kosten Nederlands"
			],
			"price_taxes" => [
				"en" => "Taxes in English",
				"fr" => "Taxes en français",
				"nl" => "Belastingen in het Nederlands"
			],
			"custom_price" => "Price available on request",
			"location" => [
				"geo" => [
					"latitude" => $faker->latitude(50.95686, 51.20892),
					"longitude" => $faker->longitude(3.22424, 3.59425),
				],
				"city" => $faker->city(),
				"street" => $faker->streetName(),
				"street_2" => $faker->streetName(),
				"number" => $faker->buildingNumber(),
				"box" => $faker->numberBetween(0, 10),
				"floor" => $faker->numberBetween(1, 10),
				"addition" => $faker->randomLetter(),
				"country" => "BE",
				"formatted" => $faker->streetAddress(),
				"formatted_agency" => "Formatted Agency",
				"postal_code" => $faker->postcode(),
				"hidden" => false
			],
			"amenities" => $amenities,
			"sizes" => [
				"plot_area" => [
					"size" => $faker->numberBetween(1, 5),
					"unit" => "are"
				],
				"liveable_area" => [
					"size" => $faker->numberBetween(50, 300),
					"unit" => "sq_m"
				]
			],
			"permissions" => [
				"farming" => $faker->boolean(),
				"fishing" => $faker->boolean(),
				"planning" => $faker->boolean(),
				"construction" => $faker->boolean(),
			],
			"rooms" => $rooms,
			"images" => $images,
			"plans" => $plans,
			"documents" => $documents,
			"features" => [
				"energy" => [
					"gas" => $faker->boolean(),
					"fuel" => $faker->boolean(),
					"electricity" => $faker->boolean(),
					"heat_pump" => $faker->boolean()
				],
				"comfort" => [
					"home_automation" => $faker->boolean(),
					"water_softener" => $faker->boolean(),
					"fireplace" => $faker->boolean(),
					"walk_in_closet" => $faker->boolean(),
					"home_cinema" => $faker->boolean(),
					"wine_cellar" => $faker->boolean(),
					"sauna" => $faker->boolean(),
					"fitness_room" => $faker->boolean(),
					"furnished" => $faker->boolean()
				],
				"ecology" => [
					"double_glazing" => $faker->boolean(),
					"solar_panels" => $faker->boolean(),
					"solar_boiler" => $faker->boolean(),
					"rainwater_harvesting" => $faker->boolean(),
					"insulated_roof" => $faker->boolean()
				],
				"security" => [
					"alarm" => $faker->boolean(),
					"concierge" => $faker->boolean(),
					"video_surveillance" => $faker->boolean()
				],
				"heating_cooling" => [
					"central_heating" => $faker->boolean(),
					"floor_heating" => $faker->boolean(),
					"air_conditioning" => $faker->boolean()
				]
			],
			"building" => [
				"renovation" => [
					"year" => $faker->numberBetween(2015, 2020),
					"description" => $faker->sentence(3)
				],
				"construction" => [
					"year" => $faker->numberBetween(1990, 2013),
					"architect" => $faker->company(),
				]
			],
			"negotiator" => [
				"first_name" => $faker->firstName(),
				"last_name" => $faker->lastName(),
				"email" => $faker->companyEmail(),
				"phone" => $faker->phoneNumber(),
				"photo_url" => $faker->randomElement(['https://images.pexels.com/photos/4063856/pexels-photo-4063856.jpeg', 'https://images.pexels.com/photos/4347368/pexels-photo-4347368.jpeg', 'https://images.pexels.com/photos/3206114/pexels-photo-3206114.jpeg', 'https://images.pexels.com/photos/2182970/pexels-photo-2182970.jpeg']),
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
				"name" => $faker->company(),
			],
			"buyers" => [
				[
					"first_name" => $faker->firstName(),
					"last_name" => $faker->lastName(),
					"email" => $faker->freeEmail(),
					"phone" => $faker->phoneNumber()
				]
			],
			"vendors" => [
				[
					"first_name" =>  $faker->firstName(),
					"last_name" => $faker->lastName(),
					"email" => $faker->freeEmail(),
					"phone" => $faker->phoneNumber(),
				]
			],
			"settings" => [
				"reference" => "internal reference"
			],
		];

		// Properties
		$is_project = false;
		if ($property_type === 'project' && !$is_unit) {
			$properties = [];
			$total_properties = $faker->numberBetween(1, 5);

			for ($x = 0; $x <= $total_properties; $x += 1) {
				$properties[] = $this->create_property($estate_id, true, $x + 1, $type);
			}

			$property['properties'] = $properties;
		}

		return $property;
	}

	public function init($data)
	{
		$faker = Faker\Factory::create();

		// Create property
		$estate_id = $faker->bothify('esate-??????????-##########');
		$property = $this->create_property($estate_id, false, false, false);

		// Output
		return rest_ensure_response($property);
	}
}
