<?php if (WP_Wrapper::get('feature_group', $component, $args)) : ?>
  <div class="flex flex-wrap lg:-m-<?= WP_Wrapper::get('gap', $component, $args); ?> lg:space-y-0 space-y-5">
    <?php foreach (WP_Wrapper::get('feature_group', $component, $args) as $index => $feature) : ?>
      <div class="w-full lg:w-1/<?= WP_Wrapper::get('features_per_row', $component, $args); ?> lg:p-<?= WP_Wrapper::get('gap', $component, $args); ?>">
        <div class="relative h-full p-10 <?= WP_Wrapper::get('background_color', $component, $args); ?> lg:p-14 <?= WP_Wrapper::get('border_radius', $component, $args); ?>">
          <?php if (WP_Wrapper::get('icon', $component, $args, $feature)) : ?>
            <div class="top-0 left-0 flex items-center justify-center p-1 mb-5 overflow-hidden bg-white border-2 rounded-full lg:absolute w-14 h-14 lg:w-20 lg:h-20 lg:-m-10 border-primary">
              <img src="<?= WP_Wrapper::get('icon', $component, $args, $feature); ?>">
            </div>
          <?php endif; ?>

          <div class="post">
            <h4><?= WP_Wrapper::get('title', $component, $args, $feature); ?></h4>
            <p><?= WP_Wrapper::get('description', $component, $args, $feature); ?></p>
          </div>
          <?php if (WP_Wrapper::get('button', $component, $args, $feature)) : ?>
            <div class="mt-3">
              <?php
              // Get the page link
              $link = WP_Wrapper::page(
                $component,
                WP_Wrapper::get('button_link', $component, $args, $feature),
                ['single' => true]
              )["url"];

              // Add URL parameters to the link if they exist
              if ($link && WP_Wrapper::get('enable_url_param', $component, $args, $feature) && WP_Wrapper::get('url_param', $component, $args, $feature)) {
                $link .= '?' . WP_Wrapper::get('url_param', $component, $args, $feature);
              }

              // Add anchor link if it exists
              if (WP_Wrapper::get('enable_url_anchor', $component, $args, $feature) && WP_Wrapper::get('anchor_link', $component, $args, $feature)) {
                $link .= '#' . WP_Wrapper::get('anchor_link', $component, $args, $feature);
              }
              ?>
              <a href="<?= $link; ?>" class="text-base font-semibold lowercase" data-scroll>
                <?= WP_Wrapper::get('button_label', $component, $args, $feature); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
