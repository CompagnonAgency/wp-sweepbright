<?php if (WP_Wrapper::get('people', $component, $args)) : ?>
  <div class="flex flex-col flex-wrap space-y-5 lg:-m-5 lg:flex-row lg:space-y-0">
    <?php foreach (WP_Wrapper::get('people', $component, $args) as $index => $people) : ?>
      <div class="w-full min-w-0 lg:p-5 lg:w-1/<?= WP_Wrapper::get('members_per_row', $component, $args); ?>">
        <div class="w-7/12 <?php if (WP_Wrapper::get('alignment', $component, $args) === 'text-center') : ?>mx-auto<?php endif; ?>">
          <div class="relative mb-6">
            <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
            <img class="absolute top-0 left-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>" src="<?= WP_Wrapper::get('profile', $component, $args, $people); ?>">
          </div>
        </div>

        <div class="<?= WP_Wrapper::get('alignment', $component, $args); ?>">
          <p class="text-xl font-semibold font-secondary">
            <?= WP_Wrapper::get('first_name', $component, $args, $people); ?>
          </p>
          <p class="text-xl">
            <?= WP_Wrapper::get('last_name', $component, $args, $people); ?>
          </p>
          <?php if (WP_Wrapper::get('tagline', $component, $args, $people)) : ?>
            <p class="mt-3 text-gray-500">
              <?= WP_Wrapper::get('tagline', $component, $args, $people); ?>
            </p>
          <?php endif; ?>

          <?php if (WP_Wrapper::get('email', $component, $args, $people) || WP_Wrapper::get('phone', $component, $args, $people)) : ?>
            <ul class="inline-flex mt-4 <?php if (WP_Wrapper::get('display_mode', $component, $args, $people) !== 'icon') : ?>flex-col space-y-2<?php else : ?>space-x-7<?php endif; ?>">
              <?php if (WP_Wrapper::get('phone', $component, $args, $people)) : ?>
                <li>
                  <a href="tel:<?= WP_Wrapper::get('phone', $component, $args, $people); ?>" class="space-x-2">
                    <?php if (WP_Wrapper::get('display_mode', $component, $args, $people) === 'icon' || WP_Wrapper::get('display_mode', $component, $args, $people) === 'icon_text') : ?>
                      <i class="fas fa-phone"></i>
                    <?php endif; ?>

                    <?php if (WP_Wrapper::get('display_mode', $component, $args, $people) === 'text' || WP_Wrapper::get('display_mode', $component, $args, $people) === 'icon_text') : ?>
                      <span><?= WP_Wrapper::get('phone', $component, $args, $people); ?></span>
                    <?php endif; ?>
                  </a>
                </li>
              <?php endif; ?>
              <?php if (WP_Wrapper::get('email', $component, $args, $people)) : ?>
                <li>
                  <a href="mailto:<?= WP_Wrapper::get('email', $component, $args, $people); ?>" class="space-x-2">
                    <?php if (WP_Wrapper::get('display_mode', $component, $args, $people) === 'icon' || WP_Wrapper::get('display_mode', $component, $args, $people) === 'icon_text') : ?>
                      <i class="fas fa-envelope"></i>
                    <?php endif; ?>

                    <?php if (WP_Wrapper::get('display_mode', $component, $args, $people) === 'text' || WP_Wrapper::get('display_mode', $component, $args, $people) === 'icon_text') : ?>
                      <span><?= WP_Wrapper::get('email', $component, $args, $people); ?></span>
                    <?php endif; ?>
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
