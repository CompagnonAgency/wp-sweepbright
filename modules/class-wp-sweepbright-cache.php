<?php

/**
 * WP_SweepBright_Cache.
 *
 * @package WP_SweepBright_Cache
 */

class WP_SweepBright_Cache
{

  public function __construct()
  {
  }

  public static function migrate_db()
  {
    /*
     Always increase the number after each iteration
    */

    // Create the table which is used for storing properties (WordPress posts) in cache
    if (!get_option('wp_sweepbright_migrate_00010')) {
      // Truncate database
      WP_SweepBright_Cache::drop_table();

      // Create table
      global $wpdb;
      $table_name = $wpdb->prefix . 'sweepbright_estates';
      $wpdb->query("CREATE TABLE `$table_name` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `date` datetime NOT NULL,
        `post_id` int(11) NOT NULL,
        `estate_id` varchar(255) NOT NULL,
        `status` varchar(255) NOT NULL,
        `is_unit` tinyint(1) NOT NULL,
        `is_project` tinyint(1) NOT NULL,
        `General_condition` varchar(255) NOT NULL,
        `negotiation` varchar(255) NOT NULL,
        `category` varchar(255) NOT NULL,
        `subcategory` varchar(255) NOT NULL,
        `negotiator_email` varchar(255) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `plot_area` decimal(10,2) NOT NULL,
        `liveable_area` decimal(10,2) NOT NULL,
        `bedrooms` int(11) NOT NULL,
        `lat` decimal(8,6) NOT NULL,
        `lng` decimal(9,6) NOT NULL,
        `postal_code` varchar(255) NOT NULL,
        `has_open_home` tinyint(1) NOT NULL,
        `office_name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

      // Migrate
      WP_SweepBright_Cache::migrate_properties();

      // Finish migration
      add_option('wp_sweepbright_migrate_00010', true);
    }
  }

  public static function drop_table()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sweepbright_estates';
    $wpdb->query('DROP TABLE ' . $table_name);
  }

  public static function migrate_properties()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sweepbright_estates';
    $query = new WP_Query([
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'post_type' => 'sweepbright_estates',
      'fields' => 'ids',
    ]);
    $posts = $query->posts;
    if ($posts) {
      foreach ($posts as $id) {
        $wpdb->insert($table_name, [
          'date' => get_the_date('c', $id),
          'post_id' => $id,
          'estate_id' => get_post_meta($id, 'estate_id', true),
          'status' => get_post_meta($id, 'estate_status', true),
          'is_unit' => get_post_meta($id, 'estate_project_id', true) ? 1 : 0,
          'is_project' => get_post_meta($id, 'estate_is_project', true) ? 1 : 0,
          'general_condition' => get_post_meta($id, 'conditions_general_condition', true) ? get_post_meta($id, 'conditions_general_condition', true) : '',
          'negotiation' => get_post_meta($id, 'features_negotiation', true),
          'category' => get_post_meta($id, 'features_type', true) ? get_post_meta($id, 'features_type', true) : '',
          'subcategory' => get_post_meta($id, 'features_sub_type', true) ? get_post_meta($id, 'features_sub_type', true) : '',
          'negotiator_email' => get_post_meta($id, 'negotiator_email', true),
          'price' => get_post_meta($id, 'price_amount', true) ? get_post_meta($id, 'price_amount', true) : '',
          'plot_area' => get_post_meta($id, 'sizes_plot_area_size', true) ? get_post_meta($id, 'sizes_plot_area_size', true) : '',
          'liveable_area' => get_post_meta($id, 'sizes_liveable_area_size', true) ? get_post_meta($id, 'sizes_liveable_area_size', true) : '',
          'bedrooms' => get_post_meta($id, 'facilities_bedrooms', true) ? get_post_meta($id, 'facilities_bedrooms', true) : '',
          'lat' => get_post_meta($id, 'location_latitude', true) ? get_post_meta($id, 'location_latitude', true) : '',
          'lng' => get_post_meta($id, 'location_longitude', true) ? get_post_meta($id, 'location_longitude', true) : '',
          'postal_code' => get_post_meta($id, 'location_postal_code', true) ? get_post_meta($id, 'location_postal_code', true) : '',
          'has_open_home' => get_post_meta($id, 'open_homes_hasOpenHome', true) ? 1 : 0,
          'office_name' => get_post_meta($id, 'office_name', true) ? get_post_meta($id, 'office_name', true) : '',
        ]);
      }
    }
  }

  public static function update($estate, $post_id)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sweepbright_estates';

    $data = [
      'date' => date('c'),
      'post_id' => $post_id,
      'estate_id' => $estate['id'],
      'status' => $estate['status'],
      'is_unit' => $estate['project_id'] ? 1 : 0,
      'is_project' => $estate['is_project'] ? 1 : 0,
      'general_condition' => $estate['general_condition'] ? $estate['general_condition'] : '',
      'negotiation' => $estate['negotiation'],
      'category' => $estate['type'] ? $estate['type'] : '',
      'subcategory' => $estate['sub_type'] ? $estate['sub_type'] : '',
      'negotiator_email' => $estate['negotiator']['email'],
      'price' => $estate['price']['amount'] ? $estate['price']['amount'] : '',
      'plot_area' => $estate['sizes']['liveable_area']['size'] ? $estate['sizes']['liveable_area']['size'] : '',
      'liveable_area' => $estate['sizes']['liveable_area']['size'] ? $estate['sizes']['liveable_area']['size'] : '',
      'bedrooms' => $estate['bedrooms'] ? $estate['bedrooms'] : '',
      'lat' => $estate['location']['geo']['latitude'] ? $estate['location']['geo']['latitude'] : '',
      'lng' => $estate['location']['geo']['longitude'] ? $estate['location']['geo']['longitude'] : '',
      'postal_code' => $estate['location']['postal_code'] ? $estate['location']['postal_code'] : '',
      'has_open_home' => count($estate['open_homes']) > 0 ? 1 : 0,
      'office_name' => $estate['office']['name'] ? $estate['office']['name'] : '',
    ];

    $query = "SELECT post_id FROM $table_name WHERE estate_id='" . $estate['id'] . "'";
    $query_results = $wpdb->get_results($query);

    if (count($query_results) == 0) {
      error_log('no_results_insert');
      $wpdb->insert($table_name, $data);
    } else {
      error_log('has_results_update');
      $wpdb->update($table_name, $data, [
        'estate_id' => $estate['id'],
      ]);
    }
  }

  public static function remove($post_id)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sweepbright_estates';
    $wpdb->delete($table_name, [
      'post_id' => $post_id,
    ]);
  }
}
