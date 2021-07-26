<div>
  <div class="mb-4 post">
    <h2>
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['negotiation'][get_field('features')['negotiation']]; ?>
    </h2>
  </div>
  <ul class="inline-flex flex-col space-y-2 lg:space-x-3 lg:divide-x lg:divide-black text-uppercase lg:space-y-0 lg:flex-row">
    <?php if (get_field('features')['sub_type']) : ?>
      <li class="lg:pl-3 first:pl-0">
        <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['sub_type'][get_field('features')['sub_type']]; ?>
      </li>
    <?php endif; ?>
    <?php if (get_field('facilities')['bedrooms']) : ?>
      <li class="lg:pl-3">
        <?= get_field('facilities')['bedrooms']; ?>
        <?php if (get_field('facilities')['bedrooms'] > 1) : ?>
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['rooms']['bedroom']['multiple']; ?>
        <?php else : ?>
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['rooms']['bedroom']['single']; ?>
        <?php endif; ?>
      </li>
    <?php endif; ?>
    <?php if (get_field('estate')['is_project']) : ?>
      <li class="lg:pl-3">
        <?= WP_SweepBright_Query::min_max_living_area(WP_Wrapper::lang())['min']; ?>
        -
        <?= WP_SweepBright_Query::min_max_living_area(WP_Wrapper::lang())['max']; ?>
      </li>
    <?php else : ?>
      <?php if (get_field('sizes')['liveable_area']['size']) : ?>
        <li class="lg:pl-3">
          <?= WP_SweepBright_Query::get_the_size(WP_Wrapper::lang(), 'liveable_area'); ?>
        </li>
      <?php endif; ?>
    <?php endif; ?>
  </ul>
  <?php if (WP_Wrapper::get('documents_cta', $component, $args) === 'true') : ?>
    <?php if (get_field('features')['documents'] || get_field('features')['plans']) : ?>
      <div class="mt-6">
        <a href="#documents" class="btn btn-primary" data-scroll>
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['documents_cta']; ?>
        </a>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
