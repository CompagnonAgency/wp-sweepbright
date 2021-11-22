<div class="relative <?php if (WP_Wrapper::get('mobile_max_width', $component, $args)) : ?>max-w-<?= WP_Wrapper::get('mobile_max_width', $component, $args); ?> lg:max-w-full<?php endif; ?>">
  <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
  <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>" src="<?= WP_Wrapper::get('image', $component, $args); ?>">
</div>
