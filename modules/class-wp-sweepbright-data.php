<?php

/**
 * WP_SweepBright_Data.
 *
 * Create the custom post type for estates.
 *
 * @package    WP_SweepBright_Data
 */

class WP_SweepBright_Data {

	public function __construct() {
		$this->create_logs();
		$this->create_estates_custom_post_type();
		$this->acf_disable_fields();
		$this->acf_add_fields();
	}

	public function create_logs() {
		function add_log_types($types) {
			$types[] = 'wp_sweepbright_logs';
			return $types;
		}
		add_filter('wp_log_types', 'add_log_types');
	}

	public function create_estates_custom_post_type() {
		register_post_type(
			'sweepbright_estates',
			[
				'labels' => [
					'name'                => __( 'Estates', 'text_domain' ),
					'singular_name'       => __( 'Estate', 'text_domain' ),
					'menu_name'           => __( 'Post Type', 'text_domain' ),
					'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
					'all_items'           => __( 'Estates', 'text_domain' ),
					'view_item'           => __( 'View Estate', 'text_domain' ),
					'add_new_item'        => __( 'Add New Estate', 'text_domain' ),
					'add_new'             => __( 'Add Estate', 'text_domain' ),
					'edit_item'           => __( 'Edit Estate', 'text_domain' ),
					'update_item'         => __( 'Update Estate', 'text_domain' ),
					'search_items'        => __( 'Search Estate', 'text_domain' ),
					'not_found'           => __( 'Not found', 'text_domain' ),
					'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
				],
				'public' => true,
				'has_archive' => false,
				'rewrite' => [
					'slug' => WP_SweepBright_Helpers::settings_form()['custom_url'] . '/%post_id%',
					'with_front' => false,
				],
				'show_in_menu' => false,
				'show_in_rest' => false,
			]
		);

		function custom_permalink($post_link, $id = 0) {
			if (strpos('%post_id%', $post_link) === 'FALSE') {
				return $post_link;
			}
			$post = get_post($id);
			if (is_wp_error($post) || $post->post_type != 'sweepbright_estates') {
				return $post_link;
			}
			return str_replace('%post_id%', $post->ID, $post_link);
		}
		add_filter('post_type_link', 'custom_permalink', 1, 3);
	}

	public function acf_disable_fields() {
		function disable_classic_editor() {
			remove_post_type_support('sweepbright_estates', 'editor');
		}
		add_action('admin_head', 'disable_classic_editor');
	}

	public function acf_add_fields() {
		if (function_exists('acf_add_local_field_group')):
			// Retrieve classes to build the groups
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-estate.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-open-homes.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-price.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-location.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-features.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-facilities.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-rooms.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-conditions.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-building.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-sizes.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-energy.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-ecology.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-security.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-heating-cooling.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-comfort.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-amenities.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-vendors.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-negotiator.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-office.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-occupancy.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-orientation.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-regulations.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-legal.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-property.php';
			require_once plugin_dir_path( __DIR__ ). 'modules/fields/class-custom.php';

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
		else:
			function save_settings_notice(){
				echo '<div class="notice notice-error is-dismissible">
				<p>SweepBright for WordPress requires <strong>Advanced Custom Fields PRO</strong> which is not installed. Please, <a href="https://www.advancedcustomfields.com/pro/" target="_blank">install</a> this dependecy first.</p>
				</div>';
			}
			add_action('admin_notices', 'save_settings_notice');
		endif;
	}

}
