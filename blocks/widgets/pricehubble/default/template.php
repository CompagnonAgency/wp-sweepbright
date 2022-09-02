<iframe id="fisher-widget"></iframe>
<script src="https://fisher.pricehubble.com/widget.js"></script>

<script type="text/javascript">
  FisherWidget.init({
    apiKey: '<?= WP_Wrapper::get('api_key', $component, $args); ?>',
    iframe: '#fisher-widget',
    textColor: '<?= WP_Wrapper::get('text_color', $component, $args); ?>',
    activeColor: '<?= WP_Wrapper::get('active_color', $component, $args); ?>',
    buttonColor: '<?= WP_Wrapper::get('button_color', $component, $args); ?>',
    lang: '<?= WP_Wrapper::get('language', $component, $args); ?>',
    gaTrackingId: '<?= WP_Wrapper::get('ga_id', $component, $args); ?>',
    gtmId: '<?= WP_Wrapper::get('gtm_id', $component, $args); ?>',
    resizable: true,
    scrollTopOffset: <?= WP_Wrapper::get('scroll_top_offset', $component, $args) ? WP_Wrapper::get('scroll_top_offset', $component, $args) : 0; ?>,
  });
</script>

<style>
  iframe#fisher-widget {
    width: 100%;
  }
</style>
