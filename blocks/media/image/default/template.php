<div class="relative">
  <div class="aspect-ratio-4/3"></div>
  <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full <?= $args['theme']['rounded']; ?>" src="<?= WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['image']; ?>">
</div>
