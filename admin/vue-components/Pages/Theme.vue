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
        <div class="mb-5">
          <h2 class="mb-1 text-2xl">Colors</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="color_primary">Primary</label>
                </th>
                <td>
                  <input
                    required
                    v-model="theme.color_primary"
                    type="color"
                    id="color_primary"
                    class="w-10 regular-text ltr"
                  />
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="color_secondary">Secondary</label>
                </th>
                <td>
                  <input
                    required
                    v-model="theme.color_secondary"
                    type="color"
                    id="color_secondary"
                    class="w-10 regular-text ltr"
                  />
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="color_dark">Dark</label>
                </th>
                <td>
                  <input
                    required
                    v-model="theme.color_dark"
                    type="color"
                    id="color_dark"
                    class="w-10 regular-text ltr"
                  />
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="color_secondary">Light</label>
                </th>
                <td>
                  <input
                    required
                    v-model="theme.color_light"
                    type="color"
                    id="color_secondary"
                    class="w-10 regular-text ltr"
                  />
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="color_text_dark">Text - default</label>
                </th>
                <td>
                  <input
                    required
                    v-model="theme.color_text_dark"
                    type="color"
                    id="color_text_dark"
                    class="w-10 regular-text ltr"
                  />
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="color_text_light">Text - light</label>
                </th>
                <td>
                  <input
                    required
                    v-model="theme.color_text_light"
                    type="color"
                    id="color_text_light"
                    class="w-10 regular-text ltr"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <hr class="mb-8" />

        <div class="mb-5">
          <h2 class="mb-1 text-2xl">Style</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="style">Rounded corners</label>
                </th>
                <td>
                  <select
                    id="style"
                    class="w-auto regular-text ltr"
                    v-model="theme.style"
                  >
                    <option value="classic">Classic</option>
                    <option value="playful">Playful</option>
                    <option value="modern">Modern</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <hr class="mb-8" />

        <div class="mb-5">
          <h2 class="mb-1 text-2xl">Buttons</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="button_color">Color</label>
                </th>
                <td>
                  <input
                    required
                    v-model="theme.button_color"
                    type="color"
                    id="button_color"
                    class="w-10 regular-text ltr"
                  />
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="style">Style</label>
                </th>
                <td>
                  <select
                    id="style"
                    class="w-auto regular-text ltr"
                    v-model="theme.button_style"
                  >
                    <option value="normal">Normal</option>
                    <option value="uppercase">Uppercase</option>
                    <option value="lowercase">Lowercase</option>
                    <option value="raised">Raised</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <hr class="mb-8" />

        <div class="mb-5">
          <h2 class="mb-1 text-2xl">Forms</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="style">Style</label>
                </th>
                <td>
                  <select
                    id="style"
                    class="w-auto regular-text ltr"
                    v-model="theme.form_style"
                  >
                    <option value="border_thick">Border (thick)</option>
                    <option value="border_thin">Border (thin)</option>
                    <option value="line">Line</option>
                    <option value="filled">Filled</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <hr class="mb-8" />

        <div class="mb-5 last:mb-0">
          <h2 class="mb-1 text-2xl">Font</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="font_primary">Primary font</label>
                </th>
                <td>
                  <select
                    v-model="font_primary"
                    class="w-auto regular-text ltr"
                    id="font_primary"
                    @change="setFont($event, 'primary')"
                  >
                    <option
                      v-for="(font, index) in fonts"
                      :key="index"
                      :value="font.value"
                    >
                      {{ font.name }}
                    </option>
                  </select>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="font_secondary">Secondary font</label>
                </th>
                <td>
                  <select
                    v-model="font_secondary"
                    class="w-auto regular-text ltr"
                    id="font_secondary"
                    @change="setFont($event, 'secondary')"
                  >
                    <option
                      v-for="(font, index) in fonts"
                      :key="index"
                      :value="font.value"
                    >
                      {{ font.name }}
                    </option>
                  </select>
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
        @click="saveTheme"
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
  computed: {},
  data() {
    return {
      isLoading: false,
      font_primary: "Open+Sans",
      font_secondary: "Merriweather",
      fonts: [
        {
          name: "Montserrat",
          value: "Montserrat",
          weights: [300, 400, 500, 600],
        },
        {
          name: "Quicksand",
          value: "Quicksand",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "DM Sans",
          value: "DM+Sans",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "Manrope",
          value: "Manrope",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "Inter",
          value: "Inter",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "Red Hat Display",
          value: "Red+Hat+Display",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "IBM Plex Sans",
          value: "IBM+Plex+Sans",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "Source Serif Pro",
          value: "Source+Serif+Pro",
          weights: [300, 400, 600],
        },
      ],
      theme: false,
    };
  },
  methods: {
    saveTheme() {
      this.isLoading = true;
      axios({
        method: "post",
        url: "/wp-json/v1/sweepbright/theme/save",
        data: {
          theme: this.theme,
        },
      }).then(() => {
        this.isLoading = false;
      });
    },
    getTheme() {
      this.isLoading = true;
      axios({
        method: "get",
        url: "/wp-json/v1/sweepbright/theme",
      }).then((response) => {
        this.isLoading = false;
        this.theme = response.data.THEME;
        this.font_primary = this.theme.font_primary.value;
        this.font_secondary = this.theme.font_secondary.value;
      });
    },
    setFont($e, type) {
      const font = this.fonts.find((obj) => {
        return obj.value === $e.target.value;
      });
      if (type === "primary") {
        this.theme.font_primary = font;
      }
      if (type === "secondary") {
        this.theme.font_secondary = font;
      }
    },
  },
  mounted() {
    this.getTheme();
  },
};
</script>
