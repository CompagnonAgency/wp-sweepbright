<?php
$details = [];
$offices = WP_Wrapper::get('offices_group', $component, $args);

if ($offices) {
  foreach ($offices as $key => $office) {
    if (WP_Wrapper::get('office_name', $component, $args, $office) === get_field('office')['name']) {
      $details[] = [
        'office_name' => WP_Wrapper::get('office_name', $component, $args, $office),
        'image' => WP_Wrapper::get('image', $component, $args, $office),
        'address' => WP_Wrapper::get('address', $component, $args, $office),
        'phone' => WP_Wrapper::get('phone', $component, $args, $office),
        'email' => WP_Wrapper::get('email', $component, $args, $office),
        'vat' => WP_Wrapper::get('vat', $component, $args, $office),
        'activity' => WP_Wrapper::get('activity', $component, $args, $office),
        'additional_information' => WP_Wrapper::get('additional_information', $component, $args, $office),
      ];
    }
  }
}
?>

<div>
  <?php if (WP_Wrapper::get('show_heading', $component, $args) === 'true') : ?>
    <div class="mb-8 post">
      <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
    </div>
  <?php endif; ?>

  <?php if ($details) : ?>
    <div class="mt-5">
      <?php foreach ($details as $detail) : ?>
        <div class="flex flex-col space-y-10 lg:flex-row lg:space-y-0 lg:space-x-10">
          <?php if ($detail['image']) : ?>
            <div class="w-full lg:w-3/12">
              <div class="relative">
                <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
                <img src="<?= $detail['image']; ?>" alt="<?= $detail['office_name']; ?>" class="absolute top-0 left-0 w-full h-full object-cover object-center <?= WP_Wrapper::get('border_radius', $component, $args); ?>" />
              </div>
            </div>
          <?php endif; ?>

          <div class="w-full lg:flex-1">
            <?php if ($detail['office_name']) : ?>
              <div class="mb-5 post">
                <h4><?= $detail['office_name']; ?></h4>
              </div>
            <?php endif; ?>

            <ul class="space-y-2 text-base">
              <?php if ($detail['address']) : ?>
                <li>
                  <i class="w-6 opacity-50 fas fa-map-marker-alt"></i>
                  <?= $detail['address']; ?>
                </li>
              <?php endif; ?>
              <?php if ($detail['phone']) : ?>
                <li>
                  <a href="tel:<?= $detail['phone']; ?>" class="font-medium">
                    <i class="w-6 opacity-50 fas fa-phone"></i>
                    <?= $detail['phone']; ?>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($detail['email']) : ?>
                <li>
                  <a href="mailto:<?= $detail['email']; ?>" class="font-medium">
                    <i class="w-6 opacity-50 fas fa-envelope"></i>
                    <?= $detail['email']; ?>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($detail['vat']) : ?>
                <li>
                  <i class="w-6 opacity-50 fas fa-file-contract"></i>
                  <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['vat']; ?>: <?= $detail['vat']; ?>
                </li>
              <?php endif; ?>
              <?php if ($detail['activity']) : ?>
                <li>
                  <i class="w-6 opacity-50 fas fa-info"></i>
                  <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['activity']; ?>: <?= $detail['activity']; ?>
                </li>
              <?php endif; ?>
            </ul>

            <?php if ($detail['additional_information']) : ?>
              <div class="mt-5">
                <p>
                  <a href="#" class="font-medium js-read-more">
                    <span class="js-txt-read-more">
                      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['legal_mentions']; ?>
                      <i class="fas fa-angle-down"></i>
                    </span>
                    <span class="js-txt-read-less" style="display:none;">
                      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['legal_mentions']; ?>
                      <i class="fas fa-angle-up"></i>
                    </span>
                  </a>
                </p>

                <div class="p-5 mt-5 bg-light post js-read-more-content" style="display:none;">
                  <?= $detail['additional_information']; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
