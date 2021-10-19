<template>
  <div class="wrap" v-if="estate.meta">
    <!-- Success notification -->
    <div
      class="mx-0 mb-4 notice notice-success is-dismissible"
      v-if="saveNotification"
    >
      <p>Changes saved successfully.</p>
    </div>

    <!-- Required notification -->
    <div
      class="mx-0 mb-4 notice notice-error is-dismissible"
      v-if="errorNotification"
    >
      <p>Please fill in all the required fields.</p>
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

      <p class="text-xl font-medium text-black">Additional price information</p>

      <div class="flex items-center my-5 space-x-5">
        <div class="relative inline-block w-12 mr-2 align-middle select-none">
          <input
            type="checkbox"
            id="enable_additional_price"
            v-model="form.enable_additional_price"
            class="absolute block w-6 h-6 bg-white border-4 rounded-full appearance-none cursor-pointer toggle-checkbox"
          />
          <label
            for="enable_additional_price"
            class="block h-6 overflow-hidden bg-gray-400 rounded-full cursor-pointer toggle-label"
          ></label>
        </div>
        <label for="enable_additional_price" class="text-gray-700">Enable</label>
      </div>

      <table v-if="form.enable_additional_price" class="form-table" role="presentation">
        <tbody>
          <tr>
            <th scope="row">
              <label for="gross_rent_profit">Gross rent profit</label>
            </th>
            <td :class="errors.gross_rent_profit ? 'has-error' : ''">
              The gross rent profit of the property per month. (required)<br /><br />
              <div class="flex items-center">
                <div class="mr-2 text-2xl font-medium text-gray-600">&euro;</div>
                <input
                  type="number"
                  id="gross_rent_profit"
                  v-model="form.gross_rent_profit"
                  class="small-text"
                  required
                />
              </div>
            </td>
          </tr>
          <tr v-if="estate.meta.estate.is_project">
            <th scope="row">
              <label for="price_construction">Price construction</label>
            </th>
            <td :class="errors.price_construction ? 'has-error' : ''">
              Construction value of the property. (required)<br /><br />
              <div class="flex items-center">
                <div class="mr-2 text-2xl font-medium text-gray-600">&euro;</div>
                <input
                  type="number"
                  id="price_construction"
                  v-model="form.price_construction"
                  class="regular-text"
                  required
                />
              </div>
            </td>
          </tr>
          <tr v-if="estate.meta.estate.is_project">
            <th scope="row">
              <label for="price_land">Price land</label>
            </th>
            <td :class="errors.price_land ? 'has-error' : ''">
              Land value of the property. (required)<br /><br />
              <div class="flex items-center">
                <div class="mr-2 text-2xl font-medium text-gray-600">&euro;</div>
                <input
                  type="number"
                  id="price_land"
                  v-model="form.price_land"
                  class="regular-text"
                  required
                />
              </div>
            </td>
          </tr>
          <tr v-if="estate.meta.estate.is_project">
            <th scope="row">
              <label for="ground_lease">Ground lease</label>
            </th>
            <td :class="errors.ground_lease ? 'has-error' : ''">
              Ground lease value of the property. (required)<br /><br />
              <div class="flex items-center">
                <div class="mr-2 text-2xl font-medium text-gray-600">&euro;</div>
                <input
                  type="number"
                  id="ground_lease"
                  v-model="form.ground_lease"
                  class="regular-text"
                  required
                />
              </div>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="enable_normal_vat">Type of VAT</label>
            </th>
            <td>
              <div class="flex items-center mb-3 space-x-5">
                <label for="enable_normal_vat" class="text-gray-700">Extended</label>
                <div class="relative inline-block w-12 mr-2 align-middle select-none">
                  <input
                    type="checkbox"
                    id="enable_normal_vat"
                    v-model="form.enable_normal_vat"
                    class="absolute block w-6 h-6 bg-white border-4 rounded-full appearance-none cursor-pointer toggle-checkbox"
                  />
                  <label
                    for="enable_normal_vat"
                    class="block h-6 overflow-hidden bg-gray-400 rounded-full cursor-pointer toggle-label"
                  ></label>
                </div>
                <label for="enable_normal_vat" class="text-gray-700">Normal</label>
              </div>
            </td>
          </tr>
          <tr v-if="form.enable_normal_vat">
            <th scope="row">
              <label for="vat_total_price">VAT % on total price</label>
            </th>
            <td>
              If you need to pay VAT on the total price of a property.<br /><br />
              <div class="flex items-center">
                <input
                  type="number"
                  id="vat_total_price"
                  v-model="form.vat_total_price"
                  class="small-text"
                />
                <div class="ml-2 text-2xl font-medium text-gray-600">%</div>
              </div>
            </td>
          </tr>
          <tr v-if="!form.enable_normal_vat">
            <th scope="row">
              <label for="vat_construction">VAT % on construction</label>
            </th>
            <td>
              If you need to pay VAT on the construction price of a property.<br /><br />
              <div class="flex items-center">
                <input
                  type="number"
                  id="vat_construction"
                  v-model="form.vat_construction"
                  class="small-text"
                />
                <div class="ml-2 text-2xl font-medium text-gray-600">%</div>
              </div>
            </td>
          </tr>
          <tr v-if="!form.enable_normal_vat">
            <th scope="row">
              <label for="vat_registration_rights">Registration rights %</label>
            </th>
            <td>
              If there is a specific percentage for the registration rights.<br /><br />
              <div class="flex items-center">
                <input
                  type="number"
                  id="vat_registration_rights"
                  v-model="form.vat_registration_rights"
                  class="small-text"
                />
                <div class="ml-2 text-2xl font-medium text-gray-600">%</div>
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
      errorNotification: false,
      estate: {},
      id: false,
      errors: {
        gross_rent_profit: false,
        price_construction: false,
        price_land: false,
        ground_lease: false,
      },
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
        enable_additional_price: false,
        enable_normal_vat: true,
        gross_rent_profit: false,
        price_construction: false,
        price_land: false,
        ground_lease: false,
        vat_total_price: false,
        vat_construction: false,
        vat_registration_rights: false,
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

        // Additional price
        this.form.enable_additional_price = this.estate.meta.custom_fields.enable_additional_price;
        this.form.gross_rent_profit = this.estate.meta.custom_fields.gross_rent_profit;
        this.form.price_construction = this.estate.meta.custom_fields.price_construction;
        this.form.price_land = this.estate.meta.custom_fields.price_land;
        this.form.ground_lease = this.estate.meta.custom_fields.ground_lease;
        if (this.estate.meta.custom_fields.enable_normal_vat) {
          this.form.enable_normal_vat = this.estate.meta.custom_fields.enable_normal_vat;
        }
        this.form.vat_total_price = this.estate.meta.custom_fields.vat_total_price;
        this.form.vat_construction = this.estate.meta.custom_fields.vat_construction;
        this.form.vat_registration_rights = this.estate.meta.custom_fields.vat_registration_rights;
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
    validateFields() {
      let result = true;
      this.errors.gross_rent_profit = false;
      this.errors.price_construction = false;
      this.errors.price_land = false;
      this.errors.ground_lease = false;

      if (this.form.enable_additional_price) {
        if (!this.form.gross_rent_profit) {
          result = false;
          this.errors.gross_rent_profit = true;
        }

        if (this.estate.meta.estate.is_project) {
          if (!this.form.price_construction) {
            result = false;
            this.errors.price_construction = true;
          }

          if (!this.form.price_land) {
            result = false;
            this.errors.price_land = true;
          }

          if (!this.form.ground_lease) {
            result = false;
            this.errors.ground_lease = true;
          }
        }
      }
      return result;
    },
    saveChanges() {
      if (this.validateFields()) {
        axios
          .post(`/wp-json/v1/sweepbright/property/${this.id}/save`, {
            form: this.form,
          })
          .then(() => {
            $("html, body").animate({ scrollTop: 0 }, 200);
            this.errorNotification = false;
            this.saveNotification = true;

            setTimeout(() => {
              this.saveNotification = false;
            }, 3000);
          });
      } else {
        $("html, body").animate({ scrollTop: 0 }, 200);
        this.saveNotification = false;
        this.errorNotification = true;
        setTimeout(() => {
          this.errorNotification = false;
        }, 3000);
      }
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
