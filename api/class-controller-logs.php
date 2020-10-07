<?php

/**
 * WP_SweepBright_Controller_Logs.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Logs
 */

class WP_SweepBright_Controller_Logs {

	public function __construct() {
	}

	public function init() {
    // Get logs
    $logs = [];

    $args = array(  
      'post_type' => 'wp_log', // wp_log_type
      'post_status' => 'publish',
      'posts_per_page'   => -1,
    );
    $loop = new WP_Query($args);
    $posts = $loop->get_posts();

		foreach ($posts as $log) {
      $logs[] = [
        'id' => $log->ID,
        'date' => get_the_time('c', $log->ID),
        'action' => get_post_meta($log->ID, '_wp_log_wp_sweepbright_action', true),
        'status' => get_post_meta($log->ID, '_wp_log_wp_sweepbright_status', true),
      ];
    }

    // Defaults
    $synchronization = false;
    $last_updated = false;

    // Get last updated
    if ($logs[0]['status'] === 'Cache completed') {
      $synchronization = true;
    }

    // Last updated
    if ($logs[0]['date']) {
      $last_updated = $logs[0]['date'];
    }

    // Output
    return rest_ensure_response([
      'TOTAL' => count($logs),
      'SYNCHRONIZATION' => $synchronization,
      'LAST_UPDATED' => $last_updated,
      'STATUS_CODE' => http_response_code(200),
    ]);
	}

}
