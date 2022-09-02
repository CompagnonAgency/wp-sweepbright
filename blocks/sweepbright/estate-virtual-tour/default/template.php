<?php if (get_field('features')['virtual_tour_url']) : ?>
  <div class="relative">
    <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
    <iframe src="<?= get_field('features')['virtual_tour_url']; ?>" class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>"></iframe>
  </div>
<?php endif; ?>
