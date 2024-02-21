<?php
$virtual_tour = get_field('features')['virtual_tour_url'];

// if virtual tour contains a youtube link or shortened url, return false
if (strpos($virtual_tour, 'youtube') !== false || strpos($virtual_tour, 'youtu.be') !== false) {
  $virtual_tour = false;
}
?>

<?php if ($virtual_tour) : ?>
  <div class="relative">
    <div class="aspect-ratio-<?= WP_Wrapper::get('aspect_ratio', $component, $args); ?>"></div>
    <iframe src="<?= get_field('features')['virtual_tour_url']; ?>" class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full <?= WP_Wrapper::get('border_radius', $component, $args); ?>"></iframe>
  </div>
<?php endif; ?>
