<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<style>
  .video-default .plyr__control--overlaid,
  .video-default .plyr--video .plyr__control.plyr__tab-focus,
  .video-default .plyr--video .plyr__control:hover,
  .video-default .plyr--video .plyr__control[aria-expanded="true"] {
    background-color: <?= WP_Wrapper::theme('button_color'); ?>;
  }

  .video-default .plyr--full-ui input[type="range"] {
    color: <?= WP_Wrapper::theme('button_color'); ?>;
  }
</style>

<div class="video-default overflow-hidden <?= WP_Wrapper::get('border_radius', $component, $args); ?>" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
  <video playsinline controls>
    <source src="<?= WP_Wrapper::get('video', $component, $args); ?>" type="video/mp4" />
  </video>
</div>
