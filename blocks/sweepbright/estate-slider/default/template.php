<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  $data['total_images'] = count(get_field('features')['images']);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<?php if (get_field('features')['images']) : ?>
  <div class="relative">
    <?php if (count(get_field('features')['images']) > 1) : ?>
      <div class="absolute bottom-0 left-0 z-10 m-5 lg:m-10">
        <a href="<?= get_field('features')['images'][0]['sizes']['large']; ?>" class="btn btn-primary btn-small glightbox" data-gallery="images">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['view_all_photos']; ?>
        </a>
      </div>
    <?php endif; ?>

    <div class="relative z-0 glide estate-slider-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
      <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
          <?php foreach (get_field('features')['images'] as $index => $slide) : ?>
            <li class="glide__slide">
              <a href="<?= $slide['sizes']['large']; ?>" class="relative block h-full group cursor-zoom-in glightbox" data-gallery="images">
                <?php if (count(get_field('features')['images']) > 1) : ?>
                  <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
                <?php else : ?>
                  <div class="aspect-ratio-21/9"></div>
                <?php endif; ?>

                <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>" src="<?= $slide['sizes']['large']; ?>">
                <div class="absolute top-0 left-0 z-10 w-full h-full transition duration-200 bg-black opacity-0 group-hover:opacity-50"></div>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
<?php endif; ?>
