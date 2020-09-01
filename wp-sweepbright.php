<?php
/*
 * @package WP_SweepBright
 *
 * @wordpress-plugin
 *
 * Plugin Name: SweepBright for WordPress
 * Plugin URI: https://compagnon.agency/wp-sweepbright
 * Description: Unofficial SweepBright plugin for WordPress to synchronize properties & contacts.
 * Author: Compagnon Agency
 * Author URI: https://compagnon.agency/
 * Text Domain: wp-sweepbright
 * Version: 1.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WP_SWEEPBRIGHT_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 */
function activate_wp_sweepbright() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-wp-sweepbright-activator.php';
	WP_SweepBright_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wp_sweepbright() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-wp-sweepbright-deactivator.php';
	WP_SweepBright_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_sweepbright');
register_deactivation_hook(__FILE__, 'deactivate_wp_sweepbright');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-sweepbright.php';

/**
 * Auto update module.
 */
require plugin_dir_path( __FILE__ ) . 'lib/BFIGitHubPluginUploader.php';

if (is_admin()) {
  new BFIGitHubPluginUpdater( __FILE__, 'CompagnonAgency', 'wp-sweepbright');
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 0.0.1
 */
function run_wp_sweepbright() {
	$plugin = new WP_SweepBright();
	$plugin->run();
}
run_wp_sweepbright();
