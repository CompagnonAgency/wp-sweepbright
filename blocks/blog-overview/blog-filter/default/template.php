<?php if (WP_Wrapper::get('category_group', $component, $args)) : ?>
  <ul class="inline-flex flex-col space-y-5 font-medium lg:space-x-10 lg:flex-row lg:space-y-0 lg:items-center">
    <li class="text-sm tracking-wider uppercase opacity-50">
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['categories']; ?>
    </li>
    <li>
      <a href="<?= strtok($_SERVER["REQUEST_URI"], '?'); ?>" class="transition duration-200 opacity-50 hover:opacity-100 pb-1 border-b-2 border-transparent <?php if (empty($_GET['category'])) : ?>opacity-100 border-secondary<?php endif; ?>">
        <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['all']; ?>
      </a>
    </li>
    <?php foreach (WP_Wrapper::get('category_group', $component, $args) as $index => $category) : ?>
      <?php $category = get_category_by_slug(WP_Wrapper::get('category', $component, $args, $category)); ?>
      <?php if ($category) : ?>
        <li>
          <a href="?category=<?= $category->slug; ?>" class="transition duration-200 opacity-50 hover:opacity-100 pb-1 border-b-2 border-transparent <?php if (isset($_GET['category']) && $_GET['category'] === $category->slug) : ?>opacity-100 border-secondary<?php endif; ?>">
            <?= $category->name; ?>
          </a>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
