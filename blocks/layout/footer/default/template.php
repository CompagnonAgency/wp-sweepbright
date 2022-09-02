<div class="flex flex-col w-11/12 mx-auto lg:space-x-20 lg:w-10/12 lg:flex-row">
  <div class="items-start hidden w-1/4 lg:flex">
    <a href="/" class="inline-block">
      <img src="<?= WP_Wrapper::get('small_logo', $component, $args); ?>" class="max-h-20">
    </a>
  </div>

  <?php if (WP_Wrapper::get('sitemap', $component, $args)) : ?>
    <div class="mb-8 lg:w-1/4 lg:mb-0">
      <div class="mb-2 lg:mb-6 post">
        <h5 class="text-primary">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['sitemap']; ?>
        </h5>
      </div>

      <ul class="space-y-2 text-base lowercase lg:space-y-4">
        <?php foreach (WP_Wrapper::get('sitemap', $component, $args) as $link) : ?>
          <?php if (WP_Wrapper::page($args, $link)) : ?>
            <li>
              <a href="<?= WP_Wrapper::page($args, $link)['url']; ?>">
                <?= WP_Wrapper::page($args, $link)['title']; ?>
              </a>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="mb-8 lg:w-1/4 lg:mb-0">
    <div class="mb-2 lg:mb-6 post">
      <h5 class="text-primary">
        <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['contact_us']; ?>
      </h5>
    </div>

    <div class="text-base post">
      <?= WP_Wrapper::get('location', $component, $args); ?>
    </div>

    <div class="inline-block w-10 h-px mt-6 mb-4 bg-gray-300"></div>

    <div class="space-y-4 text-base">
      <?php if (WP_Wrapper::get('email', $component, $args)) : ?>
        <div>
          <i class="w-7 fas fa-envelope"></i>
          <a href="mailto:<?= WP_Wrapper::get('email', $component, $args); ?>">
            <?= WP_Wrapper::get('email', $component, $args); ?>
          </a>
        </div>
      <?php endif; ?>

      <?php if (WP_Wrapper::get('phone', $component, $args)) : ?>
        <div>
          <i class="w-7 fas fa-phone"></i>
          <a href="tel:<?= WP_Wrapper::get('phone', $component, $args); ?>">
            <?= WP_Wrapper::get('phone', $component, $args); ?>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if (WP_Wrapper::get('facebook', $component, $args) || WP_Wrapper::get('instagram', $component, $args)) : ?>
    <div class="lg:w-1/4">
      <div class="mb-2 lg:mb-6 post">
        <h5 class="text-primary">
          <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['follow']; ?>
          <?= get_bloginfo('name'); ?>
        </h5>
      </div>

      <ul class="flex space-x-6 text-2xl">
        <?php if (WP_Wrapper::get('facebook', $component, $args)) : ?>
          <li>
            <a href="<?= WP_Wrapper::get('facebook', $component, $args); ?>" target="_blank">
              <i class="fab fa-facebook-f"></i>
            </a>
          </li>
        <?php endif; ?>

        <?php if (WP_Wrapper::get('instagram', $component, $args)) : ?>
          <li>
            <a href="<?= WP_Wrapper::get('instagram', $component, $args); ?>" target="_blank">
              <i class="fab fa-instagram"></i>
            </a>
          </li>
        <?php endif; ?>

        <?php if (WP_Wrapper::get('youtube', $component, $args)) : ?>
          <li>
            <a href="<?= WP_Wrapper::get('youtube', $component, $args); ?>" target="_blank">
              <i class="fab fa-youtube"></i>
            </a>
          </li>
        <?php endif; ?>

        <?php if (WP_Wrapper::get('linkedin', $component, $args)) : ?>
          <li>
            <a href="<?= WP_Wrapper::get('linkedin', $component, $args); ?>" target="_blank">
              <i class="fab fa-linkedin"></i>
            </a>
          </li>
        <?php endif; ?>

        <?php if (WP_Wrapper::get('tiktok', $component, $args)) : ?>
          <li>
            <a href="<?= WP_Wrapper::get('tiktok', $component, $args); ?>" target="_blank">
              <i class="fab fa-tiktok"></i>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>

<?php if (WP_Wrapper::get('biv_label', $component, $args) || WP_Wrapper::get('cib_label', $component, $args)) : ?>
  <div class="flex flex-col w-11/12 mx-auto mt-10 space-y-5 lg:mt-12 lg:space-x-20 lg:w-10/12 lg:flex-row lg:space-y-0">
    <?php if (WP_Wrapper::get('biv_description', $component, $args)) : ?>
      <div class="text-sm text-gray-500 lg:max-w-2xl post">
        <?= WP_Wrapper::get('biv_description', $component, $args); ?>
      </div>
    <?php endif; ?>

    <div class="flex flex-1 lg:justify-end">
      <ul class="flex space-x-16">
        <?php if (WP_Wrapper::get('cib_label', $component, $args)) : ?>
          <li>
            <a href="https://www.cib.be/" target="_blank">
              <img src="/wp-content/plugins/wp-sweepbright/public/images/cib-logo.png" class="max-h-10">
            </a>
          </li>
        <?php endif; ?>

        <?php if (WP_Wrapper::get('biv_label', $component, $args)) : ?>
          <li>
            <a href="https://www.biv.be/" target="_blank">
              <img src="/wp-content/plugins/wp-sweepbright/public/images/biv-logo.png" class="max-h-10">
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>

<div class="w-11/12 mx-auto mt-10 lg:mb-10 lg:w-10/12">
  <ul class="flex flex-col space-y-2 text-sm text-gray-500 lg:space-x-8 lg:space-y-0 lg:flex-row">
    <li>
      &copy; <?= date('Y') ?> <?php bloginfo('site_name'); ?>
    </li>
    <li class="lowercase">
      Website by <a href="https://nextfloor.immo" target="_blank" class="font-semibold">Nextfloor</a>
    </li>
    <li class="lowercase">
      Powered by <a href="https://sweepbright.com" target="_blank" class="font-semibold">SweepBright</a>
    </li>
    <?php foreach (WP_Wrapper::get('legal_pages', $component, $args) as $link) : ?>
      <?php if (WP_Wrapper::page($args, $link)) : ?>
        <li class="lowercase">
          <a href="<?= WP_Wrapper::page($args, $link)['url']; ?>" class="font-semibold">
            <?= WP_Wrapper::page($args, $link)['title']; ?>
          </a>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>
