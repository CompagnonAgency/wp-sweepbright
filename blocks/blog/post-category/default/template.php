<?php if (get_the_category()) : ?>
  <ul class="inline-flex flex-col space-y-5 text-base opacity-50 lg:space-x-10 lg:flex-row lg:space-y-0 lg:items-center">
    <li class="text-sm tracking-wider uppercase opacity-50">
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['categories']; ?>
    </li>
    <?php foreach (get_the_category() as $index => $category) : ?>
      <?php
      $category = get_category_by_slug($category->slug);
      $link = WP_Wrapper::page(
        $component,
        WP_Wrapper::get('parent_page', $component, $args),
        ['single' => true]
      )["url"];
      ?>
      <li>
        <?php if ($link) : ?>
          <a href="<?= $link; ?>?category=<?= $category->slug; ?>">
            <?= $category->name; ?>
          </a>
        <?php else : ?>
          <?= $category->name; ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
