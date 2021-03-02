<div>
  <p class="mb-6 text-5xl font-thin lg:text-7xl font-secondary"><?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['title']; ?></p>

  <?php
  $link = WP_SweepBright_Controller_Pages::get_page(
    $component,
    WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['button_link'],
    ['single' => true]
  )["url"];
  ?>
  <a href="<?= $link; ?>" class="btn bg-primary">
    <?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['button_label']; ?>
  </a>
</div>
