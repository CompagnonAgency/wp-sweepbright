<div class="inline-block lg:max-w-3xl">
  <div class="post">
    <h2><?= WP_Wrapper::get('title', $component, $args); ?></h2>
  </div>

  <?php if (WP_Wrapper::get('slogan', $component, $args)) : ?>
    <div class="mt-6">
      <p class="inline-block max-w-lg">
        <?= WP_Wrapper::get('slogan', $component, $args); ?>
      </p>
    </div>
  <?php endif; ?>

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
</div>
