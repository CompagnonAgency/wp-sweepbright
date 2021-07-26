<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  $data['estate_id'] = get_field('estate')['id'];
  $data['negotiator'] = get_field('negotiator');
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="quick-contact-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
