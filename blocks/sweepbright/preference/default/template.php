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

  <form method="post" action="#contact-message" name="contact-estate">
    <div class="flex flex-col-reverse lg:space-x-10 lg:flex-row">
      <div class="w-full mt-5 lg:w-1/2 lg:mt-0">
        <div class="mb-5 post">
          <h3><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']['preferences']; ?></h3>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['postal_codes']; ?> *</p>
          <input type="text" class="w-full form-input" name="postal_codes" placeholder="<?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['postal_codes_placeholder']; ?>" required>
          <p class="mt-2 text-sm text-gray-500"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['postal_codes_help']; ?> *</p>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['negotiation']; ?> *</p>
          <div class="form-select">
            <select class="w-full form-input" name="negotiation" required>
              <option>-</option>
              <option value="let"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['negotiation']['let']; ?></option>
              <option value="sale"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['negotiation']['sale']; ?></option>
            </select>
          </div>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['types']; ?></p>
          <label class="block">
            <input type="checkbox" name="types[]" value="apartment">
            <span><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['types']['apartment']; ?></span>
          </label>
          <label class="block">
            <input type="checkbox" name="types[]" value="commercial">
            <span><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['types']['commercial']; ?></span>
          </label>
          <label class="block">
            <input type="checkbox" name="types[]" value="house">
            <span><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['types']['house']; ?></span>
          </label>
          <label class="block">
            <input type="checkbox" name="types[]" value="land">
            <span><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['types']['land']; ?></span>
          </label>
          <label class="block">
            <input type="checkbox" name="types[]" value="office">
            <span><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['types']['office']; ?></span>
          </label>
          <label class="block">
            <input type="checkbox" name="types[]" value="parking">
            <span><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['dropdowns']['types']['parking']; ?></span>
          </label>
        </div>

        <div class="mb-5 last:mb-0">
          <div class="flex flex-col space-y-5 lg:flex-row lg:space-x-10 lg:space-y-0">
            <div class="w-full lg:w-1/2">
              <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['min_price']; ?></p>
              <div class="form-select">
                <select class="w-full form-input" name="min_price">
                  <option>-</option>
                  <option value="500"><?= WP_Wrapper::get('currency', $component, $args); ?> 500</option>
                  <option value="800"><?= WP_Wrapper::get('currency', $component, $args); ?> 800</option>
                  <option value="1000"><?= WP_Wrapper::get('currency', $component, $args); ?> 1000</option>
                  <option value="50000"><?= WP_Wrapper::get('currency', $component, $args); ?> 50.000</option>
                  <option value="100000"><?= WP_Wrapper::get('currency', $component, $args); ?> 100.000</option>
                  <option value="150000"><?= WP_Wrapper::get('currency', $component, $args); ?> 150.000</option>
                  <option value="200000"><?= WP_Wrapper::get('currency', $component, $args); ?> 200.000</option>
                  <option value="250000"><?= WP_Wrapper::get('currency', $component, $args); ?> 250.000</option>
                  <option value="300000"><?= WP_Wrapper::get('currency', $component, $args); ?> 300.000</option>
                  <option value="350000"><?= WP_Wrapper::get('currency', $component, $args); ?> 350.000</option>
                  <option value="400000"><?= WP_Wrapper::get('currency', $component, $args); ?> 400.000</option>
                  <option value="450000"><?= WP_Wrapper::get('currency', $component, $args); ?> 450.000</option>
                  <option value="500000"><?= WP_Wrapper::get('currency', $component, $args); ?> 500.000</option>
                  <option value="550000"><?= WP_Wrapper::get('currency', $component, $args); ?> 550.000</option>
                  <option value="600000"><?= WP_Wrapper::get('currency', $component, $args); ?> 600.000</option>
                  <option value="650000"><?= WP_Wrapper::get('currency', $component, $args); ?> 650.000</option>
                  <option value="700000"><?= WP_Wrapper::get('currency', $component, $args); ?> 700.000</option>
                  <option value="750000"><?= WP_Wrapper::get('currency', $component, $args); ?> 750.000</option>
                  <option value="800000"><?= WP_Wrapper::get('currency', $component, $args); ?> 800.000</option>
                  <option value="850000"><?= WP_Wrapper::get('currency', $component, $args); ?> 850.000</option>
                  <option value="900000"><?= WP_Wrapper::get('currency', $component, $args); ?> 900.000</option>
                  <option value="950000"><?= WP_Wrapper::get('currency', $component, $args); ?> 950.000</option>
                  <option value="1000000"><?= WP_Wrapper::get('currency', $component, $args); ?> 1.000.000</option>
                </select>
              </div>
            </div>

            <div class="w-full lg:w-1/2">
              <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['max_price']; ?></p>
              <div class="form-select">
                <select class="w-full form-input" name="max_price">
                  <option>-</option>
                  <option value="500"><?= WP_Wrapper::get('currency', $component, $args); ?> 500</option>
                  <option value="800"><?= WP_Wrapper::get('currency', $component, $args); ?> 800</option>
                  <option value="1000"><?= WP_Wrapper::get('currency', $component, $args); ?> 1000</option>
                  <option value="50000"><?= WP_Wrapper::get('currency', $component, $args); ?> 50.000</option>
                  <option value="100000"><?= WP_Wrapper::get('currency', $component, $args); ?> 100.000</option>
                  <option value="150000"><?= WP_Wrapper::get('currency', $component, $args); ?> 150.000</option>
                  <option value="200000"><?= WP_Wrapper::get('currency', $component, $args); ?> 200.000</option>
                  <option value="250000"><?= WP_Wrapper::get('currency', $component, $args); ?> 250.000</option>
                  <option value="300000"><?= WP_Wrapper::get('currency', $component, $args); ?> 300.000</option>
                  <option value="350000"><?= WP_Wrapper::get('currency', $component, $args); ?> 350.000</option>
                  <option value="400000"><?= WP_Wrapper::get('currency', $component, $args); ?> 400.000</option>
                  <option value="450000"><?= WP_Wrapper::get('currency', $component, $args); ?> 450.000</option>
                  <option value="500000"><?= WP_Wrapper::get('currency', $component, $args); ?> 500.000</option>
                  <option value="550000"><?= WP_Wrapper::get('currency', $component, $args); ?> 550.000</option>
                  <option value="600000"><?= WP_Wrapper::get('currency', $component, $args); ?> 600.000</option>
                  <option value="650000"><?= WP_Wrapper::get('currency', $component, $args); ?> 650.000</option>
                  <option value="700000"><?= WP_Wrapper::get('currency', $component, $args); ?> 700.000</option>
                  <option value="750000"><?= WP_Wrapper::get('currency', $component, $args); ?> 750.000</option>
                  <option value="800000"><?= WP_Wrapper::get('currency', $component, $args); ?> 800.000</option>
                  <option value="850000"><?= WP_Wrapper::get('currency', $component, $args); ?> 850.000</option>
                  <option value="900000"><?= WP_Wrapper::get('currency', $component, $args); ?> 900.000</option>
                  <option value="950000"><?= WP_Wrapper::get('currency', $component, $args); ?> 950.000</option>
                  <option value="1000000"><?= WP_Wrapper::get('currency', $component, $args); ?> 1.000.000</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['min_rooms']; ?></p>
          <div class="form-select">
            <select class="w-full form-input" name="min_rooms">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select>
          </div>
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

        <div class="mb-5 last:mb-0">
          <p class="mb-4 text-uppercase"><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['message']; ?> *</p>
          <textarea class="w-full form-input textarea-large" name="message" required></textarea>
        </div>
      </div>
    </div>

    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
    <input type="hidden" name="locale" value="<?= WP_Wrapper::lang(); ?>" required>
    <input type="hidden" name="country" value="<?= WP_Wrapper::get('country', $component, $args); ?>" required>

    <button class="mt-6 btn btn-primary" type="submit" name="submit-contact-general">
      <?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['send_message']; ?>
    </button>
  </form>
</div>
