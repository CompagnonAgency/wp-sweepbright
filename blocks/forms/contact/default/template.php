<div data-contact>
  <script type="text/javascript">
    window.addEventListener("wp_contact_estate_error", function(event) {
      console.log("error: ", event.type, event.detail.message);
      document.querySelector("[data-contact-estate-error]").classList.remove("hidden");
      document.querySelector("[data-contact-estate-error]").classList.add("flex");
    });

    window.addEventListener("wp_contact_estate_sent", function(event) {
      console.log("success: ", event.type, event.detail.message);
      document.querySelector("[data-contact-estate-success]").classList.remove("hidden");
      document.querySelector("[data-contact-estate-success]").classList.add("flex");
    });
  </script>

  <a name="contact-message"></a>

  <div class="items-start hidden p-10 mb-8 text-white lg:space-x-6 bg-primary" data-contact-estate-error>
    <i class="hidden mt-2 text-4xl opacity-50 fal fa-exclamation-circle lg:block"></i>
    <div>
      <p class="font-semibold">
        <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['flash']['error']['title']; ?>
      </p>
      <p><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['flash']['error']['description']; ?></p>
    </div>
  </div>

  <div class="items-start hidden p-10 mb-8 text-white lg:space-x-6 bg-primary" data-contact-estate-success>
    <i class="hidden mt-2 text-4xl opacity-50 fal fa-envelope-open-text lg:block"></i>
    <div>
      <p class="font-semibold"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['flash']['success']['title']; ?></p>
      <p><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['flash']['success']['description']; ?></p>
    </div>
  </div>

  <form method="post" action="#contact-message" name="contact-form">
    <div class="space-y-5">
      <?php foreach (WP_Wrapper::get('column_group', $component, $args) as &$column) : ?>
        <div class="flex flex-col space-y-5 lg:space-x-10 lg:space-y-0 lg:flex-row">
          <?php foreach ($column['fields']['data']['default']['field_group'] as &$field) : ?>
            <?php
            $field_name = $field['fields']['data']['default']['field'];
            $field_label = WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()][$field_name] ?? '';
            $field_required = $field['fields']['data']['default']['required'] === 'required' ? 'required' : '';
            $field_options = isset($field['fields']['data']['default']['options_group']) ? $field['fields']['data']['default']['options_group'] : false;
            ?>

            <div class="flex-1">
              <p class="mb-4 text-uppercase"><?= $field_label; ?> <?php if ($field_required) : ?>*<?php endif; ?></p>

              <?php if ($field_name === 'message') : ?>
                <textarea class="w-full form-input textarea-medium" name="form_<?= $field_name; ?>" <?= $field_required; ?>><?php if (isset($_GET[$field_name])) : ?><?= $_GET[$field_name]; ?><?php endif; ?></textarea>
              <?php elseif ($field_name === 'subject' && $field_options) : ?>
                <div class="form-select">
                  <select class="w-full form-input" name="form_<?= $field_name; ?>" value="<?php if (isset($_GET[$field_name])) : ?><?= $_GET[$field_name]; ?><?php endif; ?>" <?= $field_required; ?>>
                    <?php foreach ($field_options as &$field_option) : ?>
                      <?php $option_value = $field_option['fields']['data'][WP_Wrapper::lang()]['option']; ?>
                      <option value="<?= $option_value; ?>" <?php if (isset($_GET[$field_name]) && $option_value === $_GET[$field_name]) : ?>selected<?php endif; ?>>
                        <?= $option_value; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php else : ?>
                <input type="text" class="w-full form-input" name="form_<?= $field_name; ?>" value="<?php if (isset($_GET[$field_name])) : ?><?= $_GET[$field_name]; ?><?php endif; ?>" <?= $field_required; ?>>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>

      <div>
        <label>
          <input type="checkbox" required>
          <span><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['privacy_policy']; ?></span>
        </label>
      </div>
    </div>

    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

    <?php
    $_SESSION["send_to"] = WP_Wrapper::get('send_to', $component, $args);
    $_SESSION["contact_subject"] = WP_Wrapper::get('contact_subject', $component, $args);
    $_SESSION["contact_body"] = WP_Wrapper::get('contact_body', $component, $args);
    $_SESSION["autoreply_subject"] = WP_Wrapper::get('autoreply_subject', $component, $args);
    $_SESSION["autoreply_body"] = WP_Wrapper::get('autoreply_body', $component, $args);
    ?>

    <button class="mt-6 btn btn-primary" type="submit" name="submit-contact-form">
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['send']; ?>
    </button>
  </form>
</div>
