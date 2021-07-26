<script>
  <?php
  // Get favorites data
  $has_favorites = false;

  if (isset($_COOKIE['favorites']) && json_decode($_COOKIE['favorites'], true)) {
    $has_favorites = true;
    $totalFavorites = count(json_decode($_COOKIE['favorites'], true));
  } else {
    $totalFavorites = 0;
  }
  ?>

  // Set data object for Javascript
  window.totalFavorites = <?= json_encode($totalFavorites); ?>;
</script>

<a href="<?= WP_Wrapper::page($args, ['id' => url_to_postid('/favorites')])['url']; ?>" class="relative" data-favorites>
  <div class="absolute right-0 items-center justify-center <?php if ($has_favorites) : ?>flex<?php else : ?>hidden<?php endif; ?> w-5 h-5 -mt-1 -mr-4 text-sm font-medium text-white bg-red-500 rounded-full" data-favorites-total>
    <span>
      <?php if ($has_favorites) : ?>
        <?= count(json_decode($_COOKIE['favorites'], true)); ?>
      <?php endif; ?>
    </span>
  </div>
  <i data-favorites-icon class="text-xl <?php if ($has_favorites) : ?>fas<?php else : ?>fal<?php endif; ?> fa-heart"></i>
</a>
