<template>
  <div class="mb-5">
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
                  <label for="color_primary">Primary color</label>
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
                  <label for="color_secondary">Secondary color</label>
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

        <hr class="mb-8" />

        <div class="mb-5 last:mb-0">
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

        <div class="mb-5 last:mb-0">
          <h2 class="mb-1 text-2xl">Logo</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="logo">Upload logo</label>
                </th>
                <td>
                  <input
                    v-model="theme.logo"
                    required
                    type="text"
                    id="logo"
                    class="max-w-lg regular-text ltr"
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
          name: "Open Sans",
          value: "Open+Sans",
          weights: [300, 400, 600, 700],
        },
        {
          name: "Work Sans",
          value: "Work+Sans",
          weights: [200, 300, 400, 500, 600],
        },
        {
          name: "Roboto",
          value: "Roboto",
          weights: [100, 300, 400, 500, 700],
        },
        {
          name: "Poppins",
          value: "Poppins",
          weights: [100, 200, 300, 400, 500, 600],
        },
        {
          name: "Nunito",
          value: "Nunito",
          weights: [200, 300, 400, 600, 700],
        },
        {
          name: "Quicksand",
          value: "Quicksand",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "Dosis",
          value: "Dosis",
          weights: [200, 300, 400, 500, 600, 700],
        },
        {
          name: "Asap",
          value: "Asap",
          weights: [400, 500, 600],
        },
        {
          name: "Baloo Paaji 2",
          value: "Baloo+Paaji+2",
          weights: [400, 500, 600, 700],
        },
        {
          name: "Roboto Slab",
          value: "Roboto+Slab",
          weights: [100, 200, 300, 400, 500, 600],
        },
        {
          name: "Source Serif Pro",
          value: "Source+Serif+Pro",
          weights: [200, 300, 400, 600],
        },
        {
          name: "Merriweather",
          value: "Merriweather",
          weights: [300, 400, 700],
        },
        {
          name: "Bitter",
          value: "Bitter",
          weights: [100, 200, 300, 400, 500, 600],
        },
        {
          name: "Martel",
          value: "Martel",
          weights: [200, 300, 400, 600, 700],
        },
        {
          name: "Cormorant Garamond",
          value: "Cormorant+Garamond",
          weights: [300, 400, 500, 600, 700],
        },
        {
          name: "Taviraj",
          value: "Taviraj",
          weights: [100, 200, 300, 400, 500, 600],
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
      }).then((response) => {
        console.log(response);
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
    // load defaults
    this.getTheme();
  },
};
</script>
