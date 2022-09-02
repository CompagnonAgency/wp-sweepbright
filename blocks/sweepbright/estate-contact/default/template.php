<div id="contact-form" data-contact>
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

  <div class="mb-8 post">
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
  </div>

  <?php if (get_field('estate')['status'] === 'available' || get_field('estate')['status'] === 'prospect' || (get_field('estate')['status'] === 'under_contract' && WP_SweepBright_Helpers::setting('available_properties') === 'under_contract')) : ?>
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

    <form class="space-y-0 lg:space-y-8" method="post" action="#contact-message" name="contact-estate">
      <div class="flex flex-col-reverse lg:space-x-10 lg:flex-row">
        <div class="w-full mb-5 lg:w-1/2 lg:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['first_name']; ?> *</p>
          <input type="text" class="w-full form-input" name="first_name" required>
        </div>
        <div class="w-full mb-5 lg:w-1/2 lg:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['email']; ?> *</p>
          <input type="email" class="w-full form-input" name="email" required>
        </div>
      </div>

      <div class="flex flex-col lg:space-x-10 lg:flex-row">
        <div class="w-full mb-5 lg:w-1/2 lg:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['last_name']; ?> *</p>
          <input type="text" class="w-full form-input" name="last_name" required>
        </div>
        <div class="w-full mb-5 lg:w-1/2 lg:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['phone']; ?> *</p>
          <input type="phone" class="w-full form-input" name="phone" required>
        </div>
      </div>

      <div class="flex flex-col lg:space-x-10 lg:flex-row">
        <div class="w-full mb-5 lg:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['message']; ?> *</p>
          <textarea class="w-full form-input" name="message" required></textarea>
        </div>
      </div>

      <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
      <input type="hidden" name="locale" value="<?= WP_Wrapper::lang(); ?>" required>

      <button class="btn btn-primary" type="submit" name="submit-contact-estate">
        <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['send_message']; ?>
      </button>
    </form>
  <?php else : ?>
    <div>
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['unavailable']; ?>
    </div>
  <?php endif; ?>
</div>
