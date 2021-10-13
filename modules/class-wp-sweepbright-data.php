<?php

/**
 * WP_SweepBright_Data.
 *
 * Create the custom post type for estates.
 *
 * @package    WP_SweepBright_Data
 */

class WP_SweepBright_Data
{

	public function __construct()
	{
		$this->create_estates_custom_post_type();
		$this->acf_disable_fields();
		$this->acf_add_fields();
		$this->migrate_db();
	}

	public function migrate_db()
	{
		require_once plugin_dir_path(__DIR__) . 'modules/class-wp-sweepbright-cache.php';
		WP_SweepBright_Cache::migrate_db();
	}

	public static function translate_slug_prefix()
	{
		return [
			'en' => [
				'sale' => 'buy',
				'let' => 'rent',
			],
			'fr' => [
				'sale' => 'a-vendre',
				'let' => 'a-louer',
			],
			'nl' => [
				'sale' => 'te-koop',
				'let' => 'te-huur',
			],
		];
	}

	public function create_estates_custom_post_type()
	{
		$slug = WP_SweepBright_Helpers::settings_form()['custom_url'];

		register_post_type(
			'sweepbright_estates',
			[
				'labels' => [
					'name'                => __('Estates', 'text_domain'),
					'singular_name'       => __('Estate', 'text_domain'),
					'menu_name'           => __('Post Type', 'text_domain'),
					'parent_item_colon'   => __('Parent Item:', 'text_domain'),
					'all_items'           => __('Estates', 'text_domain'),
					'view_item'           => __('View Estate', 'text_domain'),
					'add_new_item'        => __('Add New Estate', 'text_domain'),
					'add_new'             => __('Add Estate', 'text_domain'),
					'edit_item'           => __('Edit Estate', 'text_domain'),
					'update_item'         => __('Update Estate', 'text_domain'),
					'search_items'        => __('Search Estate', 'text_domain'),
					'not_found'           => __('Not found', 'text_domain'),
					'not_found_in_trash'  => __('Not found in Trash', 'text_domain'),
				],
				'public' => true,
				'has_archive' => false,
				'rewrite' => [
					'slug' => $slug,
					'with_front' => false,
				],
				'show_in_menu' => false,
				'show_in_rest' => false,
				'publicly_queryable' => true,
			]
		);

		add_filter('post_type_link', function ($post_link, $post) {
			if ($post && 'sweepbright_estates' === $post->post_type) {
				// Build default estate URL
				$slug = WP_SweepBright_Helpers::settings_form()['custom_url'];

				// Dynamic URL
				if (WP_SweepBright_Helpers::setting('dynamic_url') == 1) {
					if (get_post_meta($post->ID, 'features_negotiation', true)) {
						$slug = WP_SweepBright_Data::translate_slug_prefix()[WP_SweepBright_Helpers::setting('default_language')][get_post_meta($post->ID, 'features_negotiation', true)];
					}
				}

				// Base URL
				$url = $slug . '/' . $post->ID;

				if (in_array('polylang-pro/polylang.php', apply_filters('active_plugins', get_option('active_plugins')))) {
					global $polylang;

					// Get current or default language
					if (is_admin() && !pll_current_language()) {
						$lang = $polylang->pref_lang->slug;
					} else if (!is_admin() && !pll_current_language()) {
						$lang = WP_SweepBright_Helpers::setting('default_language');
					} else {
						$lang = pll_current_language();
					}

					// Dynamic URL
					if (WP_SweepBright_Helpers::setting('dynamic_url') == 1) {
						if (get_post_meta($post->ID, 'features_negotiation', true)) {
							$slug = WP_SweepBright_Data::translate_slug_prefix()[$lang][get_post_meta($post->ID, 'features_negotiation', true)];
							$url = $slug . '/' . $post->ID;
						}
					}

					// Prepend language prefix only when the data is available.
					// E.g. on the sitemap `get_field()` is empty because of no specific URL context
					if (get_field('estate')['title'][$lang]) {
						$url = $lang . '/' . $url . '/' . sanitize_title(get_field('estate')['title'][$lang]);
					} else {
						$url = $lang . '/' . $url . '/' . $post->post_name;
					}
				} else {
					$url = $url . '/' . $post->post_name;
				}
				return home_url($url);
			}
			return $post_link;
		}, 10, 2);

		// Language prefix rewrite rule
		if (in_array('polylang-pro/polylang.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			function rewrite_url()
			{
				$slug = WP_SweepBright_Helpers::settings_form()['custom_url'];
				$slug_prefixes = [$slug];

				// Dynamic URL
				if (WP_SweepBright_Helpers::setting('dynamic_url') == 1) {
					foreach (WP_SweepBright_Data::translate_slug_prefix() as $key => $prefix) {
						$slug_prefixes[] = $prefix['sale'];
						$slug_prefixes[] = $prefix['let'];
					}
				}
				$slug = '(' . implode('|', $slug_prefixes) . ')';
				add_rewrite_rule(
					'(fr|nl|en)/' . $slug . '/([0-9]+)/(.+?)?$',
					'index.php?post_type=sweepbright_estates&lang=$matches[1]&page_id=$matches[3]',
					'top'
				);
			}
			add_action('init', 'rewrite_url', 11, 0);
		} else {
			function rewrite_url()
			{
				$slug = WP_SweepBright_Helpers::settings_form()['custom_url'];
				$slug_prefixes = [$slug];

				// Dynamic URL
				if (WP_SweepBright_Helpers::setting('dynamic_url') == 1) {
					foreach (WP_SweepBright_Data::translate_slug_prefix() as $key => $prefix) {
						$slug_prefixes[] = $prefix['sale'];
						$slug_prefixes[] = $prefix['let'];
					}
				}
				$slug = '(' . implode('|', $slug_prefixes) . ')';
				add_rewrite_rule(
					$slug . '/([0-9]+)/(.+?)?$',
					'index.php?post_type=sweepbright_estates&page_id=$matches[2]',
					'top'
				);
			}
			add_action('init', 'rewrite_url', 11, 0);
		}
	}

	public function acf_disable_fields()
	{
		function disable_classic_editor()
		{
			remove_post_type_support('sweepbright_estates', 'editor');
		}
		add_action('admin_head', 'disable_classic_editor');
	}

	public function acf_add_fields()
	{
		if (function_exists('acf_add_local_field_group')) :
			// Retrieve classes to build the groups
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-estate.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-open-homes.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-price.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-location.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-features.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-facilities.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-rooms.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-conditions.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-building.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-sizes.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-energy.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-ecology.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-security.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-heating-cooling.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-comfort.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-amenities.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-vendors.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-negotiator.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-office.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-occupancy.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-orientation.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-regulations.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-legal.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-property.php';
			require_once plugin_dir_path(__DIR__) . 'modules/fields/class-custom.php';

			// Merge fields into one group
			$estate_fields = array_merge(
				[FieldEstate::retrieve()],
				[FieldOpenHomes::retrieve()],
				[FieldPrice::retrieve()],
				[FieldLocation::retrieve()],
				[FieldFeatures::retrieve()],
				[FieldFacilities::retrieve()],
				[FieldRooms::retrieve()],
				[FieldConditions::retrieve()],
				[FieldBuilding::retrieve()],
				[FieldSizes::retrieve()],
				[FieldEnergy::retrieve()],
				[FieldEcology::retrieve()],
				[FieldSecurity::retrieve()],
				[FieldHeatingCooling::retrieve()],
				[FieldComfort::retrieve()],
				[FieldAmenities::retrieve()],
				[FieldVendors::retrieve()],
				[FieldNegotiator::retrieve()],
				[FieldOffice::retrieve()],
				[FieldOccupancy::retrieve()],
				[FieldOrientation::retrieve()],
				[FieldRegulations::retrieve()],
				[FieldLegal::retrieve()],
				[FieldProperty::retrieve()],
				[FieldCustom::retrieve()],
			);

			// Field group
			acf_add_local_field_group([
				'key' => 'group_estate',
				'title' => 'Estate',
				'fields' => $estate_fields,
				'location' => [
					[
						[
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'sweepbright_estates',
						]
					]
				],
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
			]);
		else :
			function save_settings_notice()
			{
				echo '<div class="notice notice-error is-dismissible">
				<p>SweepBright for WordPress requires <strong>Advanced Custom Fields PRO</strong> which is not installed. Also, please make sure to fill in the license key. Please, <a href="https://www.advancedcustomfields.com/pro/" target="_blank">install</a> this dependecy first.</p>
				</div>';
			}
			add_action('admin_notices', 'save_settings_notice');
		endif;
	}
}
