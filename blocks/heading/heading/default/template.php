<div class="inline-block post">
  <h2><?= WP_Wrapper::get('title', $component, $args); ?></h2>
  <?php if (WP_Wrapper::get('border', $component, $args)) : ?>
    <div class="inline-block w-40 h-px mt-4 bg-gray-300"></div>
  <?php endif; ?>
</div>
