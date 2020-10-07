<?php

/**
 * Properties.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    WP_SweepBright
 * @subpackage WP_SweepBright/admin/partials
 */
?>

<script>
window.sweepbrightLanguage = '<?= WP_SweepBright_Helpers::settings_form()['default_language'] ?>';
</script>

<div id="sweepbright-properties"></div>

<?php require_once(plugin_dir_path( __FILE__ ). 'components/footer.php'); ?>
