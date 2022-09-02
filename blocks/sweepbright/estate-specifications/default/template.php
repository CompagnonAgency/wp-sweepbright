<div>
  <div class="mb-8 post">
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
  </div>

  <ul class="flex flex-wrap -m-5">
    <?php if (get_field('conditions')['general_condition']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['general_condition']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][get_field('conditions')['general_condition']]; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('conditions')['bathroom_condition']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['bathroom_condition']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][get_field('conditions')['bathroom_condition']]; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('conditions')['kitchen_condition']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['kitchen_condition']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][get_field('conditions')['kitchen_condition']]; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('building')['construction']['year']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['year_built']; ?></p>
        <p class="text-xl font-medium">
          <?= get_field('building')['construction']['year']; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('building')['renovation']['year']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['year_renovated']; ?></p>
        <p class="text-xl font-medium">
          <?= get_field('building')['renovation']['year']; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['home_automation']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['home_automation']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['water_softener']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['water_softener']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['fireplace']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['fireplace']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['walk_in_closet']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['walk_in_closet']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['home_cinema']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['home_cinema']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['wine_cellar']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['wine_cellar']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['sauna']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['sauna']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['fitness_room']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['fitness_room']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('comfort')['furnished']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['furnished']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('security')['alarm']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['alarm']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('security')['concierge']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['concierge']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('security')['video_surveillance']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['video_surveillance']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('orientation')['garden_orientation']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['garden_orientation']; ?>
        </p>
        <p class="text-xl font-medium">
          <i class="mr-1 opacity-50 fad fa-compass"></i>
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]["orientation_" . get_field('orientation')['garden_orientation']]; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('amenities')['amenity'] && count(get_field('amenities')['amenity']) > 0) : ?>
      <?php foreach (get_field('amenities')['amenity'] as $amenity) : ?>
        <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
          <p class="mb-3 text-uppercase">
            <?php if (empty(WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][$amenity['amenity_type']])) : ?>
              <?= $amenity['amenity_type']; ?>
            <?php else : ?>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][$amenity['amenity_type']]; ?>
            <?php endif; ?>
          </p>
          <p class="text-xl font-medium">
            <i class="fal fa-check"></i>
          </p>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>
