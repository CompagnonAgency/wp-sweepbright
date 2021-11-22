<div class="overflow-hidden">
  <ul class="flex flex-wrap -m-5">
    <?php if (get_field('facilities')['bedrooms']) : ?>
      <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['bedrooms']; ?></p>
        <p class="text-xl font-medium"><?= get_field('facilities')['bedrooms']; ?></p>
      </li>
    <?php endif; ?>

    <?php if (get_field('facilities')['bathrooms']) : ?>
      <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['bathrooms']; ?></p>
        <p class="text-xl font-medium"><?= get_field('facilities')['bathrooms']; ?></p>
      </li>
    <?php endif; ?>

    <?php if (get_field('facilities')['toilets']) : ?>
      <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['toilets']; ?></p>
        <p class="text-xl font-medium"><?= get_field('facilities')['toilets']; ?></p>
      </li>
    <?php endif; ?>

    <?php if (get_field('amenities')['amenity']) : ?>
      <?php foreach (get_field('amenities')['amenity'] as $index => $amenity) : ?>
        <?php if ($amenity['amenity_type'] == 'parking') : ?>
          <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
            <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['parking']; ?></p>
            <p class="text-xl font-medium">
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
            </p>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php foreach (get_field('amenities')['amenity'] as $index => $amenity) : ?>
        <?php if ($amenity['amenity_type'] == 'fireplace') : ?>
          <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
            <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['fireplace']; ?></p>
            <p class="text-xl font-medium">
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
            </p>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php foreach (get_field('amenities')['amenity'] as $index => $amenity) : ?>
        <?php if ($amenity['amenity_type'] == 'walk_in_closet') : ?>
          <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
            <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['walk_in_closet']; ?></p>
            <p class="text-xl font-medium">
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
            </p>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>
