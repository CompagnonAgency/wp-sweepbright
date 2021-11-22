<?php
switch (WP_Wrapper::get('size', $component, $args)) {
  case 'heading_1':
    $tag = 'h1';
    break;
  case 'heading_2':
    $tag = 'h2';
    break;
  case 'heading_3':
    $tag = 'h3';
    break;
  case 'heading_4':
    $tag = 'h4';
    break;
}
?>

<div class="post">
  <<?= $tag; ?>>
    <?= get_the_title(); ?>
  </<?= $tag; ?>>
</div>
