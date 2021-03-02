<script>
  <?php
  // Get component data
  $data = WP_SweepBright_Controller_Pages::get($component, $args['column']['data']);

  // Override page links
  $destination_page = WP_SweepBright_Controller_Pages::get_page(
    $component,
    WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['destination_page'],
    ['single' => true]
  );
  $data['destination_page'] = $destination_page;
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="absolute top-0 left-0 z-10 w-full h-screen" data-banner>
  <div class="absolute top-0 left-0 z-20 flex items-center justify-center w-full h-full">
    <div class="w-10/12 mx-auto">
      <p class="mx-auto text-5xl font-light text-center text-white lg:max-w-5xl lg:text-7xl text-shadow"><?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['title']; ?></p>
      <div class="banner-default-search" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
    </div>
  </div>
  <div class="absolute top-0 left-0 z-10 w-full h-full bg-gray-900 bg-opacity-60"></div>
  <img src="<?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['image']; ?>" class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full">
</div>

<div class="js-nav-space"></div>
