<template>
  <div class="pt-1 mb-5">
    <loading
      :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="true"
      :opacity="0.4"
      blur="0"
      background-color="#1a202c"
      :height="50"
      :width="50"
      color="#fff"
    ></loading>

    <div class="wrap">
      <form>
        <div class="mb-5 last:mb-0">
          <h2 class="mb-1 text-2xl">Favorites</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="favorites">Enable</label>
                </th>
                <td>
                  <input
                    type="checkbox"
                    name="favorites"
                    id="favorites"
                    v-model="settings.favorites"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mb-5 last:mb-0">
          <h2 class="mb-1 text-2xl">Multilanguage</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="default_language">Default language</label>
                </th>
                <td>
                  <select
                    id="style"
                    class="w-auto regular-text ltr"
                    v-model="settings.default_language"
                    @change="changeLanguage"
                  >
                    <option value="nl">Dutch</option>
                    <option value="fr">French</option>
                    <option value="en">English</option>
                  </select>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="default_language">Multilanguage</label>
                </th>
                <td>
                  <select
                    id="style"
                    class="w-auto regular-text ltr"
                    v-model="settings.multilanguage"
                    @change="changeLanguage"
                  >
                    <option :value="false">Disable</option>
                    <option :value="true">Enable</option>
                  </select>
                </td>
              </tr>
              <template v-if="settings.multilanguage">
                <tr>
                  <th scope="row">
                    <label for="lang_nl">Dutch</label>
                  </th>
                  <td>
                    <input
                      type="checkbox"
                      name="lang_nl"
                      id="lang_nl"
                      v-model="settings.enabled_nl"
                      :disabled="settings.default_language === 'nl'"
                    />
                  </td>
                </tr>
                <tr>
                  <th scope="row">
                    <label for="lang_fr">French</label>
                  </th>
                  <td>
                    <input
                      type="checkbox"
                      name="lang_fr"
                      id="lang_fr"
                      v-model="settings.enabled_fr"
                      :disabled="settings.default_language === 'fr'"
                    />
                  </td>
                </tr>
                <tr>
                  <th scope="row">
                    <label for="lang_en">English</label>
                  </th>
                  <td>
                    <input
                      type="checkbox"
                      name="lang_en"
                      id="lang_en"
                      v-model="settings.enabled_en"
                      :disabled="settings.default_language === 'en'"
                    />
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

        <div class="mb-5 last:mb-0">
          <h2 class="mb-1 text-2xl">Custom code</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="favorites"
                    >This code is placed right above the
                    <code>&lt;/head&gt;</code></label
                  >
                </th>
                <td><textarea v-model="settings.header_code"></textarea></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mb-5 last:mb-0">
          <h2 class="mb-1 text-2xl">Onboarding</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="favorites">Completed</label>
                </th>
                <td>
                  <input
                    type="checkbox"
                    name="favorites"
                    id="favorites"
                    v-model="settings.onboarded"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </form>
    </div>

    <!-- Submit -->
    <p class="submit">
      <input
        type="submit"
        class="button button-primary"
        value="Save settings"
        @click="saveSettings"
      />
    </p>
  </div>
</template>

<script>
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  components: {
    Loading,
  },
  data() {
    return {
      isLoading: false,
      settings: false,
    };
  },
  methods: {
    changeLanguage() {
      this.disableLanguages();

      if (this.settings.multilanguage) {
        this.settings["enabled_" + this.settings.default_language] = true;
      }
    },
    disableLanguages() {
      this.settings.enabled_nl = false;
      this.settings.enabled_fr = false;
      this.settings.enabled_en = false;
    },
    saveSettings() {
      this.isLoading = true;
      axios({
        method: "post",
        url: "/wp-json/v1/sweepbright/pages/settings/save",
        data: {
          settings: this.settings,
        },
      }).then(() => {
        this.isLoading = false;

        if (!this.settings.onboarded) {
          location.reload();
        }
      });
    },
    getSettings() {
      this.isLoading = true;
      axios({
        method: "get",
        url: "/wp-json/v1/sweepbright/pages/settings",
      }).then((response) => {
        this.settings = response.data.DATA;
        this.isLoading = false;
      });
    },
  },
  mounted() {
    this.getSettings();
  },
};
</script>

