<div>
  <?php if (get_field('negotiator')['photo']) : ?>
    <div class="inline-block w-48">
      <div class="relative">
        <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
        <img class="absolute top-0 left-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>" src="<?= get_field('negotiator')['photo']['sizes']['medium'] ?>">
      </div>
    </div>
  <?php endif; ?>

  <?php if (get_field('negotiator')['first_name'] && get_field('negotiator')['last_name']) : ?>
    <div class="mt-5">
      <p class="text-xl font-semibold font-secondary">
        <?= get_field('negotiator')['first_name']; ?>
      </p>
      <p class="text-xl">
        <?= get_field('negotiator')['last_name']; ?>
      </p>

      <?php if (get_field('negotiator')['email'] || get_field('negotiator')['phone']) : ?>
        <ul class="mt-4 space-y-2 text-base">
          <?php if (get_field('negotiator')['phone']) : ?>
            <li>
              <a href="tel:<?= get_field('negotiator')['phone']; ?>">
                <i class="w-6 fas fa-phone"></i> <?= get_field('negotiator')['phone']; ?>
              </a>
            </li>
          <?php endif; ?>
          <?php if (get_field('negotiator')['email']) : ?>
            <li>
              <a href="mailto:<?= get_field('negotiator')['email']; ?>">
                <i class="w-6 fas fa-envelope"></i> <?= get_field('negotiator')['email']; ?>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
