<div class="flex flex-col overflow-hidden lg:flex-row <?= WP_Wrapper::get('border_radius', $component, $args); ?>">
  <div class="lg:w-1/2">
    <div class="relative h-full">
      <div class="aspect-ratio-16/9 lg:hidden"></div>
      <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full" src="<?= WP_Wrapper::get('image', $component, $args); ?>">
    </div>
  </div>
  <div class="relative flex items-center p-10 lg:p-24 lg:justify-center lg:w-1/2 bg-light">
    <div class="relative z-10">
      <div class="mb-6 lg:max-w-lg post">
        <?= WP_Wrapper::get('textarea', $component, $args); ?>
      </div>

      <div>
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
    </div>

    <?php if (WP_Wrapper::get('background_pattern', $component, $args)) : ?>
      <div class="absolute right-0 z-0 h-full py-10 -mr-24 opacity-60">
        <img class="h-full" src="<?= WP_Wrapper::get('background_pattern', $component, $args); ?>">
      </div>
    <?php endif; ?>
  </div>
</div>
