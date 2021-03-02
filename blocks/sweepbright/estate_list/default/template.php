<script>
  <?php
  // Get component data
  $data = WP_SweepBright_Controller_Pages::get($component, $args['column']['data']);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="list-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
