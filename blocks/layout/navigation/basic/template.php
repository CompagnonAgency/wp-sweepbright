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

<style>
  .js-navigation {
    transform: translate3d(0, 0, 0);
    transition-property: all;
    transition-duration: 300ms;
  }

  .js-navigation.is-hidden {
    --transform-translate-x: 0;
    --transform-translate-y: 0;
    --transform-rotate: 0;
    --transform-skew-x: 0;
    --transform-skew-y: 0;
    --transform-scale-x: 1;
    --transform-scale-y: 1;
    transform: translateX(var(--transform-translate-x)) translateY(var(--transform-translate-y)) rotate(var(--transform-rotate)) skewX(var(--transform-skew-x)) skewY(var(--transform-skew-y)) scaleX(var(--transform-scale-x)) scaleY(var(--transform-scale-y));
    --transform-translate-y: -100%;
  }

  .js-navigation-wrapper {
    transition-property: all;
    transition-duration: 300ms;
  }

  .js-navigation.is-detached .js-navigation-wrapper {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .js-navigation.is-hidden .js-navigation-wrapper {
    box-shadow: none;
  }

  .js-navigation.is-attached.is-animated {
    -webkit-animation: detach 1000ms forwards cubic-bezier(0.5, 0, 0, 1);
    animation: detach 1000ms forwards cubic-bezier(0.5, 0, 0, 1);
  }

  @-webkit-keyframes detach {
    0% {
      opacity: 0;
    }

    100% {
      opacity: 1;
    }
  }

  @keyframes detach {
    0% {
      opacity: 0;
    }

    100% {
      opacity: 1;
    }
  }
</style>

<!-- Mobile navigation -->
<div class="navigation-basic" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>

<!-- Desktop navigation -->
<div class="fixed top-0 left-0 z-30 hidden w-full lg:block js-navigation" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
  <div class="<?php if (WP_Wrapper::has_banner()) : ?>w-11/12<?php endif; ?> mx-auto">
    <div class="flex <?php if (WP_Wrapper::has_banner()) : ?>mt-5<?php endif; ?> space-x-10 text-base bg-white js-navigation-wrapper <?php if (WP_Wrapper::has_banner() && (WP_Wrapper::theme('style') === 'modern' || WP_Wrapper::theme('style') === 'playful')) : ?>rounded<?php endif; ?>">
      <div class="flex items-center px-5">
        <a href="/" class="inline-block">
          <img src="<?= WP_Wrapper::get('large_logo', $component, $args); ?>" class="max-h-12">
        </a>
      </div>

      <ul class="flex items-center flex-1 space-x-10 font-medium">
        <?php foreach (WP_Wrapper::get('links', $component, $args) as $link) : ?>
          <?php if (WP_Wrapper::page($args, $link)) : ?>
            <li class="relative flex items-center h-full">
              <a href="<?= WP_Wrapper::page($args, $link)['url']; ?>" class="inline-block text-center group">
                <p class="transition duration-200 group-hover:opacity-100 <?php if ($post && $post->ID === WP_Wrapper::page($args, $link)['id']) : ?>opacity-100<?php else : ?>opacity-60<?php endif; ?>"><?= WP_Wrapper::page($args, $link)['title']; ?></p>
                <?php if ($post && $post->ID === WP_Wrapper::page($args, $link)['id']) : ?>
                  <div class="absolute top-0 left-0 right-0 w-10 h-1 mx-auto rounded-b bg-secondary"></div>
                <?php endif; ?>
              </a>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>

      <div class="flex space-x-10">
        <?php if (WP_Wrapper::setting('favorites')) : ?>
          <div class="flex items-center h-full">
            <div class="favorites-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
          </div>
        <?php endif; ?>

        <?php if (WP_SweepBright_Helpers::settings_form()['multilanguage']) : ?>
          <div class="flex items-center h-full">
            <div class="relative inline-block group">
              <div class="transition duration-200 cursor-pointer opacity-60 group-hover:opacity-100">
                <span class="mr-1 font-semibold tracking-wide uppercase">
                  <?= WP_SweepBright_Controller_Pages::current_lang(); ?>
                </span>
                <i class="fal fa-angle-down"></i>
              </div>

              <ul class="absolute hidden py-1 text-sm font-semibold tracking-wide text-center uppercase bg-white rounded shadow w-14 group-hover:block text-dark">
                <?php if (WP_SweepBright_Helpers::settings_form()['enabled_nl']) : ?>
                  <li>
                    <a href="<?= get_the_permalink(); ?>?lang=nl" class="block p-1 transition duration-200 hover:bg-gray-100">
                      NL
                    </a>
                  </li>
                <?php endif; ?>
                <?php if (WP_SweepBright_Helpers::settings_form()['enabled_fr']) : ?>
                  <li>
                    <a href="<?= get_the_permalink(); ?>?lang=fr" class="block p-1 transition duration-200 hover:bg-gray-100">
                      FR
                    </a>
                  </li>
                <?php endif; ?>
                <?php if (WP_SweepBright_Helpers::settings_form()['enabled_en']) : ?>
                  <li>
                    <a href="<?= get_the_permalink(); ?>?lang=en" class="block p-1 transition duration-200 hover:bg-gray-100">
                      EN
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>

        <div>
          <?php
          $link = WP_Wrapper::page(
            $component,
            WP_Wrapper::get('button_link', $component, $args),
            ['single' => true]
          )["url"];
          ?>
          <a href="<?= $link; ?>" class="block px-10 <?php if (WP_Wrapper::has_banner() && (WP_Wrapper::theme('style') === 'modern' || WP_Wrapper::theme('style') === 'playful')) : ?>rounded-r<?php endif; ?> <?php if (WP_Wrapper::has_banner()) : ?>py-8<?php else : ?>py-10<?php endif; ?> rounded-none btn btn-primary shadow-none text-base">
            <?= WP_Wrapper::get('button_label', $component, $args); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
