<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="filter-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
