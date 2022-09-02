<?php
if (WP_Wrapper::get('category_group', $component, $args)) {
  $categories = [];
  foreach (WP_Wrapper::get('category_group', $component, $args) as $category) {
    $category = get_category_by_slug(WP_Wrapper::get('category', $component, $args, $category));
    if ($category) {
      $categories[] = $category->term_id;
    }
  }

  if ($categories) {
    $categories = implode(',', $categories);
  } else {
    $categories = '';
  }
} else if (isset($_GET['category'])) {
  $category = get_category_by_slug($_GET['category']);
  if ($category) {
    $categories = $category->term_id;
  }
} else {
  $categories = '';
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = WP_Wrapper::get('posts_per_page', $component, $args);
$posts_per_row = intval(WP_Wrapper::get('posts_per_row', $component, $args));

$query = [
  'paged' => $paged,
  'lang' => WP_Wrapper::lang(),
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => $posts_per_page,
  'cat' => $categories
];
$wp_query = new WP_Query($query);
?>
<?php if ($wp_query->have_posts()) : ?>
  <div class="flex flex-col space-y-5 lg:-m-5 lg:flex-wrap lg:flex-row lg:space-y-0">
    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
      <div class="<?php if ($posts_per_row !== 1) : ?>w-full lg:w-1/<?= $posts_per_row; ?><?php else : ?>w-full<?php endif; ?> lg:p-5">
        <a href="<?= get_the_permalink(); ?>" class="relative group block h-full <?= WP_Wrapper::get('grid__background_color', $component, $args); ?> <?= WP_Wrapper::get('grid__text_color', $component, $args); ?> <?= WP_Wrapper::get('border_radius', $component, $args); ?>">
          <div class="relative overflow-hidden <?= WP_Wrapper::get('border_radius', $component, $args); ?> rounded-b-none">
            <div class="aspect-ratio-4/3"></div>
            <?php if (get_the_post_thumbnail_url()) : ?>
              <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full" src="<?= get_the_post_thumbnail_url(); ?>">
            <?php else : ?>
              <div class="absolute top-0 left-0 z-10 flex items-center justify-center w-full h-full">
                <i class="text-gray-400 text-9xl fad fa-image"></i>
              </div>
              <div class="absolute top-0 left-0 z-0 w-full h-full bg-gray-300"></div>
            <?php endif; ?>
          </div>

          <div class="p-10">
            <div class="mb-4">
              <p class="mb-1 text-xl font-semibold leading-tight font-secondary lg:text-2xl">
                <?= get_the_title(); ?>
              </p>

              <p class="text-base opacity-50">
                <?= get_the_date(); ?>
              </p>
            </div>

            <div class="mt-4">
              <p class="font-medium">
                <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['read_more']; ?> →
              </p>
            </div>
          </div>

          <div class="
            absolute
            bottom-0
            left-0
            w-0
            h-0.5
            transition-all
            duration-300
            <?= WP_Wrapper::get('grid__text_color', $component, $args); ?>
            bg-current
            opacity-20
            group-hover:w-full
          "></div>
        </a>
      </div>
    <?php endwhile; ?>
  </div>

  <?php if (WP_Wrapper::get('pagination', $component, $args) === 'enabled') : ?>
    <?php if ($wp_query->found_posts > $posts_per_page) : ?>
      <div class="mt-8 pagination">
        <?php
        $total_pages = ceil($wp_query->found_posts / $posts_per_page);
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $big = 999999999;
        echo paginate_links([
          'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
          'format' => '',
          'current' => $paged,
          'total'   => $wp_query->max_num_pages,
          'mid_size' => 2,
          'prev_text' => __('&larr; Back'),
          'next_text' => __('Next &rarr;')
        ]);
        ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
<?php else : ?>
  <div class="mx-auto my-20 text-center lg:max-w-2xl post">
    <div class="mb-5 text-6xl opacity-75">
      <i class="fad fa-newspaper"></i>
    </div>
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['empty']['title']; ?></h2>
    <p class="opacity-75">
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['empty']['description']; ?>
    </p>
  </div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
<?php wp_reset_query(); ?>
