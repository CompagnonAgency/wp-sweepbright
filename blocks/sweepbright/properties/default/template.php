<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  $data['favorites'] = WP_Wrapper::setting('favorites');
  $data['button_url'] = WP_Wrapper::page(
    $component,
    WP_Wrapper::get('button_link', $component, $args),
    ['single' => true]
  )["url"];
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="list-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
