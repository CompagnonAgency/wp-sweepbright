<script>
  <?php
  // Get component data
  $data = WP_Wrapper::all($component, $args);
  ?>

  // Set unique data object for Javascript
  window.component_<?= str_replace('-', '_', $args['column']['id']); ?> = <?= json_encode($data); ?>;
</script>

<?php if (WP_Wrapper::get('testimonials', $component, $args)) : ?>
  <div class="glide testimonials-default" data-component="component_<?= str_replace('-', '_', $args['column']['id']); ?>">
    <div class="glide__track" data-glide-el="track">
      <ul class="glide__slides">
        <?php foreach (WP_Wrapper::get('testimonials', $component, $args) as $index => $testimonial) : ?>
          <li class="relative glide__slide">
            <div class="absolute top-0 left-0 hidden opacity-50 text-9xl text-primary font-secondary lg:block">
              &#8220;
            </div>

            <div class="inline-block lg:max-w-xl lg:pt-16 lg:pl-8 post is-large">
              <?= WP_Wrapper::get('description', $component, $args, $testimonial); ?>
            </div>

            <?php if (WP_Wrapper::get('title', $component, $args, $testimonial)) : ?>
              <p class="mt-4 text-sm lg:pl-8 lg:text-lg">
                <?= WP_Wrapper::get('title', $component, $args, $testimonial); ?>
              </p>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="mt-5 lg:ml-8 glide__bullets" data-glide-el="controls[nav]">
      <?php foreach (WP_Wrapper::get('testimonials', $component, $args) as $index => $value) : ?>
        <button class="glide__bullet" data-glide-dir="=<?= $index; ?>"></button>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
