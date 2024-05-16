<?php

use function DeliciousBrains\WPMDB\Container\DI\value;

if (WP_Wrapper::get('breadcrumb_parent', $component, $args)) {
  $breadcrumb_buy = WP_Wrapper::page(
    $component,
    WP_Wrapper::get('breadcrumb_parent', $component, $args),
    ['single' => true]
  );
}
if (WP_Wrapper::get('breadcrumb_parent_rent', $component, $args)) {
  $breadcrumb_rent = WP_Wrapper::page(
    $component,
    WP_Wrapper::get('breadcrumb_parent_rent', $component, $args),
    ['single' => true]
  );
}
if (WP_Wrapper::get('breadcrumb_parent_new', $component, $args)) {
  $breadcrumb_new = WP_Wrapper::page(
    $component,
    WP_Wrapper::get('breadcrumb_parent_new', $component, $args),
    ['single' => true]
  );
}

if (get_field('features')['negotiation'] === 'sale') {
  $breadcrumb = $breadcrumb_buy;
} else if (get_field('features')['negotiation'] === 'let') {
  $breadcrumb = $breadcrumb_rent;
} else if (get_field('estate')['project_id']) {
  $breadcrumb = $breadcrumb_new;
}
?>
<div>
  <?php if ($breadcrumb || get_field('estate')['project_id']) : ?>
    <ul class="inline-flex mb-6 space-x-3 text-uppercase">
      <li>
        <?php if (!get_field('estate')['project_id']) : ?>
          <?php
          $previous_url = $_SERVER['HTTP_REFERER'] ?? $breadcrumb['url'];
          $query_string = substr($previous_url, strpos($previous_url, '?') + 1);

          if (
            strpos($query_string, 'negotiation') >= 0 ||
            strpos($query_string, 'locations') >= 0 ||
            strpos($query_string, 'category') >= 0 ||
            strpos($query_string, 'subcategory') >= 0 ||
            strpos($query_string, 'price') >= 0 ||
            strpos($query_string, 'plot_area') >= 0 ||
            strpos($query_string, 'liveable_area') >= 0 ||
            strpos($query_string, 'sort') >= 0
          ) {
            $previous_url = $previous_url;
          } else {
            $previous_url = $breadcrumb['url'];
          }
          ?>

          <a href="<?= $previous_url; ?>" class="font-semibold">
            <i class="mr-1 far fa-long-arrow-alt-left"></i>
            <?= $breadcrumb["title"]; ?>
          </a>
        <?php else : ?>
          <a href="<?= get_the_permalink(WP_SweepBright_Helpers::get_post_ID_from_estate(get_field('estate')['project_id'])); ?>" class="font-semibold">
            <i class="mr-1 far fa-long-arrow-alt-left"></i>
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['all_units']; ?>
          </a>
        <?php endif; ?>
      </li>
      <li class="hidden lg:block">
        |
      </li>
      <li class="hidden lg:block">
        <?php if (get_field('estate')['status'] === 'available' || get_field('estate')['status'] === 'prospect' || (get_field('estate')['status'] === 'under_contract' && WP_SweepBright_Helpers::setting('available_properties') === 'under_contract')) : ?>
          <?= mb_strimwidth(get_field('estate')['title'][WP_Wrapper::lang()], 0, 40, '...'); ?>
        <?php else : ?>
          <span class="inline-block px-2 py-1 mr-1 text-xs tracking-widest text-white uppercase bg-black">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['status'][get_field('estate')['status']]; ?>
          </span>
          <?= mb_strimwidth(get_field('estate')['title'][WP_Wrapper::lang()], 0, 40, '...'); ?>
        <?php endif; ?>
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
        <?php if (get_field('estate')['status'] === 'available' || get_field('estate')['status'] === 'prospect' || (get_field('estate')['status'] === 'under_contract' && WP_SweepBright_Helpers::setting('available_properties') === 'under_contract')) : ?>
          <?php if (!get_field('location')['hidden']) : ?>
            <p class="mb-2">
              <?php if (get_field('estate')['status'] === 'available' || get_field('estate')['status'] === 'prospect') : ?>
                <?= get_field('location')['street']; ?> <?= get_field('location')['number']; ?>,
              <?php else : ?>
                <?= get_field('location')['street']; ?>,
              <?php endif; ?>
              <?= get_field('location')['postal_code']; ?>
              <?= get_field('location')['city']; ?>
            </p>
          <?php endif; ?>
          <p>
            <span class="text-2xl font-medium">
              <?php if (get_field('estate')['is_project']) : ?>
                <?= WP_SweepBright_Query::min_max_price(WP_Wrapper::lang(), false, true)['min']; ?>
                -
                <?= WP_SweepBright_Query::min_max_price(WP_Wrapper::lang(), false, true)['max']; ?>
              <?php else : ?>
                <?= WP_SweepBright_Query::get_the_price(WP_Wrapper::lang()); ?>
              <?php endif; ?>
            </span>
            <?php if (get_field('features')['negotiation'] === 'let') : ?>
              <span class="text-sm lowercase">/ <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['month']; ?></span>

              <?php if (WP_SweepBright_Helpers::setting('country') === 'fr') : ?>
                <span class="text-sm lowercase">
                  <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['costs_included']; ?>
                </span>
              <?php endif; ?>
            <?php endif; ?>
          </p>

          <?php if ((get_field('price')['buyer_percentage'] || get_field('price')['buyer_fixed_fee']) && (get_field('features')['negotiation'] === 'sale')) : ?>
            <p class="mt-2 text-sm opacity-75">
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['buyer_label']; ?>
            </p>
          <?php endif; ?>

          <?php if (get_field('features')['negotiation'] === 'sale') : ?>
            <?php if (get_field('price')['vendor_percentage'] || get_field('price')['vendor_fixed_fee']) : ?>
              <p class="mt-2 text-sm opacity-75">
                <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['vendor_label']; ?>
              </p>
            <?php endif; ?>
          <?php endif; ?>
        <?php else : ?>
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['unavailable']; ?>
        <?php endif; ?>
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
