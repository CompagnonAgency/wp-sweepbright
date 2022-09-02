<div>
  <div class="relative overflow-hidden <?= WP_Wrapper::get('border_radius', $component, $args); ?> rounded-b-none">
    <div class="aspect-ratio-4/3"></div>
    <?php if (WP_Wrapper::get('media', $component, $args) === 'image') : ?>
      <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full" src="<?= WP_Wrapper::get('image', $component, $args); ?>">
    <?php else : ?>
      <video class="absolute top-0 left-0 object-cover object-center w-full h-full border-none" autoplay muted loop>
        <source src="<?= WP_Wrapper::get('video', $component, $args); ?>" type="video/mp4" />
      </video>
    <?php endif; ?>
  </div>

  <div class="p-10">
    <?php if (WP_Wrapper::get('title', $component, $args)) : ?>
      <p class="mb-1 text-xl font-semibold leading-tight font-secondary lg:text-2xl">
        <?= WP_Wrapper::get('title', $component, $args); ?>
      </p>
    <?php endif; ?>

    <?php if (WP_Wrapper::get('textarea', $component, $args)) : ?>
      <div class="text-base opacity-50 post">
        <?= WP_Wrapper::get('textarea', $component, $args); ?>
      </div>
    <?php endif; ?>

    <?php if (WP_Wrapper::get('button', $component, $args)) : ?>
      <div class="mt-6">
        <?php
        // Get the page link
        $link = WP_Wrapper::page(
          $component,
          WP_Wrapper::get('button_link', $component, $args),
          ['single' => true]
        )["url"];

        // Add URL parameters to the link if they exist
        if ($link && WP_Wrapper::get('enable_url_param', $component, $args) && WP_Wrapper::get('url_param', $component, $args)) {
          $link .= '?' . WP_Wrapper::get('url_param', $component, $args);
        }

        // Add anchor link if it exists
        if (WP_Wrapper::get('enable_url_anchor', $component, $args) && WP_Wrapper::get('anchor_link', $component, $args)) {
          $link .= '#' . WP_Wrapper::get('anchor_link', $component, $args);
        }
        ?>
        <a href="<?= $link; ?>" class="btn btn-border" data-scroll>
          <?= WP_Wrapper::get('button_label', $component, $args); ?>
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>
