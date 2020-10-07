<template>
  <div>
    <!-- Detail -->
    <div class="wrap" v-if="estate.meta">
      <!-- Notification -->
      <div
        class="mx-0 mb-4 notice notice-success is-dismissible"
        v-if="saveNotification"
      >
        <p>Changes saved successfully.</p>
        <button
          type="button"
          class="notice-dismiss"
          @click="saveNotification = false"
        ></button>
      </div>

      <div class="flex items-center">
        <img
          width="100"
          height="100"
          class="mr-8 border-none"
          :src="estate.meta.features.images[0].sizes.thumbnail"
        />
        <div>
          <h1>{{ estate.meta.estate.title[language] }}</h1>
          <p class="mb-3 font-semibold">
            {{ estate.meta.location.street }}
            {{ estate.meta.location.number }},
            {{ estate.meta.location.city }}
          </p>
          <a :href="estate.permalink" class="button action" target="_blank"
            >View property</a
          >
        </div>
      </div>

      <hr class="my-10" />

      <table class="form-table" role="presentation">
        <tbody>
          <tr>
            <th scope="row">
              <label for="phase">Construction phase</label>
            </th>
            <td>
              E.g. "Phase 1"<br /><br />
              <input
                type="text"
                id="phase"
                v-model="form.phase"
                class="regular-text"
              />
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label>Unique selling points</label>
            </th>
            <td>
              E.g. Spacious, sustainable and energy-efficient homes<br /><br />
              <div class="mb-2" v-for="(usp, index) in form.usp" :key="index">
                <input type="text" v-model="usp.value" class="regular-text" />
                <button
                  type="submit"
                  class="button button-action"
                  @click="removeUSP(index)"
                >
                  <i class="mt-1 dashicons dashicons-trash"></i>
                </button>
              </div>
              <div class="mt-2">
                <button
                  type="submit"
                  class="button button-action"
                  @click="addUSP"
                >
                  <i class="mt-1 dashicons dashicons-plus-alt2"></i>
                  Add selling point
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <p class="submit">
        <input
          type="submit"
          class="button button-primary"
          value="Save changes"
          @click="saveChanges"
        />
      </p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  components: {},
  data() {
    return {
      saveNotification: false,
      estate: {},
      form: {
        phase: "",
        usp: [],
      },
      language: window.sweepbrightLanguage,
    };
  },
  methods: {
    addUSP() {
      this.form.usp.push({
        value: "",
      });
    },
    removeUSP(index) {
      this.form.usp.splice(index, 1);
    },
    fillForm() {
      if (this.estate.meta.custom_fields) {
        if (this.estate.meta.custom_fields.phase) {
          this.form.phase = this.estate.meta.custom_fields.phase;
        }

        if (this.estate.meta.custom_fields.usp) {
          this.estate.meta.custom_fields.usp.forEach((usp) => {
            this.form.usp.push({
              value: usp.usp_item,
            });
          });
        }
      }
    },
    getEstate() {
      axios
        .get(`/wp-json/v1/sweepbright/property/${this.$route.params.id}`)
        .then((response) => {
          const [estate] = response.data;
          this.estate = estate;
          this.fillForm();
        });
    },
    saveChanges() {
      axios
        .post(
          `/wp-json/v1/sweepbright/property/${this.$route.params.id}/save`,
          {
            form: this.form,
          }
        )
        .then(() => {
          this.saveNotification = true;
        });
    },
  },
  mounted() {
    this.getEstate();
  },
};
</script>

<style>
dd,
li {
  margin: 0;
}
</style>
