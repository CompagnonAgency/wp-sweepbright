<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);

  $data['project_id'] = '';
  $data['unit_id'] = false;

  if (get_field('estate')['properties'] && count(get_field('estate')['properties']) > 0) {
    $data['project_id'] = get_the_ID();
    $data['unit_id'] = false;
  } else if (get_field('estate')['project_id']) {
    $data['project_id'] = WP_SweepBright_Helpers::get_post_ID_from_estate(get_field('estate')['project_id']);
    $data['unit_id'] = get_field('estate')['id'];
  }

  $data['project_type'] = get_field('features')['type'];
  $data['posts_per_page'] = WP_Wrapper::get('units_per_page', $component, $args);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="units-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
