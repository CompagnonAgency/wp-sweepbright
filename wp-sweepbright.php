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
 * Version: 1.8.2
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
define('WP_SWEEPBRIGHT_VERSION', '1.8.2');

/**
 * The code that runs during plugin activation.
 */
function activate_wp_sweepbright()
{
  require_once plugin_dir_path(__FILE__) . 'includes/class-wp-sweepbright-activator.php';
  WP_SweepBright_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wp_sweepbright()
{
  require_once plugin_dir_path(__FILE__) . 'includes/class-wp-sweepbright-deactivator.php';
  WP_SweepBright_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_sweepbright');
register_deactivation_hook(__FILE__, 'deactivate_wp_sweepbright');

// Delete post media
function delete_post_media($post_id)
{
  $attachments = get_posts(
    [
      'post_type' => 'attachment',
      'posts_per_page' => -1,
      'post_status' => 'any',
      'post_parent' => $post_id,
    ]
  );

  foreach ($attachments as $attachment) {
    wp_delete_attachment($attachment->ID);
  }
}
add_action('delete_post', 'delete_post_media');
add_action('wp_trash_post', 'delete_post_media');

/**
 * Custom page templates for the estate page.
 */
add_filter('template_include', function ($template) {
  if (get_post() && 'sweepbright_estates' === get_post()->post_type)
    return locate_template('single-estate.php');

  return $template;
});


/**
 * Programmatically set SEO meta description
 */
function ag_yoast_seo_fb_share_descriptions($desc)
{
  global $post;

  if (($post && $post->post_type === 'sweepbright_estates')) {
    if (get_field('estate', $post->ID)['description']['fr']) {
      $default_description = get_field('estate', $post->ID)['description']['fr'];
    }
    if (get_field('estate', $post->ID)['description']['nl']) {
      $default_description = get_field('estate', $post->ID)['description']['nl'];
    }
    if (get_field('estate', $post->ID)['description']['en']) {
      $default_description = get_field('estate', $post->ID)['description']['en'];
    }
    $desc = wp_trim_words($default_description, 25);
  }
  return $desc;
}
add_filter('wpseo_opengraph_desc', 'ag_yoast_seo_fb_share_descriptions');

/**
 * Programmatically set SEO meta image
 */
if (isset(get_option('wp_sweepbright_settings')['enable_pages']) && get_option('wp_sweepbright_settings')['enable_pages'] == 1) {
  function add_images($object)
  {
    global $post;

    if (($post && $post->post_type === 'sweepbright_estates')) {
      $image = get_field('features', $post->ID)['images'][0]['sizes']['large'];
      $object->add_image($image);
    }
  }
  add_action('wpseo_add_opengraph_images', 'add_images');
}

/**
 * Redirect estates that are no longer available.
 */
function redirect_pand()
{
  global $post;
  global $wp_query;

  if (($post && $post->post_type === 'sweepbright_estates') &&
    WP_SweepBright_Helpers::setting('unavailable_properties') === 'hidden'
  ) {
    // Redirect projects & estates
    if (get_field('estate', $post->ID)['status'] !== 'available') {
      $wp_query->set_404();
      status_header(404);
      get_template_part(404);
      exit();
    }

    // Redirect units
    if (get_field('estate', $post->ID)['project_id']) {
      if (get_field('estate', WP_SweepBright_Helpers::get_project())['status'] !== 'available') {
        $wp_query->set_404();
        status_header(404);
        get_template_part(404);
        exit();
      }
    }
  }
}
add_action('template_redirect', 'redirect_pand');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-sweepbright.php';

/**
 * Auto update module.
 */
require plugin_dir_path(__FILE__) . 'lib/BFIGitHubPluginUploader.php';

if (is_admin()) {
  new BFIGitHubPluginUpdater(__FILE__, 'CompagnonAgency', 'wp-sweepbright');
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
function run_wp_sweepbright()
{
  $plugin = new WP_SweepBright();
  $plugin->run();
}
run_wp_sweepbright();
