<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="glide slider-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
  <div class="glide__track" data-glide-el="track">
    <ul class="glide__slides">
      <?php foreach (WP_Wrapper::get('images', $component, $args) as $index => $slide) : ?>
        <li class="glide__slide">
          <div class="relative">
            <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
            <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>" src="<?= $slide['url']; ?>">
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
