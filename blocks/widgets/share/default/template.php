<?php
$lang = WP_SweepBright_Controller_Pages::current_lang();
$title = '';
$description = '';

// Get the meta data based on the post type
if (get_post_type() === 'post') {
  $title = wp_strip_all_tags(get_the_title());
  $description = htmlspecialchars(mb_strimwidth(wp_strip_all_tags(get_the_content()), 0, 150, '...'));
} else if (get_post_type() === 'page' || is_front_page()) {
  $title = htmlspecialchars(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['title'][$lang]));
  $description = htmlspecialchars(__(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['settings']['seo']['description'][$lang]), 'change_textdomain'));
} else if (($post && $post->post_type === 'sweepbright_estates')) {
  $title = wp_strip_all_tags(get_field('estate')['title'][$lang]);
  $description = mb_strimwidth(wp_strip_all_tags(get_field('estate')['description'][$lang]), 0, 150, '...');
}
?>

<ul class="flex space-x-5 text-2xl text-primary">
  <?php if (WP_Wrapper::get('facebook', $component, $args)) : ?>
    <li>
      <a data-sharer="facebook" data-title="<?= $title; ?>" data-description="<?= $description; ?>" data-url="<?= get_the_permalink(); ?>" href="#" class="transition duration-200 opacity-100 hover:opacity-60">
        <i class="fab fa-facebook-f"></i>
      </a>
    </li>
  <?php endif; ?>

  <?php if (WP_Wrapper::get('twitter', $component, $args)) : ?>
    <li>
      <a data-sharer="twitter" data-title="<?= $title; ?>" data-description="<?= $description; ?>" data-url="<?= get_the_permalink(); ?>" href="#" class="transition duration-200 opacity-100 hover:opacity-60">
        <i class="fab fa-twitter"></i>
      </a>
    </li>
  <?php endif; ?>

  <?php if (WP_Wrapper::get('whatsapp', $component, $args)) : ?>
    <li>
      <a data-sharer="whatsapp" data-title="<?= $title; ?>" data-description="<?= $description; ?>" data-url="<?= get_the_permalink(); ?>" href="#" class="transition duration-200 opacity-100 hover:opacity-60">
        <i class="fab fa-whatsapp"></i>
      </a>
    </li>
  <?php endif; ?>

  <?php if (WP_Wrapper::get('pinterest', $component, $args)) : ?>
    <li>
      <a data-sharer="pinterest" data-title="<?= $title; ?>" data-description="<?= $description; ?>" data-url="<?= get_the_permalink(); ?>" href="#" class="transition duration-200 opacity-100 hover:opacity-60">
        <i class="fab fa-pinterest"></i>
      </a>
    </li>
  <?php endif; ?>

  <?php if (WP_Wrapper::get('email', $component, $args)) : ?>
    <li>
      <a data-sharer="email" data-title="<?= $title; ?>" data-subject="<?= $title; ?>" data-url="<?= get_the_permalink(); ?>" href="#" class="transition duration-200 opacity-100 hover:opacity-60">
        <i class="fas fa-envelope"></i>
      </a>
    </li>
  <?php endif; ?>

  <?php if (WP_Wrapper::get('linkedin', $component, $args)) : ?>
    <li>
      <a data-sharer="linkedin" data-title="<?= $title; ?>" data-description="<?= $description; ?>" data-url="<?= get_the_permalink(); ?>" href="#" class="transition duration-200 opacity-100 hover:opacity-60">
        <i class="fab fa-linkedin"></i>
      </a>
    </li>
  <?php endif; ?>
</ul>
