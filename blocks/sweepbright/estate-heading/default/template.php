<?php
$breadcrumb = WP_Wrapper::page(
  $component,
  WP_Wrapper::get('breadcrumb_parent', $component, $args),
  ['single' => true]
);
?>
<div>
  <?php if ($breadcrumb || get_field('estate')['project_id']) : ?>
    <ul class="inline-flex mb-6 space-x-3 text-uppercase">
      <li>
        <?php if (!get_field('estate')['project_id']) : ?>
          <a href="<?= $breadcrumb["url"]; ?>" class="font-semibold">
            <?= $breadcrumb["title"]; ?>
          </a>
        <?php else : ?>
          <a href="<?= get_the_permalink(WP_SweepBright_Helpers::get_post_ID_from_estate(get_field('estate')['project_id'])); ?>" class="font-semibold">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['all_units']; ?>
          </a>
        <?php endif; ?>
      </li>
      <li class="hidden lg:block">
        |
      </li>
      <li class="hidden lg:block">
        <?= mb_strimwidth(get_field('estate')['title'][WP_Wrapper::lang()], 0, 40, '...'); ?>
      </li>
    </ul>
  <?php endif; ?>

  <div class="flex flex-col lg:items-end lg:justify-between lg:space-x-20 lg:flex-row">
    <div class="mb-5 lg:w-1/2 lg:mb-0">
      <div class="post">
        <h2><?= get_field('estate')['title'][WP_Wrapper::lang()]; ?></h2>
      </div>
    </div>

    <div class="flex flex-col lg:items-end lg:justify-between lg:space-x-20 lg:w-1/2 lg:flex-row">
      <div class="flex-1 mb-5 lg:mb-0">
        <p class="mb-2">
          <?= get_field('location')['street']; ?>,
          <?= get_field('location')['postal_code']; ?>
          <?= get_field('location')['city']; ?>
        </p>
        <p class="text-2xl font-medium">
          <?php if (get_field('estate')['is_project']) : ?>
            <?= WP_SweepBright_Query::min_max_price(WP_Wrapper::lang(), false, true)['min']; ?>
            -
            <?= WP_SweepBright_Query::min_max_price(WP_Wrapper::lang(), false, true)['max']; ?>
          <?php else : ?>
            <?= WP_SweepBright_Query::get_the_price(WP_Wrapper::lang()); ?>
          <?php endif; ?>
        </p>
      </div>

      <?php if (WP_Wrapper::get('slider_navigation', $component, $args) === 'true') : ?>
        <div class="flex space-x-12">
          <button class="text-4xl transition duration-200 transform focus:outline-none hover:opacity-50 active:-translate-x-1" data-slide-action="back">
            <i class="fal fa-long-arrow-left"></i>
          </button>
          <button class="text-4xl transition duration-200 transform focus:outline-none hover:opacity-50 active:translate-x-1" data-slide-action="next">
            <i class="fal fa-long-arrow-right"></i>
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
