<?php

/**
 * Contact.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    WP_SweepBright
 * @subpackage WP_SweepBright/admin/partials
 */
?>

<div class="wrap">
  <form name="wp-contact-settings" method="post" action="">
    <h1>Contact</h1>

    <hr>

    <!-- Contact request for an estate -->
    <h2 class="title">Contact request for an estate</h2>
    <p>
      Edit the form where users can submit a contact request for a <strong>specific estate</strong>.
    </p>
    <p>
      Use the shortcode <kbd>[contact-request-estate]</kbd> within your template to use this form.
    </p>
    <textarea name="contact_request_estate_form" required id="contact_request_estate_form" rows="10" class="widefat textarea code"><?= htmlspecialchars(WP_SweepBright_Helpers::contact_form()['contact_request_estate_form']); ?></textarea>

    <hr>

    <!-- General contact request -->
    <h2 class="title">General contact request</h2>
    <p>
      Edit the form where users can submit a <strong>general</strong> contact request.
    </p>
    <p>
      Use the shortcode <kbd>[contact-request-general]</kbd> within your template to use this form.
    </p>
    <br>
    <textarea name="contact_request_general_form" required id="contact_request_general_form" rows="27" class="widefat textarea code"><?= htmlspecialchars(WP_SweepBright_Helpers::contact_form()['contact_request_general_form']); ?></textarea>

    <hr>

    <!-- Autoresponder -->
    <h2 class="title">Autoresponder</h2>
    <p>
      Configure a custom <kbd>subject</kbd>, <kbd>subject</kbd> and <kbd>recipients</kbd> when <strong>you</strong> receive a contact request.<br>
    </p>

    <table class="form-table" role="presentation">
      <tbody>
        <tr>
          <th scope="row">
            <label for="mail_to">To</label>
          </th>
          <td>
            <input required name="mail_to" type="text" id="mail_to" placeholder="info@my-company.com" value="<?= WP_SweepBright_Helpers::contact_form()['autoresponder']['to']; ?>" class="regular-text code">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="mail_cc">CC (separate with <kbd>,</kbd>)</label>
          </th>
          <td>
            <input name="mail_cc" type="text" id="mail_cc" placeholder="john@doe.com, jane@doe.com" value="<?= WP_SweepBright_Helpers::contact_form()['autoresponder']['cc']; ?>" class="regular-text code">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="mail_subject">Subject</label>
          </th>
          <td>
            <p>
              Available tags: <kbd>[title]</kbd>, <kbd>[address]</kbd>, <kbd>[negotiation]</kbd>, <kbd>[url]</kbd>, <kbd>[first_name]</kbd>, <kbd>[last_name]</kbd>, <kbd>[email]</kbd>, <kbd>[phone]</kbd>, <kbd>[message]</kbd>.<br>
              <kbd>[title]</kbd>, <kbd>[address]</kbd>, <kbd>[url]</kbd> are only available for estate contact requests.
            </p>
            <br>
            <input required name="mail_subject" type="text" id="mail_subject" value="<?= WP_SweepBright_Helpers::contact_form()['autoresponder']['subject']; ?>" class="regular-text ltr">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="mail_message">Message (supports HTML)</label>
          </th>
          <td>
            <p>
              Available tags: <kbd>[title]</kbd>, <kbd>[address]</kbd>, <kbd>[negotiation]</kbd>, <kbd>[url]</kbd>, <kbd>[first_name]</kbd>, <kbd>[last_name]</kbd>, <kbd>[email]</kbd>, <kbd>[phone]</kbd>, <kbd>[message]</kbd>.<br>
              <kbd>[title]</kbd>, <kbd>[address]</kbd>, <kbd>[negotiation]</kbd>, <kbd>[url]</kbd> are only available for estate contact requests.
            </p>
            <br>
            <textarea name="mail_message" required id="mail_message" rows="10" class="widefat textarea code"><?= WP_SweepBright_Helpers::contact_form()['autoresponder']['message']; ?></textarea>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Submit -->
    <p class="submit">
      <input type="submit" name="submit-contact-settings" id="submit" class="button button-primary" value="Save settings">
    </p>
  </form>
</div>

<?php require_once(plugin_dir_path(__FILE__) . 'components/footer.php'); ?>
