<div class="fixed top-0 left-0 z-30 w-full transition-all duration-200 js-navigation">
  <div class="flex items-center justify-between w-10/12 mx-auto">
    <a href="/" class="text-black bg-white <?= $args['theme']['rounded_b']; ?>">
      <img src="<?= $args['theme']['logo']; ?>" class="m-10 transition-all duration-200 max-h-14 js-navigation-logo">
    </a>

    <ul class="hidden space-x-16 font-semibold lowercase lg:flex">
      <?php foreach (WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['links'] as $link) : ?>
        <?php if (WP_SweepBright_Controller_Pages::get_page($args['column']['data'], $link)) : ?>
          <li>
            <a href="<?= WP_SweepBright_Controller_Pages::get_page($args['column']['data'], $link)['url']; ?>" class="text-center">
              <p><?= WP_SweepBright_Controller_Pages::get_page($args['column']['data'], $link)['title']; ?></p>
              <?php if ($post->ID === WP_SweepBright_Controller_Pages::get_page($args['column']['data'], $link)['id']) : ?>
                <div class="w-2 h-2 mx-auto mt-1 rounded-full bg-secondary"></div>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>

      <li>
        <a href="#" class="relative">
          <div class="absolute right-0 flex items-center justify-center w-5 h-5 -mt-1 -mr-4 text-sm font-medium text-white bg-red-500 rounded-full">
            <span>1</span>
          </div>
          <i class="fas fa-heart"></i>
        </a>
      </li>
    </ul>
  </div>
</div>
