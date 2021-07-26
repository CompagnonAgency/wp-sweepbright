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

  <form method="post" action="#contact-message" name="contact-valuation">
    <div class="flex flex-col-reverse lg:space-x-10 lg:flex-row">
      <div class="w-full mt-5 lg:w-1/2 lg:mt-0">
        <div class="mb-5 post">
          <h3><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']['address']; ?></h3>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['street']; ?> *</p>
          <input type="text" class="w-full form-input" name="street" required>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['city']; ?> *</p>
          <input type="text" class="w-full form-input" name="city" required>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['message']; ?> *</p>
          <textarea class="w-full form-input textarea-medium" name="message" required></textarea>
        </div>
      </div>

      <div class="w-full lg:w-1/2">
        <div class="mb-5 post">
          <h3><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']['contact']; ?></h3>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['first_name']; ?> *</p>
          <input type="text" class="w-full form-input" name="first_name" required>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['last_name']; ?> *</p>
          <input type="text" class="w-full form-input" name="last_name" required>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['email']; ?> *</p>
          <input type="email" class="w-full form-input" name="email" required>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['phone']; ?> *</p>
          <input type="phone" class="w-full form-input" name="phone" required>
        </div>
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

    <button class="mt-6 btn btn-primary" type="submit" name="submit-contact-valuation">
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['send_message']; ?>
    </button>
  </form>
</div>
