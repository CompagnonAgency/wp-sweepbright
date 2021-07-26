<div class="<?php if (WP_Wrapper::get('background_image', $component, $args) === 'true') : ?>-mt-10 lg:-mt-8 py-64 relative text-white<?php else : ?>pt-0 lg:pt-14 pb-8<?php endif; ?>">
  <div class="<?php if (WP_Wrapper::get('background_image', $component, $args) === 'true') : ?>relative z-20 flex items-center<?php endif; ?>">
    <div class="w-11/12 mx-auto lg:w-10/12">
      <div class="inline-block max-w-4xl post">
        <h1><?= WP_Wrapper::get('title', $component, $args); ?></h1>
      </div>

      <?php if (WP_Wrapper::get('slogan', $component, $args)) : ?>
        <div class="mt-6 text-xl">
          <div class="inline-block max-w-3xl leading-relaxed post">
            <?= WP_Wrapper::get('slogan', $component, $args); ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if (WP_Wrapper::get('button', $component, $args) && WP_Wrapper::get('button_label', $component, $args) && WP_Wrapper::get('button_link', $component, $args)) : ?>
        <div class="mt-8">
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
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if (WP_Wrapper::get('background_image', $component, $args) === 'true') : ?>
    <img src="<?= WP_Wrapper::get('image', $component, $args); ?>" class="absolute top-0 left-0 object-cover <?= WP_Wrapper::get('background_position', $component, $args); ?> w-full h-full">
    <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-40"></div>
  <?php endif; ?>
</div>
