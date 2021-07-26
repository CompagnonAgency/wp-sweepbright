<div>
  <div class="mb-8 post">
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
  </div>

  <ul class="flex flex-wrap -m-5">
    <?php $count = 0; ?>
    <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
      <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['terrace']; ?></p>
      <p class="text-xl font-medium">
        <?php if (get_field('rooms')['room'] && count(get_field('rooms')['room']) > 0) : ?>
          <?php foreach (get_field('rooms')['room'] as $room) : ?>
            <?php if ($room['type'] === 'terrace') : ?>
              <?php $count++; ?>
              <?= WP_SweepBright_Query::format_number($room['size'], WP_Wrapper::lang()); ?> <?= WP_SweepBright_Query::format_unit($room['unit']) ?>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($count === 0) : ?>
          -
        <?php endif; ?>
      </p>
    </li>

    <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
      <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['liveable_area']; ?></p>
      <p class="text-xl font-medium">
        <?php if (get_field('sizes')['liveable_area']['size']) : ?>
          <?= WP_SweepBright_Query::get_the_size(WP_Wrapper::lang(), 'liveable_area'); ?>
        <?php else : ?>
          -
        <?php endif; ?>
      </p>
    </li>

    <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
      <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['plot_area']; ?></p>
      <p class="text-xl font-medium">
        <?php if (get_field('sizes')['plot_area']['size']) : ?>
          <?= WP_SweepBright_Query::get_the_size(WP_Wrapper::lang(), 'plot_area'); ?>
        <?php else : ?>
          -
        <?php endif; ?>
      </p>
    </li>
  </ul>
</div>
