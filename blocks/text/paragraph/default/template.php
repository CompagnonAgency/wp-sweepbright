<div class="inline-block <?= WP_Wrapper::get('alignment', $component, $args); ?> <?php if (WP_Wrapper::get('reading_mode', $component, $args) === 'optimized') : ?>lg:max-w-3xl<?php endif; ?>">
  <div class="post">
    <?= WP_Wrapper::get('textarea', $component, $args); ?>
  </div>

  <?php if (WP_Wrapper::get('button', $component, $args)) : ?>
    <?php
    // Get the page link
    $link = WP_Wrapper::page(
      $component,
      WP_Wrapper::get('button_link', $component, $args),
      ['single' => true]
    )["url"];

    // Add URL parameters to the link if they exist
    if ($link && WP_Wrapper::get('enable_url_param', $component, $args) && WP_Wrapper::get('url_param', $component, $args)) {
      $link .= '?' . WP_Wrapper::get('url_param', $component, $args);
    }

    // Add anchor link if it exists
    if (WP_Wrapper::get('enable_url_anchor', $component, $args) && WP_Wrapper::get('anchor_link', $component, $args)) {
      $link .= '#' . WP_Wrapper::get('anchor_link', $component, $args);
    }
    ?>
    <div class="mt-7">
      <a href="<?= $link; ?>" class="btn btn-primary" data-scroll>
        <?= WP_Wrapper::get('button_label', $component, $args); ?>
      </a>
    </div>
  <?php endif; ?>
</div>
