<?php

/**
 * WP_SweepBright_Controller_Logs.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Logs
 */

class WP_SweepBright_Controller_Logs
{

  public function __construct()
  {
  }

  public function init()
  {
    $status = [
      'message' => 'No publications happened.',
      'status' => 'idle',
      'date' => date_i18n('d M Y, h:i:s A', current_time('timestamp')),
    ];

    if (get_option('wp_sweepbright_publish_status')) {
      $status = get_option('wp_sweepbright_publish_status');
    }

    // Output
    return rest_ensure_response([
      'STATUS' => $status,
      'TOTAL' => 0,
      'STATUS_CODE' => http_response_code(200),
    ]);
  }
}
