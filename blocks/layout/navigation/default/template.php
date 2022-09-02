<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  $data['estate_id'] = get_field('estate')['id'];
  $data['is_front_page'] = is_front_page();

  // Mobile navigation data
  foreach (WP_Wrapper::get('links', $component, $args) as $link) {
    if (WP_Wrapper::page($args, $link)) {
      $data['pages'][] = [
        'label' => WP_Wrapper::page($args, $link)['title'],
        'url' => WP_Wrapper::page($args, $link)['url'],
      ];
    }
  }

  $data['favorites'] = [
    'enabled' => WP_Wrapper::setting('favorites'),
    'link' => WP_Wrapper::page($args, ['id' => url_to_postid('/favorites')])['url'],
  ];

  $data['call_to_action'] = [
    'label' => WP_Wrapper::get('button_label', $component, $args),
    'link' => WP_Wrapper::page(
      $component,
      WP_Wrapper::get('button_link', $component, $args),
      ['single' => true]
    )["url"]
  ];

  $data['multilanguage'] = [
    'current_lang' => WP_SweepBright_Controller_Pages::current_lang(),
    'enabled' => WP_Wrapper::setting('multilanguage'),
    'enabled_nl' => WP_SweepBright_Helpers::settings_form()['enabled_nl'],
    'enabled_fr' => WP_SweepBright_Helpers::settings_form()['enabled_fr'],
    'enabled_en' => WP_SweepBright_Helpers::settings_form()['enabled_en'],
  ];
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<!-- Mobile navigation -->
<div class="navigation-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>

<!-- Desktop navigation -->
<div class="hidden lg:block fixed top-0 left-0 z-30 w-full h-24 js-navigation <?php if (WP_Wrapper::has_banner()) : ?>text-white<?php endif; ?>" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
  <?php if (get_field('estate')['id']) : ?>
    <div class="absolute top-0 left-0 right-0 z-10 flex items-center w-11/12 h-full mx-auto transition-transform duration-500 transform -translate-y-full lg:w-10/12 js-navigation-estate">
      <div class="flex-1">
        <ul class="space-y-1">
          <li class="text-xl font-semibold">
            <?= mb_strimwidth(get_field('estate')['title'][WP_Wrapper::lang()], 0, 45, '...'); ?>
          </li>
          <li class="text-base">
            <?= get_field('location')['street']; ?>,
            <?= get_field('location')['postal_code']; ?>
            <?= get_field('location')['city']; ?>
          </li>
        </ul>
      </div>

      <ul class="flex items-center space-x-10">
        <li>
          <p class="text-xl font-semibold">
            <?php if (get_field('estate')['is_project']) : ?>
              <?= WP_SweepBright_Query::min_max_price(WP_Wrapper::lang(), false, true)['min']; ?>
              -
              <?= WP_SweepBright_Query::min_max_price(WP_Wrapper::lang(), false, true)['max']; ?>
            <?php else : ?>
              <?= WP_SweepBright_Query::get_the_price(WP_Wrapper::lang()); ?>
            <?php endif; ?>
          </p>
        </li>
        <?php if (WP_Wrapper::setting('favorites')) : ?>
          <div class="favorites-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
        <?php endif; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="absolute top-0 left-0 right-0 z-0 flex items-center justify-end w-11/12 h-full mx-auto transition-transform duration-500 transform lg:w-10/12 js-navigation-links">
    <?php if (is_front_page()) : ?>
      <div class="top-0 left-0 z-0 absolute w-32 h-32 justify-center inline-flex items-center bg-white <?= $args['theme']['rounded_b']; ?> js-navigation-logo js-navigation-logo-large">
        <a href="/" class="inline-block">
          <img src="<?= WP_Wrapper::get('small_logo', $component, $args); ?>" class="max-h-12">
        </a>
      </div>
    <?php endif; ?>

    <div class="absolute top-0 left-0 z-10 inline-flex items-center w-56 h-full <?php if (is_front_page()) : ?>transform -translate-y-full opacity-0 js-navigation-logo js-navigation-logo-small<?php endif; ?>">
      <a href="/" class="inline-block">
        <img src="<?= WP_Wrapper::get('large_logo', $component, $args); ?>" class="max-h-10">
      </a>
    </div>

    <ul class="flex items-center text-base font-medium lowercase">
      <?php foreach (WP_Wrapper::get('links', $component, $args) as $link) : ?>
        <?php if (WP_Wrapper::page($args, $link)) : ?>
          <li class="ml-14 first:ml-0">
            <a href="<?= WP_Wrapper::page($args, $link)['url']; ?>" class="relative inline-block text-center group">
              <p><?= WP_Wrapper::page($args, $link)['title']; ?></p>
              <div class="transition duration-200 opacity-0 group-hover:opacity-100 absolute left-0 right-0 w-2 h-2 mx-auto mt-1 rounded-full bg-secondary <?php if ($post && $post->ID === WP_Wrapper::page($args, $link)['id']) : ?>opacity-100<?php endif; ?>"></div>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php if (WP_Wrapper::get('call_to_action', $component, $args) === 'true' && WP_Wrapper::get('button_label', $component, $args)) : ?>
        <li class="ml-14">
          <?php
          $link = WP_Wrapper::page(
            $component,
            WP_Wrapper::get('button_link', $component, $args),
            ['single' => true]
          )["url"];
          ?>
          <a href="<?= $link; ?>" class="btn btn-primary">
            <?= WP_Wrapper::get('button_label', $component, $args); ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if (WP_Wrapper::setting('favorites')) : ?>
        <li class="ml-10">
          <div class="favorites-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
        </li>
      <?php endif; ?>

      <?php if (WP_SweepBright_Helpers::settings_form()['multilanguage']) : ?>
        <li class="ml-10">
          <div class="relative inline-block group">
            <span class="mr-1 font-semibold">
              <?= WP_SweepBright_Controller_Pages::current_lang(); ?>
            </span>
            <i class="fal fa-angle-down"></i>

            <ul class="absolute hidden py-1 text-sm font-semibold tracking-wide text-center uppercase bg-white rounded shadow w-14 group-hover:block text-dark">
              <?php if (WP_SweepBright_Helpers::settings_form()['enabled_nl']) : ?>
                <li>
                  <a href="<?= get_the_permalink(WP_Wrapper::ID()); ?>?lang=nl" class="block p-1 transition duration-200 hover:bg-gray-100">
                    NL
                  </a>
                </li>
              <?php endif; ?>
              <?php if (WP_SweepBright_Helpers::settings_form()['enabled_fr']) : ?>
                <li>
                  <a href="<?= get_the_permalink(WP_Wrapper::ID()); ?>?lang=fr" class="block p-1 transition duration-200 hover:bg-gray-100">
                    FR
                  </a>
                </li>
              <?php endif; ?>
              <?php if (WP_SweepBright_Helpers::settings_form()['enabled_en']) : ?>
                <li>
                  <a href="<?= get_the_permalink(WP_Wrapper::ID()); ?>?lang=en" class="block p-1 transition duration-200 hover:bg-gray-100">
                    EN
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>
