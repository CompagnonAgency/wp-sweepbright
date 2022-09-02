<div>
  <div class="mb-4 post">
    <h2>
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['negotiation'][get_field('features')['negotiation']]; ?>
    </h2>
  </div>
  <?php if (WP_Wrapper::get('show_features', $component, $args) === 'true' || get_field('features')['sub_type'] || get_field('mandate')['exclusive']) : ?>
    <ul class="inline-flex flex-col space-y-2 lg:space-x-3 lg:divide-x lg:divide-black text-uppercase lg:space-y-0 lg:flex-row">
      <?php if (get_field('mandate')['exclusive']) : ?>
        <li class="lg:pl-3 first:pl-0">
          <i class="mr-0.5 text-secondary fas fa-certificate"></i>
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['mandate']; ?>
        </li>
      <?php endif; ?>

      <?php if (WP_Wrapper::get('show_features', $component, $args) === 'true') : ?>
        <?php if (get_field('features')['sub_type']) : ?>
          <li class="lg:pl-3 first:pl-0">
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['sub_type'][get_field('features')['sub_type']]; ?>
          </li>
        <?php endif; ?>

        <?php if (get_field('estate')['is_project']) : ?>
          <li class="lg:pl-3 first:pl-0">
            <?= WP_SweepBright_Query::min_max_living_area(WP_Wrapper::lang())['min']; ?>
            -
            <?= WP_SweepBright_Query::min_max_living_area(WP_Wrapper::lang())['max']; ?>
          </li>
        <?php else : ?>
          <?php if (get_field('sizes')['liveable_area']['size']) : ?>
            <li class="lg:pl-3 first:pl-0">
              <?= WP_SweepBright_Query::get_the_size(WP_Wrapper::lang(), 'liveable_area'); ?>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      <?php endif; ?>
    </ul>
  <?php endif; ?>

  <?php
  $is_rentable = false;
  $is_available = get_field('estate')['status'] === 'available' || get_field('estate')['status'] === 'prospect' || (get_field('estate')['status'] === 'under_contract' && WP_SweepBright_Helpers::setting('available_properties') === 'under_contract');

  if (WP_Wrapper::get('rentio_cta', $component, $args) === 'true' && get_field('features')['negotiation'] === 'let' && $is_available) {
    $is_rentable = true;
  }
  ?>

  <?php if (WP_Wrapper::get('documents_cta', $component, $args) === 'true' || $is_rentable) : ?>
    <div class="mt-6 space-x-5">
      <?php if (WP_Wrapper::get('rentio_cta', $component, $args) === 'true' && $is_rentable) : ?>
        <a href="https://chvc.mijnhuurprofiel.be/sweepbright/<?= get_field('estate')['id']; ?>" class="btn btn-primary" target="_blank">
          <i class="mr-1 fas fa-thumbs-up"></i>
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['rentio_cta']; ?>
        </a>
      <?php endif; ?>

      <?php if (WP_Wrapper::get('documents_cta', $component, $args) === 'true') : ?>
        <?php if (get_field('features')['documents'] || get_field('features')['plans']) : ?>
          <a href="#documents" class="btn btn-border" data-scroll>
            <i class="mr-1 fal fa-file-alt"></i>
            <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['documents_cta']; ?>
          </a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
