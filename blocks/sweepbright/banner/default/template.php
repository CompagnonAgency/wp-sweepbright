<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);

  // Override page links
  $destination_page = WP_Wrapper::page(
    $component,
    WP_Wrapper::get('destination_page', $component, $args),
    ['single' => true]
  );
  $data['destination_page'] = $destination_page;
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<div class="absolute top-0 left-0 z-10 w-full h-screen" data-banner>
  <div class="absolute top-0 left-0 z-20 flex items-center w-full h-full">
    <div class="w-11/12 mx-auto lg:w-10/12">
      <p class="inline-block text-5xl font-light text-white lg:max-w-5xl lg:text-7xl text-shadow font-secondary">
        <?= WP_Wrapper::get('title', $component, $args); ?>
      </p>
      <div class="mt-16">
        <div class="banner-default-search" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>"></div>
      </div>
    </div>
  </div>

  <img src="<?= WP_Wrapper::get('image', $component, $args); ?>" class="absolute top-0 left-0 object-cover object-center w-full h-full">
  <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-40"></div>
</div>

<div class="h-screen"></div>
