<?php

/**
 * Settings.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    WP_SweepBright
 * @subpackage WP_SweepBright/admin/partials
 */
?>

<div class="wrap">
  <form name="wp-global-settings" method="post" action="">
    <h1>Settings</h1>

    <h2 class="title">API credentials</h2>
    <p>
      Enter your SweepBright API credentials. If you don't have these yet, please contact <a target="_blank" href="https://get.sweepbright.help/en/articles/2529969-request-access-to-our-website-api">SweepBright</a>.
    </p>

    <table class="form-table" role="presentation">
      <tbody>
        <tr>
          <th scope="row">
            <label for="client_id">Client ID</label>
          </th>
          <td>
            <input name="client_id" type="text" id="client_id" value="<?= WP_SweepBright_Helpers::settings_form()['client_id']; ?>" class="regular-text code" required>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="client_secret">Client Secret</label>
          </th>
          <td>
            <input name="client_secret" type="text" id="client_secret" value="<?= WP_SweepBright_Helpers::settings_form()['client_secret']; ?>" class="regular-text code" required>
          </td>
        </tr>
      </tbody>
    </table>

    <hr>

    <h2 class="title">Custom URL</h2>
    <p>
      Set a custom permalink for your properties. When having the URL set to e.g. <kbd>/estates</kbd>,<br>
      the URL of a property would look like this in the browser: <kbd>https://an-estate-agency.com/estates/lakeview-mansion-cumbria</kbd>.
    </p>

    <table class="form-table" role="presentation">
      <tbody>
        <tr>
          <th scope="row">
            <label for="custom_url">URL</label>
          </th>
          <td>
            <input name="custom_url" type="text" id="custom_url" placeholder="estates" value="<?= WP_SweepBright_Helpers::settings_form()['custom_url']; ?>" class="regular-text ltr" required>
          </td>
        </tr>
      </tbody>
    </table>

    <hr>

    <h2 class="title">Default language</h2>
    <p>
      SweepBright supports multiple languages: <kbd>english</kbd>, <kbd>french</kbd> and <kbd>dutch</kbd>.
    </p>
    <p>
      Select which language you want to use by default.
    </p>

    <select name="default_language" id="default_language" class="postform" value="<?= WP_SweepBright_Helpers::settings_form()['default_language']; ?>">
      <option class="level-0" value="en" <?= WP_SweepBright_Helpers::settings_form()['default_language'] === 'en' ? 'selected' : ''; ?>>English</option>
      <option class="level-0" value="fr" <?= WP_SweepBright_Helpers::settings_form()['default_language'] === 'fr' ? 'selected' : ''; ?>>French</option>
      <option class="level-0" value="nl" <?= WP_SweepBright_Helpers::settings_form()['default_language'] === 'nl' ? 'selected' : ''; ?>>Dutch</option>
    </select>

    <h2 class="title">SweepBright API version</h2>
    <p>
      By default the plugin will use SweepBright's latest API version, but you can also rollback if you need to support an older version.<br>
      <a href="https://website.sweepbright.com/docs/#overview-of-versions" target="_blank">Click here</a> to learn more about the different versions.
    </p>

    <select name="api_version" id="api_version" class="postform" value="<?= WP_SweepBright_Helpers::settings_form()['api_version']; ?>">
      <option class="level-0" value="v20191206" <?= WP_SweepBright_Helpers::settings_form()['api_version'] === 'v20191206' ? 'selected' : ''; ?>>v20191206 (latest)</option>
      <option class="level-0" value="v20191121" <?= WP_SweepBright_Helpers::settings_form()['api_version'] === 'v20191121' ? 'selected' : ''; ?>>v20191121 (stable)</option>
      <option class="level-0" value="20190920" <?= WP_SweepBright_Helpers::settings_form()['api_version'] === '20190920' ? 'selected' : ''; ?>>20190920 (stable)</option>
      <option class="level-0" value="v1" <?= WP_SweepBright_Helpers::settings_form()['api_version'] === 'v1' ? 'selected' : ''; ?>>v1 (unstable)</option>
    </select>

    <p class="submit">
      <input type="submit" name="submit-global-settings" class="button button-primary" value="Save settings">
    </p>
  </form>
</div>

<?php require_once(plugin_dir_path( __FILE__ ). 'components/footer.php'); ?>
