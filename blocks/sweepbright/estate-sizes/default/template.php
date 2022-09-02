<div>
  <div class="mb-8 post">
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
  </div>

  <ul class="flex flex-wrap -m-5">
    <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
      <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['liveable_area']; ?></p>
      <p class="text-xl font-medium">
        <?php if (get_field('sizes')['liveable_area']['size']) : ?>
          <?php if (WP_Wrapper::get('display_mode', $component, $args) === 'visual') : ?>
            <i class="mr-1 opacity-50 fad fa-home"></i>
          <?php endif; ?>
          <?= WP_SweepBright_Query::get_the_size(WP_Wrapper::lang(), 'liveable_area'); ?>
        <?php else : ?>
          -
        <?php endif; ?>
      </p>
    </li>

    <?php if (get_field('sizes')['plot_area']['size']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['plot_area']; ?></p>
        <p class="text-xl font-medium">
          <?php if (get_field('sizes')['plot_area']['size']) : ?>
            <?php if (WP_Wrapper::get('display_mode', $component, $args) === 'visual') : ?>
              <i class="mr-1 opacity-50 fad fa-expand-arrows-alt"></i>
            <?php endif; ?>
            <?= WP_SweepBright_Query::get_the_size(WP_Wrapper::lang(), 'plot_area'); ?>
          <?php else : ?>
            -
          <?php endif; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('rooms')['room'] && count(get_field('rooms')['room']) > 0) : ?>
      <?php foreach (get_field('rooms')['room'] as $room) : ?>
        <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
          <p class="mb-3 text-uppercase">
            <?php if (empty(WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][$room['type']])) : ?>
              <?= $room['size_description']; ?>
            <?php else : ?>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][$room['type']]; ?>
            <?php endif; ?>
          </p>
          <p class="text-xl font-medium">
            <?php if (WP_Wrapper::get('display_mode', $component, $args) === 'visual' && isset(WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][$room['type']])) : ?>
              <?php if ($room['type'] === 'living_room') : ?>
                <i class="mr-1 opacity-50 fad fa-couch"></i>
              <?php endif; ?>
              <?php if ($room['type'] === 'kitchen' || $room['type'] === 'kitchens') : ?>
                <i class="mr-1 opacity-50 fad fa-oven"></i>
              <?php endif; ?>
              <?php if ($room['type'] === 'bedrooms') : ?>
                <i class="mr-1 opacity-50 fad fa-bed-alt"></i>
              <?php endif; ?>
              <?php if ($room['type'] === 'bathroom') : ?>
                <i class="mr-1 opacity-50 fad fa-bath"></i>
              <?php endif; ?>
              <?php if ($room['type'] === 'shower_rooms') : ?>
                <i class="mr-1 opacity-50 fad fa-shower"></i>
              <?php endif; ?>
              <?php if ($room['type'] === 'garden') : ?>
                <i class="mr-1 opacity-50 fad fa-trees"></i>
              <?php endif; ?>
              <?php if ($room['type'] === 'terrace') : ?>
                <i class="mr-1 opacity-50 fad fa-seedling"></i>
              <?php endif; ?>
            <?php endif; ?>
            <?= WP_SweepBright_Query::format_number($room['size'], WP_Wrapper::lang()); ?><?= WP_SweepBright_Query::format_unit($room['unit']) ?>
          </p>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>
