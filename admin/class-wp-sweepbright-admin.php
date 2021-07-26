<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    WP_SweepBright
 * @subpackage WP_SweepBright/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_SweepBright
 * @subpackage WP_SweepBright/admin
 * @author     Falko Joseph <falko@compagnon.agency>
 */
class WP_SweepBright_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_SweepBright_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_SweepBright_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if (get_current_screen()->base === "toplevel_page_wp-sweepbright-properties") {
			wp_enqueue_style('font-awesome', 'https://pro.fontawesome.com/releases/v5.15.0/css/all.css');
			wp_enqueue_style($this->plugin_name, plugins_url('wp-sweepbright') . '/dist/wp-sweepbright-admin.css', array(), $this->version, 'all');
		}

		if (get_current_screen()->base === "sweepbright_page_wp-sweepbright-pages") {
			wp_enqueue_style('font-awesome', 'https://pro.fontawesome.com/releases/v5.15.0/css/all.css');
			wp_enqueue_style($this->plugin_name, plugins_url('wp-sweepbright') . '/dist/wp-sweepbright-admin.css', array(), $this->version, 'all');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_SweepBright_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_SweepBright_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugins_url('wp-sweepbright') . '/dist/wp-sweepbright-admin.js', array(), $this->version, false);
	}

	/**
	 * Load the data module which creates the custom post type.
	 */
	public function load_wp_sweepbright_data()
	{
		require_once plugin_dir_path(__DIR__) . 'modules/class-wp-sweepbright-data.php';
		$wp_sweepbright_data = new WP_SweepBright_Data();
	}

	/**
	 * Load the hook.
	 */
	public function load_wp_sweepbright_hook()
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-hook.php';
		$wp_sweepbright_controller_hook = new WP_SweepBright_Controller_Hook();
	}


	/**
	 * Load the contact form.
	 */
	public function load_wp_sweepbright_contact()
	{
		require_once plugin_dir_path(__DIR__) . 'admin/pages/class-wp-sweepbright-contact.php';
		$wp_sweepbright_contact = new WP_SweepBright_Contact();
	}

	/**
	 * Load the geo location library.
	 */
	public function load_wp_sweepbright_geo()
	{
		require_once plugin_dir_path(__DIR__) . 'modules/class-wp-sweepbright-geo.php';
	}

	/**
	 * Load the general SweepBright class containing global helpers.
	 */
	public function load_wp_sweepbright_helpers()
	{
		require_once plugin_dir_path(__DIR__) . 'modules/class-wp-sweepbright-helpers.php';
		$wp_sweepbright_helpers = new WP_SweepBright_Helpers();
	}

	/**
	 * Load the SweepBright class containing filters and queries.
	 */
	public function load_wp_sweepbright_query()
	{
		require_once plugin_dir_path(__DIR__) . 'modules/class-wp-sweepbright-query.php';
		$wp_sweepbright_query = new WP_SweepBright_Query();
	}

	/**
	 * Load the pages API.
	 */
	public function load_wp_sweepbright_pages()
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-pages.php';
		$wp_sweepbright_controller_pages = new WP_SweepBright_Controller_Pages();
	}

	/**
	 * Load the wrapper API.
	 */
	public function load_wp_sweepbright_wrapper()
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-controller-wrapper.php';
		$wp_sweepbright_controller_wrapper = new WP_Wrapper();
	}


	/**
	 * Load the API router.
	 */
	public function load_wp_sweepbright_router()
	{
		require_once plugin_dir_path(__DIR__) . 'api/class-router.php';
		$wp_sweepbright_router = new WP_SweepBright_Router();
	}

	/**
	 * Create the WP SweepBright menu page with add_menu_page();
	 */
	public function add_admin_page()
	{
		$icon = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgMjAwIDIwMCI+CiAgPHBhdGggaWQ9IlBhdGhfMSIgZGF0YS1uYW1lPSJQYXRoIDEiIGQ9Ik0xNTIwLjE2NywzNzFjLTE1LjAyMiwwLTUwLjItNTcuMTktNTAuMi04MS41NzlhNTAuMTk1LDUwLjE5NSwwLDEsMSwxMDAuMzksMEMxNTcwLjM2MiwzMTMuODEsMTUzNS4xNzcsMzcxLDE1MjAuMTY3LDM3MVptMC0xMTkuMjM5YTM3LjcsMzcuNywwLDAsMC0zNy42NDksMzcuNjZjMCwyMS4xNTUsMjguODIsNjMuMzE3LDM3LjY0OSw2OC43MjIsOC44Mi01LjQwNiwzNy42NTYtNDcuNTY3LDM3LjY1Ni02OC43MjJBMzcuNywzNy43LDAsMCwwLDE1MjAuMTY3LDI1MS43NjFabTAsMjUuMTA2YTkuNDE3LDkuNDE3LDAsMSwxLTkuNDEsOS40MTdBOS40MTksOS40MTksMCwwLDEsMTUyMC4xNjksMjc2Ljg2N1ptLTEuMjM1LTcxLjEzMmE2LjAxNCw2LjAxNCwwLDAsMS02LjAwOS02LjAxMlYxNzcuMDA5YTYuMDEsNi4wMSwwLDAsMSwxMi4wMjEsMHYyMi43MTRBNi4wMTEsNi4wMTEsMCwwLDEsMTUxOC45MzQsMjA1LjczNVptNDMuODEyLDEyLjA2OWE2LjAxMSw2LjAxMSwwLDAsMS00Ljc3OC05LjY1OWwxMy43NjMtMTguMDZhNi4wMTEsNi4wMTEsMCwxLDEsOS41NjMsNy4yODVsLTEzLjc2MywxOC4wNjNBNi4wMDksNi4wMDksMCwwLDEsMTU2Mi43NDYsMjE3LjhabS04OC4xNTIsMi4yNzFhNS45ODksNS45ODksMCwwLDEtNC43ODgtMi4zNzFsLTEzLjc2MS0xOC4wNjZhNi4wMSw2LjAxLDAsMCwxLDkuNTYyLTcuMjgybDEzLjc2OCwxOC4wNjlhNi4wMTUsNi4wMTUsMCwwLDEtNC43ODEsOS42NVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xNDE4LjgxNSAtMTcxKSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIi8+Cjwvc3ZnPgo=";

		// Main page
		add_menu_page(
			'SweepBright',
			'SweepBright',
			'publish_pages',
			$this->plugin_name . '-properties',
			false,
			$icon,
			2
		);

		// Properties
		add_submenu_page(
			$this->plugin_name . '-properties',
			'Properties',
			'Properties',
			'publish_pages',
			$this->plugin_name . '-properties',
			array($this, 'load_admin_properties'),
		);

		if (WP_SweepBright_Helpers::setting('enable_pages') == 1) {
			// Pages
			add_submenu_page(
				$this->plugin_name . '-properties',
				'Pages',
				'Pages',
				'publish_pages',
				$this->plugin_name . '-pages',
				array($this, 'load_admin_pages'),
			);
		}
		// Database
		add_submenu_page(
			$this->plugin_name . '-properties',
			'Database',
			'Database',
			'activate_plugins',
			'edit.php?post_type=sweepbright_estates',
			false,
		);

		// Contact
		add_submenu_page(
			$this->plugin_name . '-properties',
			'Contact',
			'Contact',
			'activate_plugins',
			$this->plugin_name . '-contact',
			array($this, 'load_admin_contact'),
		);

		// Settings
		add_submenu_page(
			$this->plugin_name . '-properties',
			'Settings',
			'Settings',
			'activate_plugins',
			$this->plugin_name . '-settings',
			array($this, 'load_admin_settings'),
		);
	}

	/**
	 * Load properties.
	 */
	public function load_admin_properties()
	{
		require_once plugin_dir_path(__DIR__) . 'admin/pages/class-wp-sweepbright-properties.php';
		$wp_sweepbright_properties = new WP_SweepBright_Properties();
		require_once plugin_dir_path(__FILE__) . 'partials/properties.php';
	}

	/**
	 * Load pages.
	 */
	public function load_admin_pages()
	{
		require_once plugin_dir_path(__DIR__) . 'admin/pages/class-wp-sweepbright-pages.php';
		$wp_sweepbright_pages = new WP_SweepBright_Pages();
		require_once plugin_dir_path(__FILE__) . 'partials/pages.php';
	}

	/**
	 * Load the contact page.
	 */
	public function load_admin_contact()
	{
		require_once plugin_dir_path(__FILE__) . 'partials/contact.php';
	}

	/**
	 * Load the settings page.
	 */
	public function load_admin_settings()
	{
		require_once plugin_dir_path(__DIR__) . 'admin/pages/class-wp-sweepbright-settings.php';
		$wp_sweepbright_settings = new WP_SweepBright_Settings();
		require_once plugin_dir_path(__FILE__) . 'partials/settings.php';
	}
}
