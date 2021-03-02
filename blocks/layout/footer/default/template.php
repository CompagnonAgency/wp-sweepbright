<div class="py-10 bg-gray-200">
  <div class="flex flex-col w-10/12 mx-auto space-y-10 lg:flex-row lg:space-y-0">
    <div class="lg:w-6/12">
      <ul class="flex flex-col space-y-2 lowercase lg:flex-row lg:space-y-0 lg:space-x-16">
        <?php foreach (WP_SweepBright_Controller_Pages::get($component, $args['column']['data'])['links'] as $link) : ?>
          <?php if (WP_SweepBright_Controller_Pages::get_page($args['column']['data'], $link)) : ?>
            <li>
              <a href="<?= WP_SweepBright_Controller_Pages::get_page($args['column']['data'], $link)['url']; ?>">
                <?= WP_SweepBright_Controller_Pages::get_page($args['column']['data'], $link)['title']; ?>
              </a>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="lg:w-6/12 lg:text-right">
      &copy; <?= date('Y') ?> <?php bloginfo('site_name'); ?> | Built by <a href="https://compagnon.agency" target="_blank">Compagnon</a> | Powered by <a href="https://sweepbright.com" target="_blank">SweepBright</a>
    </div>
  </div>
</div>
