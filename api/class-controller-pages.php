<?php

/**
 * WP_SweepBright_Controller_Pages.
 *
 * SweepBright hook.
 *
 * @package    WP_SweepBright_Controller_Pages
 */

class WP_SweepBright_Controller_Pages
{

  public function __construct()
  {
  }

  public function settings()
  {
    $onboarded = false;

    if (get_option('wp_sweepbright_onboarded')) {
      $onboarded = true;
    }

    $data = [
      'onboarded' => $onboarded,
      'description' => get_bloginfo('description'),
      'client_id' => WP_SweepBright_Helpers::settings_form()['client_id'],
      'client_secret' => WP_SweepBright_Helpers::settings_form()['client_secret'],
      'recaptcha_site_key' => WP_SweepBright_Helpers::settings_form()['recaptcha_site_key'],
      'recaptcha_secret_key' => WP_SweepBright_Helpers::settings_form()['recaptcha_secret_key'],
    ];

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'DATA' => $data,
    ];
    return rest_ensure_response($result);
  }

  public static function current_lang()
  {
    return 'nl';
  }

  public function create_pages($data)
  {
    $pages = [
      'Home',
      'About',
      'Contact',
      'Buy',
      'Rent',
      'New Development',
      'Services',
      'Valuation',
    ];

    $list_pages = get_pages();

    foreach ($list_pages as $page) {
      wp_delete_post($page->ID, true);
    }

    foreach ($pages as &$name) {
      $page = array(
        'post_title'    => $name,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type' => 'page'
      );
      $id = wp_insert_post($page);

      if ($name === 'Home') {
        // Default base home
        require_once plugin_dir_path(__DIR__) . 'api/pages/default-home.php';
        add_option('wp_sweepbright_page_' . $id, [
          'layout' => $wp_default_home
        ]);
      }
    }

    // Default base layout
    require_once plugin_dir_path(__DIR__) . 'api/pages/default-base.php';
    add_option('wp_sweepbright_page_base', [
      'layout' => $wp_default_base
    ]);
  }

  public function delete($data)
  {
    wp_delete_post($data['id'], true);
    delete_option('wp_sweepbright_page_' . $data['id']);

    $result = [
      'STATUS_CODE' => http_response_code(200),
    ];
    return rest_ensure_response($result);
  }

  public function setup($data)
  {
    $onboarding = $data['onboarding'];

    // Update description
    update_option('blogdescription', $onboarding['description']);

    // Update settings
    $data = WP_SweepBright_Helpers::settings_form();
    $data['client_id'] = $onboarding['client_id'];
    $data['client_secret'] = $onboarding['client_secret'];
    $data['recaptcha_site_key'] = $onboarding['recaptcha_site_key'];
    $data['recaptcha_secret_key'] = $onboarding['recaptcha_secret_key'];
    update_option('wp_sweepbright_settings', $data);

    // Onboarding completed
    add_option('wp_sweepbright_onboarded', true);

    // Set theme
    switch_theme('wp-sweepbright-theme');

    // Create pages
    $this->create_pages($data);

    // Set frontpage to home
    $home = get_page_by_title('Home');

    if ($home) {
      update_option('page_on_front', $home->ID);
      update_option('show_on_front', 'page');
    }

    $result = [
      'STATUS_CODE' => http_response_code(200),
    ];
    return rest_ensure_response($result);
  }


  public function list()
  {
    $list_pages = get_pages();

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'PAGES' => $list_pages,
    ];
    return rest_ensure_response($result);
  }

  public static function get($component, $data)
  {
    $output = [];
    $lang = WP_SweepBright_Controller_Pages::current_lang();

    foreach ($component['fields'] as $field) {
      if (isset($field['sync']) && $field['sync']) {
        if (empty($data['default'][$field['id']])) {
          $val = $field['default'];
        } else {
          $val = $data['default'][$field['id']];
        }
      } else {
        if (empty($data[$lang][$field['id']])) {
          $val = $field['default'];
        } else {
          $val = $data[$lang][$field['id']];
        }
      }

      $output[$field['id']] = $val;
    }
    return $output;
  }

  public static function loadCategories()
  {
    $categories = file_get_contents(plugin_dir_path(__DIR__) . 'blocks/categories.json');
    $categories = json_decode($categories, true);
    return $categories;
  }

  public static function loadComponents($categories)
  {
    $components = [];

    foreach ($categories as $category) {
      $directories = array_diff(scandir(plugin_dir_path(__DIR__) . 'blocks/' . $category['value']), ['..', '.', '.DS_Store']);

      foreach ($directories as $directory) {
        $component_path = plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . '/' . $directory . '.json';

        if (file_exists($component_path)) {
          $component = file_get_contents($component_path);
          $component = json_decode($component, true);

          // Variants
          $variants = array_diff(scandir(plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory), ['..', '.', '.DS_Store']);
          $variant_dir = [];
          foreach ($variants as $variant) {
            if (is_dir(plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . "/" . $variant)) {
              $variant_path = plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . '/' . $variant . '/info.json';
              if (file_exists($variant_path)) {
                $variant_file = file_get_contents($variant_path);
                $variant_file = json_decode($variant_file, true);
                array_push($variant_dir, $variant_file);
              }
            }
          }

          $component['variants'] = $variant_dir;

          // Add component
          $components[] = $component;
        }
      }
    }
    return rest_ensure_response($components);
  }

  public static function data($data)
  {
    $categories = WP_SweepBright_Controller_Pages::loadCategories();
    $components = WP_SweepBright_Controller_Pages::loadComponents($categories);

    if ($data['id'] === 'base') {
      $link = '';
      $page = [
        "id" => "base",
        "title" => "Base layout",
        "updated" => false,
        "settings" => [
          "locked" => true,
          "template" => "base",
        ],
      ];
    } else if ($data['id'] === 'estate-default') {
      $link = '';
      $page = [
        "id" => "estate-default",
        "title" => "Estate - default",
        "updated" => false,
        "settings" => [
          "locked" => true,
          "template" => "estate",
        ],
      ];
    } else if ($data['id'] === 'estate-project') {
      $link = '';
      $page = [
        "id" => "estate-project",
        "title" => "Estate - project",
        "updated" => false,
        "settings" => [
          "locked" => true,
          "template" => "estate",
        ],
      ];
    } else if ($data['id'] === 'estate-unit') {
      $link = '';
      $page = [
        "id" => "estate-unit",
        "title" => "Estate - unit",
        "updated" => false,
        "settings" => [
          "locked" => true,
          "template" => "estate",
        ],
      ];
    } else if ($data['id'] === 'create') {
      $link = '';
      $page = [
        "id" => "create",
        "title" => [
          "nl" => "Untitled Page",
          "en" => "Untitled Page",
          "fr" => "Untitled Page",
        ],
        "slug" => "untitled-page",
        "updated" => get_the_modified_date('c', $data["id"]),
        "settings" => [
          "locked" => false,
          "template" => "default",
          "seo" => [
            "title" => [
              "nl" => "",
              "en" => "",
              "fr" => "",
            ],
            "description" => [
              "nl" => "",
              "en" => "",
              "fr" => "",
            ]
          ],
        ],
      ];
    } else {
      $locked = false;
      if (get_post($data["id"])->post_name === 'home') {
        $locked = true;
      }
      $link = get_the_permalink($data['id']);
      $page = [
        "id" => $data["id"],
        "title" => [
          "nl" => get_post($data["id"])->post_title,
          "en" => get_post($data["id"])->post_title,
          "fr" => get_post($data["id"])->post_title,
        ],
        "updated" => get_the_modified_date('c', $data["id"]),
        "settings" => [
          "locked" => $locked,
          "template" => "default",
          "seo" => [
            "title" => [
              "nl" => get_post($data["id"])->post_title,
              "en" => get_post($data["id"])->post_title,
              "fr" => get_post($data["id"])->post_title,
            ],
            "description" => [
              "nl" => get_bloginfo('description'),
              "en" => get_bloginfo('description'),
              "fr" => get_bloginfo('description'),
            ]
          ],
        ],
      ];

      if (isset($data['slug']) && $data['slug']) {
        $page["slug"] = $data['slug'];
      } else {
        $page["slug"] = sanitize_title($page['title']['en']);
      }
    }

    $data = get_option('wp_sweepbright_page_' . $data['id']);

    $page["layout"] = [];

    if (isset($data['layout']) && $data['layout']) {
      $page["layout"] = $data['layout'];
    }

    if (isset($data['title']) && $data['title']) {
      $page["title"] = $data['title'];
    }

    if (isset($data['settings']) && $data['settings'] && $data['settings']["template"] === "default") {
      $page["settings"]["seo"] = $data['settings']["seo"];
    }

    $base = [];
    if (get_option('wp_sweepbright_page_base')) {
      $base = get_option('wp_sweepbright_page_base');
    }

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'LANG' => WP_SweepBright_Helpers::settings_form()['default_language'],
      'LINK' => $link,
      'PAGE' => $page,
      'PAGES' => get_pages(),
      'BASE' => $base,
      'CATEGORIES' => $categories,
      'COMPONENTS' => $components,
    ];
    return rest_ensure_response($result);
  }

  public function save($data)
  {
    // Update modified date
    if ($data['page']['id'] !== 'create') {
      $id = wp_update_post([
        'ID' => $data['page']['id'],
        'post_title' => $data['page']['title']['en'],
        'post_name' => $data['page']['slug'],
      ]);
    } else {
      $id = wp_insert_post([
        'post_title' => $data['page']['title']['en'],
        'post_name' => $data['page']['slug'],
        'post_type' => 'page',
        'post_author' => 1,
        'post_status' => 'publish',
      ]);
    }

    if ($data['page']['settings']['template'] === 'default') {
      $name = $id;
    } else {
      $name = $data['page']['id'];
    }

    if (get_option('wp_sweepbright_page_' . $name)) {
      update_option('wp_sweepbright_page_' . $name, $data['page']);
    } else {
      add_option('wp_sweepbright_page_' . $name, $data['page']);
    }

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'ID' => $id,
      'UPDATED' => date('c'),
      'LINK' => get_the_permalink($id),
    ];
    return rest_ensure_response($result);
  }

  public static function theme_data()
  {
    if (get_option('wp_sweepbright_theme')) {
      $theme = get_option('wp_sweepbright_theme');
    } else {
      $theme = [
        "color_primary" => "#000",
        "color_secondary" => "#000",
        "font_primary" => [
          "name" => "Open Sans",
          "value" => "Open+Sans",
          "weights" => [300, 400, 600, 700],
        ],
        "font_secondary" => [
          "name" => "Merriweather",
          "value" => "Merriweather",
          "weights" => [300, 400, 700],
        ],
        "logo" => "https://www.qbrobotics.com/wp-content/uploads/2018/03/sample-logo-470x235.png",
        "style" => "playful",
      ];
    }

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'THEME' => $theme,
    ];
    return rest_ensure_response($result);
  }

  public function theme_save($data)
  {
    if (get_option('wp_sweepbright_theme')) {
      update_option('wp_sweepbright_theme', $data['theme']);
    } else {
      add_option('wp_sweepbright_theme', $data['theme']);
    }

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'DATA' => $data['theme'],
    ];
    return rest_ensure_response($result);
  }

  public static function get_page($data, $link, $args = false)
  {

    if ($args) {
      if ($args['single']) {
        $link = $link[0];
      }
    }

    if (empty($data["default"]) || count($data["default"]) === 0) {
      $post = new WP_Query([
        'name' => $link["slug"],
        'post_type' => 'page',
        'posts_per_page' => 1,
      ]);
    } else {
      $post = new WP_Query([
        'p' => $link["id"],
        'post_type' => 'page',
        'posts_per_page' => 1,
      ]);
    }

    if ($post->posts) {
      $post = $post->posts[0];

      // Get SweepBright info
      $lang = WP_SweepBright_Controller_Pages::current_lang();

      $page = get_option('wp_sweepbright_page_' . $post->ID);

      if (!$page) {
        $title = $post->post_title;
      } else {
        $title = $page['title'][$lang];
      }

      $result = [
        'id' => $post->ID,
        'title' => $title,
        'url' => get_the_permalink($post),
      ];
    } else {
      $result = false;
    }

    return $result;
  }

  public static function load_scripts()
  {
    $components = [];

    $output = WP_SweepBright_Controller_Pages::data(['id' => get_the_ID()]);
    $base = $output->data["BASE"];
    $page = $output->data["PAGE"];

    foreach ($base["layout"] as $layout) {
      foreach ($layout["columns"] as $column) {
        $components[$column["component"]] = $column["variant"];
      }
    }

    foreach ($page["layout"] as $layout) {
      foreach ($layout["columns"] as $column) {
        $components[$column["component"]] = $column["variant"];
      }
    }

    $categories = WP_SweepBright_Controller_Pages::loadCategories();
    foreach ($categories as $category) {
      $directories = array_diff(scandir(plugin_dir_path(__DIR__) . 'blocks/' . $category['value']), ['..', '.', '.DS_Store']);

      $directories = array_filter($directories, function ($k) use ($components) {
        return array_key_exists($k, $components);
      }, ARRAY_FILTER_USE_BOTH);

      foreach ($directories as $directory) {
        $variants = array_diff(scandir(plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory), ['..', '.', '.DS_Store']);

        $variants = array_filter($variants, function ($k) use ($components, $directory) {
          return isset($components[$directory]) && $components[$directory] == $k;
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($variants as $variant) {
          if (is_dir(plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . "/" . $variant)) {
            $script_path = plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . '/' . $variant . '/dist/main.js';
            $script_url = plugin_dir_url(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . '/' . $variant . '/dist/main.js';

            $style_path = plugin_dir_path(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . '/' . $variant . '/dist/main.css';
            $style_url = plugin_dir_url(__DIR__) . 'blocks/' . $category['value'] . '/' . $directory . '/' . $variant . '/dist/main.css';

            if (file_exists($style_path)) {
              echo '<link rel="stylesheet" href="' . $style_url . '">';
            }

            if (file_exists($script_path)) {
              echo '<script type="text/javascript" src="' . $script_url . '"></script>';
            }
          }
        }
      }
    }
  }
}
