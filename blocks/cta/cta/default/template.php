<div class="inline-block max-w-3xl">
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
  $link = WP_Wrapper::page(
    $component,
    WP_Wrapper::get('button_link', $component, $args),
    ['single' => true]
  )["url"];
  ?>
  <div class="mt-7">
    <a href="<?= $link; ?>" class="btn btn-primary">
      <?= WP_Wrapper::get('button_label', $component, $args); ?>
    </a>
  </div>
</div>
