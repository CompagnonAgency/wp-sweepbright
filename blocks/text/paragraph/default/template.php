<div class="inline-block <?= WP_Wrapper::get('alignment', $component, $args); ?> <?php if (WP_Wrapper::get('reading_mode', $component, $args) === 'optimized') : ?>lg:max-w-3xl<?php endif; ?>">
  <div class="post">
    <?= WP_Wrapper::get('textarea', $component, $args); ?>
  </div>

  <?php if (WP_Wrapper::get('button', $component, $args)) : ?>
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
  <?php endif; ?>
</div>
