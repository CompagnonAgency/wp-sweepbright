<?php if (get_the_post_thumbnail_url()) : ?>
  <div class="relative">
    <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
    <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>" src="<?= get_the_post_thumbnail_url(); ?>">
  </div>
<?php else : ?>
  <div class="relative">
    <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
    <div class="absolute top-0 left-0 z-10 flex items-center justify-center w-full h-full">
      <i class="text-gray-400 text-9xl fad fa-image"></i>
    </div>
    <div class="absolute top-0 left-0 z-0 w-full h-full bg-gray-300"></div>
  </div>
<?php endif; ?>
