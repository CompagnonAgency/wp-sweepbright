<?php
$files = [];
$offices = WP_Wrapper::get('offices_group', $component, $args);

if ($offices) {
  foreach ($offices as $key => $office) {
    if (WP_Wrapper::get('office_name', $component, $args, $office) === get_field('office')['name']) {
      $files[] = [
        'office_name' => WP_Wrapper::get('office_name', $component, $args, $office),
        'file_description' => WP_Wrapper::get('file_description', $component, $args, $office),
        'document' => WP_Wrapper::get('document', $component, $args, $office),
      ];
    }
  }
}
?>

<?php if (get_field('estate')['status'] === 'available' || get_field('estate')['status'] === 'prospect' || (get_field('estate')['status'] === 'under_contract' && WP_SweepBright_Helpers::setting('available_properties') === 'under_contract')) : ?>
  <div>
    <?php if (WP_Wrapper::get('show_heading', $component, $args) === 'true') : ?>
      <div class="mb-8 post">
        <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
      </div>
    <?php endif; ?>

    <?php if (!get_field('price')['hidden']) : ?>
      <div class="pb-5 mb-5 border-b border-black border-opacity-10">
        <ul class="space-y-4">
          <li>
            <span class="text-2xl"><?= WP_SweepBright_Query::get_the_price(WP_Wrapper::lang()); ?></span>
            <?php if (get_field('price')['buyer_percentage'] || get_field('price')['buyer_fixed_fee']) : ?>
              <span class="lowercase text-sm"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['buyer_costs_included']; ?></span>
            <?php endif; ?>

            <?php if (get_field('features')['negotiation'] === 'let') : ?>
              <span class="text-sm lowercase"> / <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['month']; ?></span>
              <?php if (WP_SweepBright_Helpers::setting('country') === 'fr') : ?>
                <span class="text-sm lowercase">
                  <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['costs_included']; ?>
                </span>
              <?php endif; ?>
            <?php endif; ?>
          </li>
          <?php if (get_field('features')['negotiation'] === 'sale') : ?>
            <?php if (get_field('price')['buyer_percentage'] || get_field('price')['buyer_fixed_fee']) : ?>
              <li class="opacity-50">
                <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['buyer_prefix']; ?>:
                <?php
                $price = get_field('price')['amount'];
                $price_exc = $price;
                $price_perc = get_field('price')['buyer_percentage'];
                $price_fixed = get_field('price')['buyer_fixed_fee'];

                if ($price_perc) {
                  $price_exc = $price / (1 + $price_perc / 100);
                } elseif ($price_fixed) {
                  $price_exc = $price - $price_fixed;
                }
                echo WP_SweepBright_Query::format_price($price_exc, WP_Wrapper::lang());
                ?>
              </li>
              <li class="opacity-50">
                <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['buyer_label']; ?>:<br>

                <?php if (get_field('price')['buyer_percentage']) : ?>
                  <?= get_field('price')['buyer_percentage']; ?>% <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['buyer_suffix']; ?>
                <?php else : ?>
                  <?= WP_SweepBright_Query::format_price(get_field('price')['buyer_fixed_fee'], WP_Wrapper::lang()); ?>
                <?php endif; ?>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (
      get_field('price')['price_property_taxes']['amount'] ||
      get_field('price')['price_vat_regime'] ||
      get_field('price')['price_yearly_budgeted_building_costs']['amount'] ||
      get_field('price')['price_recurring_costs']['amount'] ||
      get_field('property_and_land')['cadastral_income'] ||
      get_field('price')['buyer_fixed_fee'] ||
      get_field('price')['vendor_fixed_fee'] ||
      get_field('occupancy')['available_from']
    ) : ?>
      <ul class="space-y-3 text-sm tracking-wider uppercase opacity-75">
        <?php if (get_field('features')['negotiation'] === 'sale') : ?>
          <?php if (get_field('price')['buyer_percentage'] || get_field('price')['buyer_fixed_fee']) : ?>
            <li>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['buyer_label']; ?>
            </li>
          <?php endif; ?>
          <?php if (get_field('price')['vendor_percentage'] || get_field('price')['vendor_fixed_fee']) : ?>
            <li>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['vendor_label']; ?>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (get_field('price')['price_property_taxes']['amount']) : ?>
          <li>
            <strong><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['property_taxes']; ?></strong>
            <?= WP_SweepBright_Query::format_price(get_field('price')['price_property_taxes']['amount'], WP_Wrapper::lang()); ?>
          </li>
        <?php endif; ?>
        <?php if (get_field('price')['price_vat_regime']) : ?>
          <li>
            <strong><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['vat_regime']; ?></strong>
            <?= get_field('price')['price_vat_regime']; ?>%
          </li>
        <?php endif; ?>
        <?php if (get_field('price')['price_yearly_budgeted_building_costs']['amount']) : ?>
          <li>
            <strong><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['yearly_budgeted_building_costs']; ?></strong>
            <?= WP_SweepBright_Query::format_price(get_field('price')['price_yearly_budgeted_building_costs']['amount'], WP_Wrapper::lang()); ?>
          </li>
        <?php endif; ?>
        <?php if (get_field('property_and_land')['cadastral_income']) : ?>
          <li>
            <strong><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['cadastral_income']; ?></strong>
            <?= WP_SweepBright_Query::format_price(get_field('property_and_land')['cadastral_income'], WP_Wrapper::lang()); ?>
          </li>
        <?php endif; ?>
        <?php if (get_field('occupancy')['occupied'] || get_field('occupancy')['available_from']) : ?>
          <li class="inline-flex items-center">
            <i class="w-8 fad fa-calendar-alt"></i>
            <p class="flex-1">
              <?php if (get_field('occupancy')['available_from']) : ?>
                <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['from']; ?>
                <strong><?= get_field('occupancy')['available_from']; ?></strong>
              <?php else : ?>
                <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['occupied']; ?>
              <?php endif; ?>
            </p>
          </li>
        <?php endif; ?>
      </ul>
    <?php endif; ?>

    <?php if (
      get_field('price')['price_costs'][WP_Wrapper::lang()] ||
      get_field('price')['price_recurring_costs']['amount'] ||
      get_field('price')['buyer_percentage'] ||
      get_field('price')['buyer_fixed_fee']
    ) : ?>
      <div class="p-5 mt-5 bg-gray-100 post">
        <?php if (get_field('features')['negotiation'] === 'let' && WP_SweepBright_Helpers::setting('country') === 'fr') : ?>
          <div>
            <h5>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['base_price']; ?>:
              <?= WP_SweepBright_Query::format_price(get_field('price')['amount'], WP_Wrapper::lang()); ?>
            </h5>
          </div>
        <?php endif; ?>
        <?php if (get_field('price')['price_recurring_costs']['amount']) : ?>
          <div class="pt-3 mt-3 border-t border-black border-opacity-10">
            <h5>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['recurring_costs']; ?>:
              <?= WP_SweepBright_Query::format_price(get_field('price')['price_recurring_costs']['amount'], WP_Wrapper::lang()); ?>
            </h5>
          </div>
        <?php endif; ?>
        <?php if (get_field('price')['price_costs'][WP_Wrapper::lang()]) : ?>
          <div>
            <div class="inline-flex">
              <p class="flex-1 text-base opacity-50"><?= get_field('price')['price_costs'][WP_Wrapper::lang()]; ?></p>
            </div>
          </div>
        <?php endif; ?>
        <?php if ((get_field('price')['buyer_percentage'] || get_field('price')['buyer_fixed_fee']) && get_field('features')['negotiation'] === 'let') : ?>
          <div class="pt-3 mt-3 border-t border-black border-opacity-10">
            <h5>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['rental_costs']; ?>:

              <?php if (get_field('price')['buyer_percentage']) : ?>
                <?= get_field('price')['buyer_percentage']; ?>%
              <?php else : ?>
                <?= WP_SweepBright_Query::format_price(get_field('price')['buyer_fixed_fee'], WP_Wrapper::lang()); ?>
              <?php endif; ?>
            </h5>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ($files) : ?>
      <div class="mt-5">
        <div class="mb-5 post">
          <h5><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['fees']; ?></h5>
        </div>

        <ul class="space-y-4">
          <?php foreach ($files as $file) : ?>
            <li>
              <i class="mt-1 mr-3 fal fa-file-pdf"></i>
              <a href="<?= $file['document']; ?>" target="_blank" class="inline-block text-xl font-semibold hover:underline">
                <?php if ($file['file_description']) : ?>
                  <?= $file['file_description']; ?>
                <?php else : ?>
                  <?= $file['document']; ?>
                <?php endif; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>
<?php else : ?>
  <div>
    <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['unavailable']; ?>
  </div>
<?php endif; ?>
