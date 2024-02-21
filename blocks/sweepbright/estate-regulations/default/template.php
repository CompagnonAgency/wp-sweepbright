<div>
  <div class="mb-8 post">
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
  </div>

  <ul class="flex flex-wrap -m-5">
    <?php if (get_field('property_and_land')['cadastral_income']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['cadastral_income']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_SweepBright_Query::format_price(get_field('property_and_land')['cadastral_income'], WP_Wrapper::lang()); ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('property_and_land')['flood_risk']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['flood_risk']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][get_field('property_and_land')['flood_risk']]; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['building_permit']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['building_permit']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['priority_purchase_right']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['priority_purchase_right']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['subdivision_authorisation']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['subdivision_authorisation']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['urban_planning_breach']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['urban_planning_breach']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['yes']; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['expropriation_plan']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['expropriation_plan']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['heritage_list']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heritage_list']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['pending_legal_proceedings']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['pending_legal_proceedings']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['yes']; ?>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('regulations')['urban_planning_certificate']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['urban_planning_certificate']; ?></p>
        <p class="text-xl font-medium">
          <i class="fal fa-check"></i>
        </p>
      </li>
    <?php endif; ?>

    <?php if (get_field('property_and_land')['land_use_designation']) : ?>
      <li class="<?php if (WP_Wrapper::get('max_rows', $component, $args) > 1) : ?>w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?><?php else : ?>w-full<?php endif; ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['land_use_designation']; ?></p>
        <p class="text-xl font-medium">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][get_field('property_and_land')['land_use_designation']]; ?>
        </p>
      </li>
    <?php endif; ?>
  </ul>
</div>
