<?php

/**
 * Pages.
 *
 * This file is used to markup the front-end-facing aspects of the plugin.
 *
 * @package    WP_SweepBright
 * @subpackage WP_SweepBright/admin/partials
 */

wp_enqueue_media();
?>

<script>
  <?php
  // Get component data
  $data = get_userdata(get_current_user_id())->roles;
  ?>

  // Set unique data object for Javascript
  window.wp_user_roles = <?= json_encode($data); ?>;
</script>

<div id="sweepbright-pages"></div>
