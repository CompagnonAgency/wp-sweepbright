<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<?php if (get_field('features')['video_url']) : ?>
  <style>
    .youtube-default .plyr__control--overlaid,
    .youtube-default .plyr--video .plyr__control.plyr__tab-focus,
    .youtube-default .plyr--video .plyr__control:hover,
    .youtube-default .plyr--video .plyr__control[aria-expanded="true"] {
      background-color: <?= WP_Wrapper::theme('button_color'); ?>;
    }

    .youtube-default .plyr--full-ui input[type="range"] {
      color: <?= WP_Wrapper::theme('button_color'); ?>;
    }
  </style>

  <div class="youtube-default overflow-hidden <?= WP_Wrapper::get('border_radius', $component, $args); ?>" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
    <div class="relative plyr__video-embed">
      <iframe class="absolute top-0 left-0 z-10 w-full h-full" src="<?= get_field('features')['video_url']; ?>" allowfullscreen allowtransparency allow="autoplay"></iframe>
    </div>
  </div>
<?php endif; ?>
