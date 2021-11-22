<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = WP_Wrapper::get('posts_per_page', $component, $args);
$query = [
  'paged' => $paged,
  'lang' => WP_Wrapper::lang(),
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => $posts_per_page,
];
$wp_query = new WP_Query($query);
?>
<?php if ($wp_query->have_posts()) : ?>
  <div class="<?php if (!WP_Wrapper::get('grid__background_color', $component, $args)) : ?>divide-y<?php endif; ?>">
    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
      <div class="flex flex-col items-center <?php if (!WP_Wrapper::get('grid__background_color', $component, $args)) : ?>pt-10 pb-0 first:pt-0<?php else : ?>lg:px-10<?php endif; ?> mb-10 space-y-5 last:mb-0 lg:space-x-20 lg:flex-row lg:space-y-0 <?= WP_Wrapper::get('grid__background_color', $component, $args); ?> <?= WP_Wrapper::get('grid__text_color', $component, $args); ?>">
        <div class="relative w-full lg:w-4/12 overflow-hidden <?= WP_Wrapper::get('border_radius', $component, $args); ?>">
          <div class="hidden aspect-ratio-4/3 lg:block"></div>
          <div class="aspect-ratio-16/9 lg:hidden"></div>
          <?php if (get_the_post_thumbnail_url()) : ?>
            <img class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full" src="<?= get_the_post_thumbnail_url(); ?>">
          <?php else : ?>
            <div class="absolute top-0 left-0 z-10 flex items-center justify-center w-full h-full">
              <i class="text-gray-400 text-9xl fad fa-image"></i>
            </div>
            <div class="absolute top-0 left-0 z-0 w-full h-full bg-gray-300"></div>
          <?php endif; ?>
        </div>

        <div class="w-full lg:w-8/12">
          <div class="<?php if (WP_Wrapper::get('grid__background_color', $component, $args)) : ?>px-10 pb-10 pt-3 lg:px-0 lg:py-16<?php endif; ?>">
            <div class="mb-4">
              <p class="mb-1 text-3xl font-normal leading-tight font-secondary lg:text-4xl">
                <a href="<?= get_the_permalink(); ?>" class="transition duration-200 hover:opacity-75"><?= get_the_title(); ?></a>
              </p>

              <p class="text-base opacity-50">
                <?= get_the_date(); ?>
              </p>
            </div>

            <p class="opacity-60 lg:max-w-2xl">
              <?= htmlspecialchars(mb_strimwidth(wp_strip_all_tags(get_the_content()), 0, 250, '...')); ?>
            </p>

            <div class="mt-5">
              <a href="<?= get_the_permalink(); ?>" class="btn btn-primary">
                <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['read_more']; ?> →
              </a>
            </div>
          </div>
        </div>
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
