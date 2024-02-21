<?php

/**
 * WPS_Data.
 *
 * Data retrieval wrappers.
 *
 * @package    WPS_Data
 */

class WP_Wrapper
{

  public function __construct()
  {
  }

  public static function ID()
  {
    $id = get_queried_object_id();
    if ($id === 0) {
      $id = get_option('page_on_front');
    }
    return $id;
  }

  public static function all($component, $args)
  {
    return WP_SweepBright_Controller_Pages::get($component, $args['column']['data']);
  }

  public static function lang()
  {
    return WP_SweepBright_Controller_Pages::current_lang();
  }

  public static function setting($key)
  {
    return WP_SweepBright_Helpers::setting($key);
  }

  public static function get($key, $component, $args, $group_fields = false)
  {
    $output = '';
    $data = WP_SweepBright_Controller_Pages::get($component, $args['column']['data'], $group_fields);
    if (array_key_exists($key, $data)) {
      $output = $data[$key];
    }
    return $output;
  }

  public static function page($data, $link, $args = false)
  {
    $column = false;
    if (array_key_exists('column', $data)) {
      $column = $data['column']['data'];
    } else {
      $column = $data;
    }
    return WP_SweepBright_Controller_Pages::get_page($column, $link, $args);
  }

  public static function theme($key = false)
  {
    $output = '';
    $theme = WP_SweepBright_Controller_Pages::theme_data()->data['THEME'];

    if ($key) {
      if (array_key_exists($key, $theme)) {
        $output = $theme[$key];
      }
    } else {
      $output = $theme;
    }
    return $output;
  }


  public static function has_banner()
  {
    $has_banner = false;

    // Find page by ID
    $post = new WP_Query([
      'p' => WP_Wrapper::ID(),
      'post_type' => 'page',
      'posts_per_page' => 1,
    ]);

    if ($post->posts) {
      $post = $post->posts[0];
      $pages = get_option('wp_sweepbright_page_' . $post->ID)['layout'];

      if ($pages) {
        foreach ($pages as $page) {
          foreach ($page["columns"] as $column) {
            if ($column['component'] === 'banner') {
              $has_banner = true;
            } else if ($column['component'] === 'banner-image') {
              $has_banner = true;
            }
          }
        }
      }
    }
    return $has_banner;
  }
}
