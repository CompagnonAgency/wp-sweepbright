<?php

/**
 * WP_SweepBright_Controller_Locations.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Locations
 */

class WP_SweepBright_Controller_Locations
{

  public function __construct()
  {
    global $wpdb;

    // Create tables
    $this->create_tables('be');
    $this->create_tables('fr');

    // Populate tables, if empty
    $this->populate_tables('be');
    $this->populate_tables('fr');
  }

  public function create_tables($country)
  {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "locations_" . $country;

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name varchar(55) NOT NULL,
      postal_code varchar(5) NOT NULL,
      latitude varchar(55),
      longitude varchar(55),
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }

  public function get_locations_from_csv($country)
  {
    $locations = [];

    $file = fopen(plugin_dir_path(__DIR__) . "csv/" . $country . ".csv", "r");

    while (!feof($file)) {
      $row = fgetcsv($file, 0, ';');

      if ($row && $row[2] && $row[3]) {
        $name = $row[0];
        $postal_code = $row[1];

        if ($country == 'fr') {
          if (strpos($name, 'Ste ') === 0) {
            $name = str_replace('Ste ', 'Sainte ', $name);
          }

          if (strpos($name, 'St ') === 0) {
            $name = str_replace('St ', 'Saint ', $name);
          }

          $name = str_replace(' ', '-', $name);

          $postal_code = strlen($row[1]) == 4 ? '0' . $row[1] : $row[1];
        }

        $locations[] = [
          'name' => $name,
          'postal_code' => $postal_code,
          'latitude' => $row[2],
          'longitude' => $row[3],
        ];
      }
    }

    fclose($file);

    return $locations;
  }

  public function populate_tables($country)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . "locations_" . $country;
    $locations = $wpdb->get_results("SELECT name FROM $table_name LIMIT 1");

    if (empty($locations)) {
      $locations = $this->get_locations_from_csv($country);

      foreach ($locations as $location) {
        $wpdb->insert(
          $table_name,
          [
            'name' => $location['name'],
            'postal_code' => $location['postal_code'],
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
          ]
        );
      }
    }
  }

  public function locations($param)
  {
    global $wpdb;

    header('Content-Type: application/json');

    $country = $param['country'];

    error_log(print_r($param['search'], true));

    // Write the same but group by latitude
    $results = $GLOBALS['wpdb']->get_results("SELECT * FROM {$wpdb->prefix}locations_{$country} WHERE name LIKE '" . $param['search'] . "%' OR postal_code LIKE '%" . $param['search'] . "%' GROUP BY latitude ORDER BY name ASC, postal_code ASC LIMIT 6", OBJECT);

    $locations = [];

    foreach ($results as $result) {
      $locations[] = [
        'key' => $result->id,
        'value' => $result->name . ' (' . $result->postal_code . ')',
        'latLng' => [
          'lat' => $result->latitude,
          'lng' => $result->longitude,
        ],
      ];
    }

    return $locations;
  }
}
