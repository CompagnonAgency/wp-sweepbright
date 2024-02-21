<?php
$has_data = false;
if (
  get_field('energy_details')['epc_value'] ||
  get_field('energy_details')['energy_dpe'] ||
  get_field('energy_details')['greenhouse_emissions'] ||
  get_field('energy_details')['co2_emissions'] ||
  get_field('heating_cooling')['central_heating'] ||
  get_field('heating_cooling')['floor_heating'] ||
  get_field('heating_cooling')['air_conditioning'] ||
  get_field('heating_cooling')['individual_heating'] ||
  get_field('features')['energy']['gas'] ||
  get_field('features')['energy']['fuel'] ||
  get_field('features')['energy']['heat_pump'] ||
  get_field('features')['energy']['electricity'] ||
  get_field('ecology')['double_glazing'] ||
  get_field('ecology')['solar_panels'] ||
  get_field('ecology')['solar_boiler'] ||
  get_field('ecology')['rainwater_harvesting'] ||
  get_field('ecology')['insulated_roof']
) {
  $has_data = true;
}
?>

<?php if ($has_data) : ?>
  <div>
    <div class="mb-8 post">
      <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
    </div>

    <?php if (WP_Wrapper::get('local_law', $component, $args) === 'be') : ?>
      <?php if (get_field('energy_details')['epc_category']) : ?>
        <div class="mb-5">
          <p class="mb-3 text-uppercase">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['epc_category']; ?>
          </p>
          <div class="text-xl font-medium">
            <div class="h-10">
              <img class="h-full" src="<?= plugin_dir_url(__FILE__); ?>/assets/images/epc_<?= WP_Wrapper::lang() === 'en' ? 'fr' : WP_Wrapper::lang() ?>/<?= strtolower(get_field('energy_details')['epc_category']); ?>.png">
            </div>
          </div>
        </div>
      <?php endif; ?>

      <?php if (get_field('energy_details')['epc_value']) : ?>
        <div class="mb-14">
          <p class="inline-block text-4xl">
            <?= get_field('energy_details')['epc_value']; ?>
          </p>
          <span class="text-uppercase">Kwh / m²</span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (WP_Wrapper::get('local_law', $component, $args) === 'fr') : ?>
      <?php
      $dpe_total = get_field('energy_details')['energy_dpe'];
      $ges_total = get_field('energy_details')['greenhouse_emissions'];

      switch ($dpe_total) {
        case 'A':
          $dpe_offset = '';
          break;
        case 'B':
          $dpe_offset = 'margin-top:40px;';
          break;
        case 'C':
          $dpe_offset = 'margin-top:78px;';
          break;
        case 'D':
          $dpe_offset = 'margin-top:115px;';
          break;
        case 'E':
          $dpe_offset = 'margin-top:152px;';
          break;
        case 'F':
          $dpe_offset = 'margin-top:190px;';
          break;
        case 'G':
          $dpe_offset = 'margin-top:228px;';
          break;
      }

      switch ($ges_total) {
        case 'A':
          $ges_offset = 'margin-top:15px;margin-left:170px;';
          break;
        case 'B':
          $ges_offset = 'margin-top:50px;margin-left:190px;';
          break;
        case 'C':
          $ges_offset = 'margin-top:90px;margin-left:220px;';
          break;
        case 'D':
          $ges_offset = 'margin-top:125px;margin-left:240px;';
          break;
        case 'E':
          $ges_offset = 'margin-top:160px;margin-left:270px;';
          break;
        case 'F':
          $ges_offset = 'margin-top:200px;margin-left:270px;';
          break;
        case 'G':
          $ges_offset = 'margin-top:240px;margin-left:270px;';
          break;
      }
      ?>
      <?php if (get_field('energy_details')['energy_dpe'] || get_field('energy_details')['greenhouse_emissions']) : ?>
        <div class="mb-14">
          <div class="flex flex-col space-y-20 lg:flex-row lg:space-y-0 lg:space-x-10">
            <?php if (get_field('energy_details')['energy_dpe']) : ?>
              <div class="lg:w-1/2">
                <p class="mb-8 font-medium">
                  <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['energy_class']; ?>
                </p>
                <div class="relative overflow-hidden overflow-x-scroll lg:overflow-x-hidden h-72">
                  <div class="absolute top-0 left-0 w-full ml-3.5 -mt-7">
                    <div class="inline-flex" style="<?= $dpe_offset; ?>">
                      <div class="w-20">
                        <p class="mb-4 ml-1" style="font-size:9px;">
                          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['consumption']; ?>
                        </p>
                        <?php if (get_field('energy_details')['epc_value']) : ?>
                          <div class="leading-none text-center">
                            <p class="font-semibold"><?= get_field('energy_details')['epc_value']; ?></p>
                            <p style="font-size:9px;">kWh/m²<?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['per_year']; ?></p>
                          </div>
                        <?php endif; ?>
                      </div>

                      <div class="w-16">
                        <?php if (get_field('energy_details')['co2_emissions']) : ?>
                          <p class="mb-4" style="font-size:9px;">
                            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['emmissions']; ?>
                          </p>
                          <div class="leading-none text-center">
                            <p class="font-semibold"><?= get_field('energy_details')['co2_emissions']; ?></p>
                            <p style="font-size:9px;">kg CO₂/m²<?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['per_year']; ?></p>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <img class="h-full" style="min-width:425px" src="<?= plugin_dir_url(__FILE__); ?>/assets/images/dpe/<?= strtolower($dpe_total); ?>.svg">
                </div>
              </div>
            <?php endif; ?>

            <?php if (get_field('energy_details')['greenhouse_emissions']) : ?>
              <div class="lg:w-1/2">
                <p class="mb-8 font-medium">
                  <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['climate_class']; ?>
                </p>
                <div class="relative overflow-hidden overflow-x-scroll lg:overflow-x-hidden h-72">
                  <?php if (get_field('energy_details')['co2_emissions']) : ?>
                    <div class="absolute top-0 left-0" style="<?= $ges_offset; ?>">
                      <div class="leading-none">
                        <p class="font-semibold"><?= get_field('energy_details')['co2_emissions']; ?></p>
                        <p class="text-sm">kg CO₂/m²<?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['per_year']; ?></p>
                      </div>
                    </div>
                  <?php endif; ?>
                  <img class="h-full" style="min-width:257px;" src="<?= plugin_dir_url(__FILE__); ?>/assets/images/ges/<?= strtolower($ges_total); ?>.svg">
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <ul class="flex flex-wrap -m-5">
      <?php if (get_field('energy_details')['report_electricity_gas']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['report_electricity_gas']; ?></p>
          <p class="text-xl font-medium">
            <?php if (get_field('energy_details')['report_electricity_gas'] === 'conform') : ?>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['conform']; ?>
            <?php else : ?>
              <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['not_conform']; ?>
            <?php endif; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('heating_cooling')['central_heating']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['central_heating']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('heating_cooling')['floor_heating']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['floor_heating']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('heating_cooling')['air_conditioning']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['airco']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('heating_cooling')['individual_heating']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['individual_heating']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('features')['energy']['gas']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['gas']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('features')['energy']['fuel']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['fuel']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('features')['energy']['heat_pump']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heat_pump']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('features')['energy']['electricity']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['electricity']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('ecology')['double_glazing']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['double_glazing']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('ecology')['solar_panels']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['solar_panels']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('ecology')['solar_boiler']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['solar_boiler']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('ecology')['rainwater_harvesting']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['rainwater_harvesting']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>

      <?php if (get_field('ecology')['insulated_roof']) : ?>
        <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
          <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['insulated_roof']; ?></p>
          <p class="text-xl font-medium">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['available']; ?>
          </p>
        </li>
      <?php endif; ?>
    </ul>
  </div>
<?php else : ?>
  <div>
    <div class="mb-8 post">
      <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
    </div>

    <p class="opacity-50"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['empty']; ?></p>
  </div>
<?php endif; ?>
