<div class="absolute top-0 left-0 z-10 w-full h-screen" data-banner>
  <div class="absolute top-0 left-0 z-20 flex items-center justify-center w-full h-full">
    <div class="w-10/12 mx-auto lg:w-auto lg:mx-0">
      <p class="mx-auto font-light text-center text-white text-7xl lg:max-w-3xl"><?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['title']; ?></p>
    </div>
  </div>
  <div class="absolute top-0 left-0 z-10 w-full h-full bg-gray-900 bg-opacity-50"></div>
  <img src="<?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['image']; ?>" class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full">
</div>

<div class="js-nav-space"></div>
