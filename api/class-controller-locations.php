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
    $this->create_postal_code_tables('be');
    $this->create_postal_code_tables('fr');
    $this->create_region_tables('fr');

    // Populate tables, if empty
    $this->populate_postal_code_tables('be');
    $this->populate_postal_code_tables('fr');
    $this->populate_region_tables('fr');
  }

  public function create_postal_code_tables($country)
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

  public function create_region_tables($country)
  {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "regions_" . $country;

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      code varchar(55) NOT NULL,
      name varchar(55) NOT NULL,
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

  public function get_regions_from_csv($country)
  {
    $regions = [];

    $file = fopen(plugin_dir_path(__DIR__) . "csv/" . $country . "_regions.csv", "r");

    while (!feof($file)) {
      $row = fgetcsv($file, 0, ';');

      if ($row && $row[1]) {
        $regions[] = [
          'code' => $row[0],
          'name' => $row[1],
        ];
      }
    }

    fclose($file);

    return $regions;
  }

  public function populate_postal_code_tables($country)
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

  public function populate_region_tables($country)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . "regions_" . $country;
    $regions = $wpdb->get_results("SELECT name FROM $table_name LIMIT 1");

    if (empty($regions)) {
      $regions = $this->get_regions_from_csv($country);

      foreach ($regions as $region) {
        $wpdb->insert(
          $table_name,
          [
            'code' => $region['code'],
            'name' => $region['name'],
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

    // Get the postal codes and group by postal code
    $postal_codes = $GLOBALS['wpdb']->get_results("SELECT * FROM {$wpdb->prefix}locations_{$country} WHERE name LIKE '" . $param['search'] . "%' OR postal_code LIKE '" . $param['search'] . "%' GROUP BY latitude ORDER BY name ASC, postal_code ASC LIMIT 6", OBJECT);

    // Get the regions
    $regions = $GLOBALS['wpdb']->get_results("SELECT * FROM {$wpdb->prefix}regions_{$country} WHERE name LIKE '" . $param['search'] . "%' OR code LIKE '" . $param['search'] . "%' ORDER BY name ASC, code ASC LIMIT 6", OBJECT);

    // Search for mutual postal codes e.g. if the postal code 03190 occurs multiple times, we want to list it as 03190 (Toutes les villes)
    $mutual_postal_codes = $GLOBALS['wpdb']->get_results("SELECT postal_code, name, latitude, longitude FROM {$wpdb->prefix}locations_{$country} WHERE postal_code LIKE '" . $param['search'] . "%' GROUP BY postal_code ORDER BY name ASC, postal_code ASC LIMIT 6", OBJECT);

    // Merge the postal codes and regions
    $locations = [];

    if (count($mutual_postal_codes) == 1) {
      $locations[] = [
        'key' => $mutual_postal_codes[0]->postal_code,
        'value' => 'Toutes les villes' . ' (' . $mutual_postal_codes[0]->postal_code . ')',
      ];
    }

    foreach ($regions as $region) {
      $locations[] = [
        'key' => $region->id,
        'value' => $region->name . ' (' . $region->code . ')',
      ];
    }

    foreach ($postal_codes as $postal_code) {
      $locations[] = [
        'key' => $postal_code->id,
        'value' => $postal_code->name . ' (' . $postal_code->postal_code . ')',
        'latLng' => [
          'lat' => $postal_code->latitude,
          'lng' => $postal_code->longitude,
        ],
      ];
    }

    return $locations;
  }
}
