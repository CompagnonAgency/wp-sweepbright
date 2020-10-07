<?php

/**
 * WP_SweepBright_Controller_Property.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Property
 */

class WP_SweepBright_Controller_Property {

	public function __construct() {
	}

	public function init($data) {
    $estate = [];

    $args = [
      'post_status' => 'publish',
      'p' => $data['estate_id'],
      'post_type' => 'sweepbright_estates',
      'fields' => 'ids'
    ];

    $loop = new WP_Query($args);
    $query = $loop->get_posts();
    
    foreach ($query as $item) {
      $estate[] = [
        'id' => $item,
        'permalink' => get_the_permalink($item),
        'date' => get_the_time('U', $item),
        'meta' => get_fields($item),
      ];
		}

    return rest_ensure_response($estate);
  }
  
  public function save($data) {
    // Save phase
    update_field('custom_fields', [
      'phase' => $data['form']['phase'],
    ], $data['estate_id']);

    // Save USPs
    if (count($data['form']['usp']) > 0) {
      $usps = [];
      foreach ($data['form']['usp'] as $usp) {
        $usps[] = [
          'acf_fc_layout' => 'usp_layout',
          'usp_item' => $usp['value'],
        ];
      }
      update_field('custom_fields', [
        'usp' => $usps,
      ], $data['estate_id']);
    }

    return rest_ensure_response([
      'STATUS_CODE' => http_response_code(200),
    ]);
	}

}
