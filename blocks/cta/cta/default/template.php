<div class="py-20 text-white bg-gray-900 <?= $args['theme']['rounded']; ?>">
  <div class="w-10/12 mx-auto text-center">
    <p class="mb-4 text-3xl font-light lg:text-5xl font-secondary"><?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['title']; ?></p>
    <p class="max-w-lg mx-auto mb-8"><?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['slogan']; ?></p>
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
</div>
