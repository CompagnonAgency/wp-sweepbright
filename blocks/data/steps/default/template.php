<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<?php if (WP_Wrapper::get('steps', $component, $args)) : ?>
  <div class="glide steps-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
    <div class="flex justify-end mb-8 space-x-16 glide__arrows" data-glide-el="controls">
      <button class="text-4xl transition duration-200 transform focus:outline-none hover:opacity-50 active:-translate-x-1" data-glide-dir="<">
        <i class="fal fa-long-arrow-left"></i>
      </button>
      <button class="text-4xl transition duration-200 transform focus:outline-none hover:opacity-50 active:translate-x-1" data-glide-dir=">">
        <i class="fal fa-long-arrow-right"></i>
      </button>
    </div>

    <div class="glide__track" data-glide-el="track">
      <ul class="glide__slides">
        <?php foreach (WP_Wrapper::get('steps', $component, $args) as $index => $step) : ?>
          <li class="glide__slide">
            <div class="relative h-full pt-8 lg:pl-8">
              <div class="absolute top-0 left-0 flex items-center justify-center w-16 h-16 text-2xl font-medium bg-white border-2 rounded-full border-primary">
                <?= sprintf('%02d', $index + 1); ?>
              </div>

              <div class="h-full <?= WP_Wrapper::get('background_color', $component, $args); ?> p-10 lg:p-14 <?= WP_Wrapper::get('border_radius', $component, $args); ?>">
                <div class="post">
                  <h4><?= WP_Wrapper::get('title', $component, $args, $step); ?></h4>
                  <p><?= WP_Wrapper::get('description', $component, $args, $step); ?></p>
                </div>

                <?php if (WP_Wrapper::get('button', $component, $args, $step)) : ?>
                  <div class="mt-3">
                    <?php
                    $link = WP_Wrapper::page(
                      $component,
                      WP_Wrapper::get('button_link', $component, $args, $step),
                      ['single' => true]
                    )["url"];
                    ?>
                    <a href="<?= $link; ?>" class="text-base font-semibold lowercase">
                      <?= WP_Wrapper::get('button_label', $component, $args, $step); ?>
                    </a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>
