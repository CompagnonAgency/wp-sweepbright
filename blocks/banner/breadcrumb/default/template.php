<?php
// Get the current title
$lang = WP_SweepBright_Controller_Pages::current_lang();

if (get_post_type() === 'post') {
  $title = htmlspecialchars(wp_strip_all_tags(get_the_title()));
}

if (get_post_type() === 'page' || is_front_page()) {
  $seo_title = htmlspecialchars(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['settings']['seo']['title'][$lang]));
  if ($seo_title) {
    $title = $seo_title;
  } else {
    $title = htmlspecialchars(wp_strip_all_tags(WP_SweepBright_Controller_Pages::raw_data()['title'][$lang]));
  }
}

if (($post && $post->post_type === 'sweepbright_estates')) {
  $title = htmlspecialchars(wp_strip_all_tags(get_field('estate')['title'][$lang]));
}
$title = mb_strimwidth($title, 0, 15, '...');
?>

<?php if (WP_Wrapper::get('links', $component, $args)) : ?>
  <ul class="flex space-x-3 text-base font-medium lg:items-center">
    <?php foreach (WP_Wrapper::get('links', $component, $args) as $link) : ?>
      <?php if (WP_Wrapper::page($args, $link)) : ?>
        <li class="opacity-50">
          <a href="<?= WP_Wrapper::page($args, $link)['url']; ?>">
            <?= WP_Wrapper::page($args, $link)['title']; ?>
          </a>
        </li>
        <li class="text-gray-400">
          <i class="fas fa-angle-right"></i>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>

    <li>
      <a href="<?= get_the_permalink(); ?>">
        <?= $title; ?>
      </a>
    </li>
  </ul>
<?php endif; ?>
