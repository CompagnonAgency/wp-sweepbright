<div>
  <div class="mb-8 post">
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
  </div>

  <?php if (get_field('energy_details')['epc_value']) : ?>
    <div class="mb-14">
      <div class="mb-3">
        <p class="inline-block text-4xl">
          <?= get_field('energy_details')['epc_value']; ?>
        </p>
        <span class="text-uppercase">Kwh / m²</span>
      </div>

      <?php
      if (get_field('energy_details')['epc_value'] < 100) {
        $epc_color = 'bg-green-500';
      } else if (get_field('energy_details')['epc_value'] >= 100 && get_field('energy_details')['epc_value'] < 200) {
        $epc_color = 'bg-green-500';
      } else if (get_field('energy_details')['epc_value'] >= 200 && get_field('energy_details')['epc_value'] < 300) {
        $epc_color = 'bg-yellow-500';
      } else if (get_field('energy_details')['epc_value'] >= 300 && get_field('energy_details')['epc_value'] < 400) {
        $epc_color = 'bg-orange-500';
      } else if (get_field('energy_details')['epc_value'] >= 400 && get_field('energy_details')['epc_value'] < 500) {
        $epc_color = 'bg-orange-500';
      } else if (get_field('energy_details')['epc_value'] >= 500) {
        $epc_color = 'bg-red-500';
      }
      ?>
      <div class="inline-block w-40 h-4 opacity-50 <?= $epc_color; ?>"></div>
    </div>
  <?php endif; ?>

  <ul class="flex flex-wrap -m-5">
    <?php if (get_field('heating_cooling')['central_heating']) : ?>
      <li class="w-1/2 lg:w-1/<?= WP_Wrapper::get('max_rows', $component, $args); ?> p-5">
        <p class="mb-3 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['central_heating']; ?></p>
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
  </ul>
</div>
