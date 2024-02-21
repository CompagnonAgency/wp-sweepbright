<iframe id="fisher-widget"></iframe>
<script src="https://fisher-v2.pricehubble.com/constructor/widget.js"></script>

<?php
$active_color = '#' . WP_Wrapper::get('active_color', $component, $args);
list($r, $g, $b) = sscanf($active_color, "#%02x%02x%02x");
?>

<script type="text/javascript">
  FisherWidget.init({
    apiKey: '<?= WP_Wrapper::get('api_key', $component, $args); ?>',
    iframe: '#fisher-widget',
    textColor: '<?= WP_Wrapper::get('text_color', $component, $args); ?>',
    activeColor: '<?= WP_Wrapper::get('active_color', $component, $args); ?>',
    buttonColor: '<?= WP_Wrapper::get('button_color', $component, $args); ?>',
    primaryColor: '<?= "$r,$g,$b"; ?>',
    lang: '<?= WP_Wrapper::get('language', $component, $args); ?>',
    gaTrackingId: '<?= WP_Wrapper::get('ga_id', $component, $args); ?>',
    gtmId: '<?= WP_Wrapper::get('gtm_id', $component, $args); ?>',
    resizable: true,
    scrollTopOffset: <?= WP_Wrapper::get('scroll_top_offset', $component, $args) ? WP_Wrapper::get('scroll_top_offset', $component, $args) : 0; ?>,
    titleSettings: {
      status: 'default',
    },
    consentGranted: true,
    host: 'https://fisher-v2.pricehubble.com',
  });
</script>

<style>
  iframe#fisher-widget {
    width: 100%;
    border: none;
  }
</style>
