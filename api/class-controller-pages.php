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
    if (WP_SweepBright_Helpers::setting('enable_pages') == 1) {
      WP_SweepBright_Controller_Pages::detect_lang();
      WP_SweepBright_Controller_Pages::yoast_seo();
    }
  }

  public function settings()
  {
    $onboarded = false;

    if (get_option('wp_sweepbright_onboarded') && get_option('wp_sweepbright_onboarded') === '1') {
      $onboarded = true;
    }

    $data = [
      'onboarded' => $onboarded,
      'description' => get_bloginfo('description'),
      'client_id' => WP_SweepBright_Helpers::setting('client_id'),
      'client_secret' => WP_SweepBright_Helpers::setting('client_secret'),
      'recaptcha_site_key' => WP_SweepBright_Helpers::setting('recaptcha_site_key'),
      'recaptcha_secret_key' => WP_SweepBright_Helpers::setting('recaptcha_secret_key'),

      'multilanguage' => WP_SweepBright_Helpers::setting('multilanguage'),
      'default_language' => WP_SweepBright_Helpers::setting('default_language'),
      'enabled_nl' => WP_SweepBright_Helpers::setting('enabled_nl'),
      'enabled_fr' => WP_SweepBright_Helpers::setting('enabled_fr'),
      'enabled_en' => WP_SweepBright_Helpers::setting('enabled_en'),
      'favorites' => WP_SweepBright_Helpers::setting('favorites'),
      'blog' => WP_SweepBright_Helpers::setting('blog'),
      'header_code' => WP_SweepBright_Helpers::setting('header_code'),
    ];

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'DATA' => $data,
    ];
    return rest_ensure_response($result);
  }

  public function save_settings($data)
  {
    // Onboarding
    update_option('wp_sweepbright_onboarded', $data['settings']['onboarded']);

    // Settings
    $settings = WP_SweepBright_Helpers::settings_form();
    $settings['favorites'] = $data['settings']['favorites'];
    $settings['blog'] = $data['settings']['blog'];
    $settings['default_language'] = $data['settings']['default_language'];
    $settings['multilanguage'] = $data['settings']['multilanguage'];
    $settings['enabled_nl'] = $data['settings']['enabled_nl'];
    $settings['enabled_fr'] = $data['settings']['enabled_fr'];
    $settings['enabled_en'] = $data['settings']['enabled_en'];
    $settings['header_code'] = $data['settings']['header_code'];

    if (get_option('wp_sweepbright_settings')) {
      update_option('wp_sweepbright_settings', $settings);
    } else {
      add_option('wp_sweepbright_settings', $settings);
    }

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
    update_option('wp_sweepbright_onboarded', true);

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

  public static function raw_data()
  {
    $id = get_the_ID();
    if (
      get_post($id)->post_name === 'home-francais' ||
      get_post($id)->post_name === 'home-english' ||
      get_post($id)->post_name === 'home-nederlands'
    ) {
      $id = url_to_postid('home');
    }
    return get_option('wp_sweepbright_page_' . $id);
  }

  public static function yoast_seo()
  {
    if (defined('WPSEO_VERSION') && !is_admin() && strpos($_SERVER['REQUEST_URI'], '/wp-json/') === false) {
      // Meta Title
      function change_opengraph_title($title)
      {
        global $post;
        $title = get_the_title();
        $lang = WP_SweepBright_Controller_Pages::current_lang();

        if (get_post_type() === 'post') {
          $title = htmlspecialchars(wp_strip_all_tags(get_the_title()));
        }

        if (get_post_type() === 'page' || is_front_page()) {
          $title = htmlspecialchars(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['settings']['seo']['title'][$lang]));
        }

        if (($post && $post->post_type === 'sweepbright_estates')) {
          $title = htmlspecialchars(wp_strip_all_tags(get_field('estate')['title'][$lang]));
        }
        return __(get_bloginfo('name') . ' | ' . $title, 'change_textdomain');
      }
      add_filter('wpseo_opengraph_title', 'change_opengraph_title');

      // Page title
      function change_title($title)
      {
        global $post;
        $title = get_the_title();
        $lang = WP_SweepBright_Controller_Pages::current_lang();

        if (get_post_type() === 'post') {
          $title = htmlspecialchars(wp_strip_all_tags(get_the_title()));
        }

        if (get_post_type() === 'page' || is_front_page()) {
          $seo_title = htmlspecialchars(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['settings']['seo']['title'][$lang]));
          if ($seo_title) {
            $title = $seo_title;
          } else {
            $title = htmlspecialchars(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['title'][$lang]));
          }
        }

        if (($post && $post->post_type === 'sweepbright_estates')) {
          $title = htmlspecialchars(wp_strip_all_tags(get_field('estate')['title'][$lang]));
        }
        return __(get_bloginfo('name') . ' | ' .  $title, 'change_textdomain');
      }
      add_filter('wpseo_title', 'change_title');

      // Description
      function change_desc($desc = false)
      {
        global $post;
        $lang = WP_SweepBright_Controller_Pages::current_lang();

        if (get_post_type() === 'post') {
          $desc = htmlspecialchars(mb_strimwidth(wp_strip_all_tags(get_the_content()), 0, 150, '...'));
        }

        if (get_post_type() === 'page' || is_front_page()) {
          $desc = htmlspecialchars(__(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['settings']['seo']['description'][$lang], 'change_textdomain')));
        }

        if (($post && $post->post_type === 'sweepbright_estates')) {
          $desc = htmlspecialchars(mb_strimwidth(wp_strip_all_tags(get_field('estate')['description'][$lang]), 0, 150, '...'));
        }
        return strip_tags($desc);
      }
      add_filter('wpseo_opengraph_desc', 'change_desc');

      function add_desc_tag()
      {
        echo '<meta property="description" content="' . change_desc() . '">' . "\n";
      }
      add_action('wp_head', 'add_desc_tag', 10);

      // URL
      function change_url($url)
      {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      }
      add_filter('wpseo_opengraph_url', 'change_url');

      // Locale
      function change_locale($locale)
      {
        $locale = WP_SweepBright_Controller_Pages::current_lang();
        return $locale;
      }
      add_filter('wpseo_locale', 'change_locale');
    }
  }

  public static function detect_lang()
  {
    // Set cookie
    if (isset($_GET['lang']) && WP_SweepBright_Helpers::settings_form()['multilanguage']) {
      $lang_param = $_GET['lang'];
      if (in_array($lang_param, ['nl', 'fr', 'en'])) {
        $lang = $lang_param;
      }
      switch ($lang) {
        case 'nl':
          switch_to_locale('nl_NL');
          break;
        case 'fr':
          switch_to_locale('fr_FR');
          break;
        case 'en':
          switch_to_locale('en_US');
          break;
      }
      setcookie('wps_lang', $lang, time() + (86400 * 30), "/");
    }

    // Set URL based on cookie
    if (isset($_COOKIE['wps_lang']) && empty($_GET['lang']) && WP_SweepBright_Helpers::settings_form()['multilanguage']) {
      if (
        strpos($_SERVER['REQUEST_URI'], '/wp-json/') === false &&
        !is_admin()
      ) {
        $link = get_the_permalink() . '?lang=' . $_COOKIE['wps_lang'];
        foreach ($_GET as $key => $value) {
          $link .= '&' . $key . '=' . $value;
        }
        header('Location: ' . $link);
      }
    }
  }

  public static function current_lang()
  {
    $lang = WP_SweepBright_Helpers::settings_form()['default_language'];
    if (WP_SweepBright_Helpers::settings_form()['multilanguage']) {
      if (isset($_GET['lang'])) {
        $lang_param = $_GET['lang'];
        if (in_array($lang_param, ['nl', 'fr', 'en'])) {
          $lang = $lang_param;
        }
      } else if (empty($_GET['lang']) && isset($_COOKIE['wps_lang'])) {
        $lang = $_COOKIE['wps_lang'];
      }
    }

    return $lang;
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
      'Favorites',
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
        // Default home
        update_option('page_on_front', $id);
        update_option('show_on_front', 'page');
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

  public function list()
  {
    $list_pages = get_pages();
    $result = [
      'STATUS_CODE' => http_response_code(200),
      'PAGES' => $list_pages,
    ];
    return rest_ensure_response($result);
  }

  public static function get($component, $data, $group_fields = false)
  {
    $output = [];
    $lang = WP_SweepBright_Controller_Pages::current_lang();

    if (!$group_fields) {
      $fields = $component['fields'];
    } else {
      $fields = array_values(array_filter($component['fields'], function ($field) use ($group_fields) {
        return $field['id'] == $group_fields['field'];
      }, ARRAY_FILTER_USE_BOTH))[0]['subfields'];
    }

    foreach ($fields as $field) {
      if (
        isset($field['type']) && ($field['type'] == 'upload_single'
          || $field['type'] == 'upload_multiple'
          || $field['type'] == 'upload_video'
          || $field['type'] == 'page_select'
          || $field['type'] == 'page_select_multiple'
          || $field['type'] == 'group')
      ) {
        $field['sync'] = 'true';
      }

      if ($group_fields) {
        $data = $group_fields['fields']['data'];
      }

      if (isset($field['sync']) && $field['sync']) {
        if (empty($data['default'][$field['id']])) {
          if (isset($field['default'])) {
            $val = $field['default'];
          } else {
            $val = '';
          }
        } else {
          $val = $data['default'][$field['id']];
        }
      } else {
        if (empty($data[$lang][$field['id']])) {
          if (isset($field['default'])) {
            $val = $field['default'];
          } else {
            $val = '';
          }
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

  public static function sync_defaults($data, $components)
  {
    if ($data['layout']) {
      foreach ($data['layout'] as &$layout) {
        foreach ($layout['columns'] as &$column) {
          $component = array_values(array_filter($components->data, function ($k) use ($column) {
            return $k['id'] === $column['component'];
          }, ARRAY_FILTER_USE_BOTH));

          if ($component) {
            foreach ($component[0]['fields'] as $field) {
              // Only apply to default or synced fields
              if (isset($field['default']) && isset($field['sync'])) {
                // Add non-existing fields
                if (empty($column['data']['default'][$field['id']])) {
                  $column['data']['default'][$field['id']] = $field['default'];
                }

                // Sync locale values (these are hardcoded)
                if ($field['id'] === 'locale') {
                  $column['data']['default'][$field['id']] = $field['default'];
                }
              }
            }
          }
        }
      }
    }

    return $data;
  }

  public static function data($data)
  {
    if (is_404()) {
      $data['id'] = '404';
    }

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
    } else if ($data['id'] === '404') {
      $link = '/404';
      $page = [
        "id" => "404",
        "title" => "404 - Page not found",
        "updated" => false,
        "settings" => [
          "locked" => true,
          "template" => "404",
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
    } else if ($data['id'] === 'blog-post') {
      $link = '';
      $page = [
        "id" => "blog-post",
        "title" => "Blog - post",
        "updated" => false,
        "settings" => [
          "locked" => true,
          "template" => "blog",
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

      // Make the homepage locked
      if (get_post($data["id"])->post_name === 'home') {
        $locked = true;
      }

      // If Polylang is enabled, disable the clones of the homepage and use 
      // the default homepage instead
      if (
        get_post($data["id"])->post_name === 'home-francais' ||
        get_post($data["id"])->post_name === 'home-english' ||
        get_post($data["id"])->post_name === 'home-nederlands'
      ) {
        $data["id"] = url_to_postid('home');
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

      $page["slug"] = sanitize_title($page['title'][WP_SweepBright_Helpers::setting('default_language')]);
    }

    $data = WP_SweepBright_Controller_Pages::sync_defaults(get_option('wp_sweepbright_page_' . $data['id']), $components);

    $page["layout"] = [];

    if (isset($data['layout']) && $data['layout']) {
      $page["layout"] = $data['layout'];
    }

    if (isset($data['title']) && $data['title']) {
      $page["title"] = $data['title'];
    }

    if (isset($data['slug']) && $data['slug']) {
      $page["slug"] = $data['slug'];
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
    $id = $data['page']['id'];
    if ( // Don't save `shadow pages` as they do not exist within WordPress
      $data['page']['id'] !== 'base'
      && $data['page']['id'] !== 'estate-default'
      && $data['page']['id'] !== 'estate-project'
      && $data['page']['id'] !== 'estate-unit'
      && $data['page']['id'] !== 'blog-post'
      && $data['page']['id'] !== '404'
    ) {
      // Update modified date & ID
      if ($data['page']['id'] !== 'create') {
        $id = wp_update_post([
          'ID' => $data['page']['id'],
          'post_title' => $data['page']['title'][WP_SweepBright_Helpers::setting('default_language')],
          'post_name' => $data['page']['slug'],
        ]);

        if ($data['page']['slug'] === 'home') {
          // Default home
          update_option('page_on_front', $id);
          update_option('show_on_front', 'page');
        }
      } else {
        $id = wp_insert_post([
          'post_title' => $data['page']['title'][WP_SweepBright_Helpers::setting('default_language')],
          'post_name' => $data['page']['slug'],
          'post_type' => 'page',
          'post_author' => 1,
          'post_status' => 'publish',
        ]);
      }
    }

    // Set the name
    if ($data['page']['settings']['template'] === 'default') {
      $name = $id;
    } else {
      $name = $data['page']['id'];
    }

    // Store in database
    if (get_option('wp_sweepbright_page_' . $name)) {
      update_option('wp_sweepbright_page_' . $name, $data['page']);
    } else {
      add_option('wp_sweepbright_page_' . $name, $data['page']);
    }

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'ID' => $id,
      'UPDATED' => date('c'),
      'PAGE' => $data['page'],
      'LINK' => get_the_permalink($id),
    ];
    return rest_ensure_response($result);
  }

  public static function theme_data()
  {
    $defaults = [
      "color_primary" => "#1b62f8",
      "color_secondary" => "#05f4ea",

      "color_dark" => "#041b2d",
      "color_light" => "#f8f8f8",

      "color_text_dark" => "#000",
      "color_text_light" => "#ffffff",

      "button_color" => "#1b62f8",
      "button_style" => "normal",

      "form_style" => "border_thick",

      "font_primary" => [
        "name" => "Montserrat",
        "value" => "Montserrat",
        "weights" => [300, 400, 500, 600],
      ],
      "font_secondary" => [
        "name" => "Source Serif Pro",
        "value" => "Source+Serif+Pro",
        "weights" => [300, 400, 600],
      ],

      "style" => "playful",
    ];

    if (get_option('wp_sweepbright_theme')) {
      $theme = get_option('wp_sweepbright_theme');

      // Find missing defaults (e.g. if an option has been added later on)
      foreach ($defaults as $key => $default) {
        if (!array_key_exists($key, $theme)) {
          $theme[$key] = $defaults[$key];
        }
      }
    } else {
      $theme = $defaults;
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
    $result = false;
    if (count($link) > 0) {
      if ($args) {
        if ($args['single']) {
          $link = $link[0];
        }
      }

      // Find page by ID
      $post = new WP_Query([
        'p' => $link["id"],
        'post_type' => 'page',
        'posts_per_page' => 1,
      ]);

      // Fallback if e.g. no ID is set (generated multipage select)
      if (!$post->posts) {
        $post = new WP_Query([
          'name' => $link["slug"],
          'post_type' => 'page',
          'posts_per_page' => 1,
        ]);
      }

      if ($post->posts) {
        $post = $post->posts[0];

        // Get SweepBright info
        $lang = WP_SweepBright_Controller_Pages::current_lang();

        $page = get_option('wp_sweepbright_page_' . $post->ID);

        if (empty($page['title'])) {
          $title = $post->post_title;
        } else {
          $title = $page['title'][$lang];
        }

        $result = [
          'id' => $post->ID,
          'title' => $title,
          'url' => get_the_permalink($post),
        ];
      }
    }
    return $result;
  }

  public static function load_scripts()
  {
    $components = [];

    // Load the scripts from a page template
    $id = get_the_ID();

    if (get_field('estate')['id']) {
      // Load the scripts from an estate template
      if (get_field('estate')['project_id']) {
        $id = 'estate-unit';
      } else if (get_field('estate')['is_project']) {
        $id = 'estate-project';
      } else {
        $id = 'estate-default';
      }
    } else if (is_single()) {
      // Load the scripts from a blog template
      $id = 'blog-post';
    }

    $output = WP_SweepBright_Controller_Pages::data(['id' => $id]);
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
              echo '<link rel="stylesheet" href="' . $style_url . '?version=' . filemtime($style_path) . '">';
            }

            if (file_exists($script_path)) {
              echo '<script type="text/javascript" src="' . $script_url . '?version=' . filemtime($script_path) . '"></script>';
            }
          }
        }
      }
    }
  }

  public static function favorites_list()
  {
    if (isset($_COOKIE['favorites']) && json_decode($_COOKIE['favorites'], true)) {
      $cookies = json_decode($_COOKIE['favorites'], true);
    } else {
      $cookies = [];
    }

    $result = [
      'STATUS_CODE' => http_response_code(200),
      'DATA' => $cookies,
    ];
    return rest_ensure_response($result);
  }

  public static function favorites_update($data)
  {
    if (isset($_COOKIE['favorites']) && json_decode($_COOKIE['favorites'], true)) {
      $cookies = json_decode($_COOKIE['favorites'], true);
    } else {
      $cookies = [];
    }

    if ($data['action']) {
      if (!in_array($data['id'], $cookies, true)) {
        array_push($cookies, $data['id']);
      }
    } else {
      $cookies = array_values(array_filter($cookies, function ($estate) use ($data) {
        return $estate !== $data['id'];
      }, ARRAY_FILTER_USE_BOTH));
    }

    setcookie('favorites', json_encode($cookies), time() + (86400 * 365), "/"); // 86400 = 1 day

    $result = [
      'STATUS_CODE' => http_response_code(200),
    ];
    return rest_ensure_response($result);
  }
}
