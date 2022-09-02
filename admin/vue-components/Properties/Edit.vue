<template>
  <div class="wrap" v-if="estate.meta">
    <!-- Notification -->
    <div
      class="mx-0 mb-4 notice notice-success is-dismissible"
      v-if="saveNotification"
    >
      <p>Changes saved successfully.</p>
    </div>

    <ProjectHeading :estate="estate" :language="language"></ProjectHeading>

    <!-- Tabs -->
    <Tab :estate="estate">
      <p class="text-xl font-medium text-black">Additional information</p>
      <table class="form-table" role="presentation">
        <tbody>
          <tr>
            <th scope="row">
              <label for="tag">Tag</label>
            </th>
            <td>
              E.g. <code>Phase 1</code>, <code>20% sold</code>, ...<br /><br />
              <input
                type="text"
                id="tag"
                v-model="form.tag"
                class="regular-text"
              />
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="style">Building style</label>
            </th>
            <td>
              E.g. <code>Modern</code>, <code>Classic</code>, ...<br /><br />
              <input
                type="text"
                id="style"
                v-model="form.style"
                class="regular-text"
              />
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="type">Building type</label>
            </th>
            <td>
              E.g. <code>Terraced</code>, <code>Detached</code>, ...<br /><br />
              <input
                type="text"
                id="type"
                v-model="form.type"
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

      <hr class="my-6" />

      <p class="text-xl font-medium text-black">Advanced customization</p>
      <p class="max-w-xl mt-2 mb-6 text-sm">
        Depending on whether your website theme supports it, you can customize
        labels and texts to further personalize your property.
      </p>

      <p class="text-lg font-medium text-black">Contact</p>
      <p class="mt-2 text-sm">
        Here you can overwrite the "contact" call-to-action.
      </p>
      <table class="form-table" role="presentation">
        <tbody>
          <tr>
            <th scope="row">
              <label for="contactTitle">Title</label>
            </th>
            <td>
              <div class="regular-text">
                <wysiwyg v-model="form.contact.title" />
              </div>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="contactDescription">Description</label>
            </th>
            <td>
              <div class="regular-text">
                <wysiwyg v-model="form.contact.description" />
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <hr class="my-6" />

      <p class="text-lg font-medium text-black">Custom call-to-action</p>
      <p class="mt-2 text-sm">
        Here you can overwrite the link and label of the call-to-action
        button.<br />
        E.g. <code>Download plans</code>, <code>Download documents</code>,
        <code>Contact us</code>.
      </p>
      <table class="form-table" role="presentation">
        <tbody>
          <tr>
            <th scope="row">
              <label for="ctaLabel">Label</label>
            </th>
            <td>
              <input
                type="text"
                id="ctaLabel"
                v-model="form.cta.label"
                class="regular-text"
              />
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="ctaLink">Action</label>
            </th>
            <td>
              <select id="ctaAction" class="postform" v-model="form.cta.action">
                <option class="level-0" value="default">Default</option>
                <option class="level-0" value="download_plans">
                  Download plans
                </option>
                <option class="level-0" value="download_documents">
                  Download documents
                </option>
                <option class="level-0" value="link_contact">Contact</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>

      <input
        type="submit"
        class="button button-primary"
        value="Save changes"
        @click="saveChanges"
      />
    </Tab>
  </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";
import ProjectHeading from "./ProjectHeading.vue";
import Tab from "./Tab.vue";

export default {
  components: {
    ProjectHeading,
    Tab,
  },
  data() {
    return {
      saveNotification: false,
      estate: {},
      id: false,
      form: {
        tag: "",
        style: "",
        type: "",
        usp: [],
        contact: {},
        cta: {
          label: "",
          action: "default",
        },
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
        // Tag
        if (this.estate.meta.custom_fields.tag) {
          this.form.tag = this.estate.meta.custom_fields.tag;
        }

        // Style
        if (this.estate.meta.custom_fields.style) {
          this.form.style = this.estate.meta.custom_fields.style;
        }

        // Type
        if (this.estate.meta.custom_fields.type) {
          this.form.type = this.estate.meta.custom_fields.type;
        }

        // USP
        if (this.estate.meta.custom_fields.usp) {
          this.estate.meta.custom_fields.usp.forEach((usp) => {
            this.form.usp.push({
              value: usp.usp_item,
            });
          });
        }

        // Contact
        if (this.estate.meta.custom_fields.contact.title) {
          this.form.contact.title = this.estate.meta.custom_fields.contact.title;
        }
        if (this.estate.meta.custom_fields.contact.description) {
          this.form.contact.description = this.estate.meta.custom_fields.contact.description;
        }

        // CTA
        if (this.estate.meta.custom_fields.cta.label) {
          this.form.cta.label = this.estate.meta.custom_fields.cta.label;
        }
        if (this.estate.meta.custom_fields.cta.action) {
          this.form.cta.action = this.estate.meta.custom_fields.cta.action;
        }
      }
    },
    getEstate() {
      axios
        .get(`/wp-json/v1/sweepbright/property/${this.id}`)
        .then((response) => {
          const [estate] = response.data;
          this.estate = estate;
          this.fillForm();
        });
    },
    saveChanges() {
      axios
        .post(`/wp-json/v1/sweepbright/property/${this.id}/save`, {
          form: this.form,
        })
        .then(() => {
          $("html, body").animate({ scrollTop: 0 }, 200);
          this.saveNotification = true;

          setTimeout(() => {
            this.saveNotification = false;
          }, 3000);
        });
    },
  },
  mounted() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    this.id = this.$route.params.id;
    this.getEstate();
  },
  beforeRouteUpdate(to, from, next) {
    this.id = to.params.id;
    this.estate = {};
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    this.getEstate();
    next();
  },
};
</script>

<style>
dd,
li {
  margin: 0;
}

.bg-wp {
  background-color: #f1f1f1 !important;
}

.bg-wp-dark {
  background-color: #e4e4e4;
}
</style>
