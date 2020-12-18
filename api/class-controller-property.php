<?php

/**
 * WP_SweepBright_Controller_Property.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Property
 */

class WP_SweepBright_Controller_Property
{

  public function __construct()
  {
  }

  public function init($data)
  {
    $estate = [];

    $args = [
      'post_status' => 'publish',
      'post_type' => 'sweepbright_estates',
      'fields' => 'ids',
      'meta_query' => [
        'relation' => 'AND',
        [
          'key' => 'estate_id',
          'value' => $data['estate_id'],
          'compare' => '='
        ]
      ],
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

  public function save($data)
  {
    $id = WP_SweepBright_Helpers::get_post_ID_from_estate($data['estate_id']);

    // Save tag
    if (trim($data['form']['tag']) || !$data['form']['tag']) {
      update_field('custom_fields', [
        'tag' => trim($data['form']['tag']),
      ], $id);
    }

    // Save style
    if (trim($data['form']['style']) || !$data['form']['style']) {
      update_field('custom_fields', [
        'style' => trim($data['form']['style']),
      ], $id);
    }

    // Save type
    if (trim($data['form']['type']) || !$data['form']['type']) {
      update_field('custom_fields', [
        'type' => trim($data['form']['type']),
      ], $id);
    }

    // Save USPs
    $usps = [];
    foreach ($data['form']['usp'] as $usp) {
      $usp['value'] = trim($usp['value']);
      if ($usp['value']) {
        $usps[] = [
          'acf_fc_layout' => 'usp_layout',
          'usp_item' => $usp['value'],
        ];
      }
    }
    update_field('custom_fields', [
      'usp' => $usps,
    ], $id);

    // Save contact
    if (trim($data['form']['contact']['title']) || !$data['form']['contact']['title']) {
      update_field('custom_fields', [
        'contact' => [
          'title' => trim($data['form']['contact']['title'])
        ],
      ], $id);
    }
    if (trim($data['form']['contact']['description']) || !$data['form']['contact']['description']) {
      update_field('custom_fields', [
        'contact' => [
          'description' => trim($data['form']['contact']['description'])
        ],
      ], $id);
    }

    // Save CTA
    if (trim($data['form']['cta']['label']) || !$data['form']['cta']['label']) {
      update_field('custom_fields', [
        'cta' => [
          'label' => trim($data['form']['cta']['label'])
        ],
      ], $id);
    }
    if (trim($data['form']['cta']['action']) || !$data['form']['cta']['action']) {
      update_field('custom_fields', [
        'cta' => [
          'action' => trim($data['form']['cta']['action'])
        ],
      ], $id);
    }

    // Update cache
    FileSystemCache::$cacheDir = WP_PLUGIN_DIR . '/wp-sweepbright/db/' . WP_SweepBright_Query::slugify(get_bloginfo('name'));
    $key = FileSystemCache::generateCacheKey('estates');
    FileSystemCache::invalidate($key);

    return rest_ensure_response([
      'STATUS_CODE' => http_response_code(200),
    ]);
  }

  public function units($data)
  {
    $project_id = get_field('estate', $data['estate_id'])['id'];
    $units = WP_SweepBright_Query::list_units([
      'project_id' => $project_id,
      'ignore_self' => false,
      'is_paged' => false,
      'page' => false,
    ]);

    $result = [
      'totalPages' => $units['totalPages'],
      'totalPosts' => $units['totalPosts'],
      'meta' => $units['results'],
    ];
    return rest_ensure_response($result);
  }

  public function units_paged($data)
  {
    $project_id = get_field('estate', $data['estate_id'])['id'];
    $units = WP_SweepBright_Query::list_units([
      'project_id' => $project_id,
      'ignore_self' => false,
      'is_paged' => true,
      'page' => $data['page'],
    ]);

    $result = [
      'totalPages' => $units['totalPages'],
      'totalPosts' => $units['totalPosts'],
      'meta' => $units['results'],
    ];
    return rest_ensure_response($result);
  }
}
