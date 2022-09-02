<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  $data['lang'] = WP_Wrapper::lang();
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="inline-block">
  <div class="overflow-hidden transition-all duration-200 lg:max-w-2xl post js-description" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
    <?= get_field('estate')['description'][WP_Wrapper::lang()]; ?>
  </div>
</div>

<?php if (get_field('estate')['status'] === 'available' || get_field('estate')['status'] === 'prospect' || (get_field('estate')['status'] === 'under_contract' && WP_SweepBright_Helpers::setting('available_properties') === 'under_contract')) : ?>
  <?php if (WP_Wrapper::get('contact_cta', $component, $args) === 'true') : ?>
    <div class="mt-5">
      <a href="#contact-form" class="btn btn-primary" data-scroll>
        <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['contact_cta']; ?>
      </a>
    </div>
  <?php endif; ?>
<?php endif; ?>
