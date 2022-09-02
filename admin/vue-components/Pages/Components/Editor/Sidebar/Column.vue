<template>
  <div>
    <template v-if="user_roles.includes('administrator')">
      <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
        Settings
      </div>
      <div class="relative p-5 border-b border-gray-400 last:border-0">
        <loading
          :active.sync="isLoading"
          :can-cancel="false"
          :is-full-page="false"
          :opacity="0.1"
          background-color="#1a202c"
          :height="0"
          :width="0"
          color="#fff"
        ></loading>
    
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          <i class="w-4 fad fa-bookmark"></i>
          Template
        </p>
        <select v-model="col.template" @change="setTemplate" v-if="templates !== null && typeof templates != 'undefined' && Object.keys(templates).length > 0">
          <option value="" disabled>-- Choose a template --</option>
          <option :value="template.name" v-for="(template, index) in templates" :key="index">
            {{ template.name }}
          </option>
        </select>
        <div class="flex mt-5 space-x-5">
          <div class="flex-1" v-if="templates !== null && col.template && typeof templates != 'undefined' && Object.keys(templates).length > 0">
            <button class="flex items-center justify-center w-full btn btn-default" @click="deleteTemplate">
              <i class="mr-2 text-base text-gray-500 fad fa-trash"></i>
              Delete
            </button>
          </div>
          <div class="flex-1" v-if="col.template">
            <button class="flex items-center justify-center w-full btn btn-default" @click="detachTemplate">
              <i class="mr-2 text-base text-gray-500 fad fa-unlink"></i>
              Unlink
            </button>
          </div>
          <div class="flex-1">
            <button class="flex items-center justify-center w-full btn btn-default" @click="saveTemplate">
              <i class="mr-2 text-base text-gray-500 fad fa-bookmark"></i>
              Bookmark
            </button>
          </div>
        </div>
      </div>
      <div class="p-5 border-b border-gray-400 last:border-0">
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          <i class="w-4 fad fa-id-card-alt"></i>
          ID
        </p>
        <input type="text" v-model="col.name" placeholder="Unique name" />
      </div>
      <div class="p-5 border-b border-gray-400 last:border-0">
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          <i class="w-4 fad fa-ruler-triangle"></i>
          Width
        </p>
        <select v-model="col.width" @change="setWidth">
          <option value="w-full">Automatic</option>
          <option value="w-1/12">1/12</option>
          <option value="w-2/12">2/12</option>
          <option value="w-3/12">3/12</option>
          <option value="w-4/12">4/12</option>
          <option value="w-5/12">5/12</option>
          <option value="w-6/12">6/12</option>
          <option value="w-7/12">7/12</option>
          <option value="w-8/12">8/12</option>
          <option value="w-9/12">9/12</option>
          <option value="w-10/12">10/12</option>
          <option value="w-11/12">11/12</option>
        </select>
      </div>
    </template>

    <template v-if="user_roles.includes('administrator')">
      <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
        Component
      </div>
      <div class="p-5 border-b border-gray-400 last:border-0">
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          <i class="w-3 fad fa-globe"></i>
          Components
        </p>
        <select v-model="col.component" @change="setComponentData(col)">
          <option value="block">Empty</option>

          <optgroup
            :label="category['name']"
            v-for="(category, index) in categoriesDropdown()"
            :key="index"
          >
            <option
              :value="component.id"
              v-for="(component, index) in componentDropdown(category)"
              :key="index"
            >
              {{ component.name }}
            </option>
          </optgroup>
        </select>
      </div>

      <div
        class="p-5 border-b border-gray-400 last:border-0"
        v-if="col.component !== 'block' && col.component !== 'content'"
      >
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          <i class="w-3 fad fa-swatchbook"></i>
          Variants
        </p>
        <select v-model="col.variant" @change="resetFields">
          <option
            :value="variant.id"
            v-for="(variant, index) in components.find(
              (obj) => obj.id === col.component
            ).variants"
            :key="index"
          >
            {{ variant.name }}
          </option>
        </select>
      </div>
    </template>

    <template v-if="col.component !== 'block' && col.component !== 'content'">
      <div
        class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400"
        v-if="
          components.find((obj) => obj.id === col.component).fields.length >
            0 &&
          components.find((obj) => obj.id === col.component).fields[0].type !==
            'locale'
        "
      >
        {{ components.find((obj) => obj.id === col.component).name }}
      </div>

      <Renderer
        :isGroup="false"
        :components="components"
        :pageSelect="pageSelect"
        :col="col"
        :fields="components.find((obj) => obj.id === col.component).fields"
        :pages="pages"
        :lang="lang"
      >
      </Renderer>

      <SettingSyle
        :active="col"
        apply="col"
        v-if="
          col.component !== 'navigation' &&
          col.component !== 'footer' &&
          user_roles.includes('administrator')
        "
      ></SettingSyle>
    </template>
  </div>
</template>

<script>
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import SettingSyle from "../Settings/SettingSyle";
import bus from "../../../../../js/pages/bus.js";

export default {
  props: [
    "components",
    "activeCol",
    "fields",
    "categories",
    "page",
    "pages",
    "lang",
    "navigation",
    "pageSelect",
  ],
  components: {
    Loading,
    SettingSyle,
  },
  computed: {},
  data() {
    return {
      isLoading: false,
      user_roles: window.wp_user_roles,
      col: this.activeCol,
      templates: {},
    };
  },
  methods: {
    getTemplates() {
      this.isLoading = true;
      if (!this.col.template) {
        this.$set(this.col, "template", "");
      }
      axios({
        method: "post",
        url: `/wp-json/v1/sweepbright/pages/column`,
      }).then((response) => {
        this.templates = response.data.DATA;

        if (this.templates && !this.templates[`col_${this.col.template}`]) {
          this.$set(this.col, "template", "");
        }
        this.setTemplate(true);
        this.isLoading = false;
      });
    },
    saveTemplate() {
      this.$dialog.prompt({
        title: "Save template",
        body: "Template name",
      }).then((dialog) => {
        if (dialog.data) {
          this.isLoading = true;
          axios({
            method: "post",
            url: `/wp-json/v1/sweepbright/pages/column/save`,
            data: {
              name: dialog.data,
              data: this.col,
            },
          }).then((response) => {
            this.templates = response.data.DATA;
            this.$set(this.col, "template", response.data.NAME);
            this.isLoading = false;
            this.$toast.open({
              message: "Saved template.",
              type: "success",
            });
          });
        } else {
          this.$dialog.alert('No name given');
        }
      });
    },
    setTemplate(skip) {
      this.$forceUpdate();
      if (this.col.template) {
        const template = Object.entries(this.templates).find(([key, template]) => {
          return template.name === this.col.template;
        })[1];
        this.$set(this.col, "component", template.data.component);
        this.$set(this.col, "data", template.data.data);
        this.$set(this.col, "style", template.data.style);
        this.$set(this.col, "variant", template.data.variant);
        this.$set(this.col, "width", template.data.width);

        if (!skip) {
          this.$toast.open({
            message: `Switched template: ${this.col.template}`,
            type: "success",
          });
        }
      }
    },
    deleteTemplate() {
      this.$dialog.confirm("Please confirm to delete template.").then(() => {
        this.isLoading = true;
        axios({
            method: "post",
            url: `/wp-json/v1/sweepbright/pages/column/delete`,
            data: {
              name: this.col.template,
            },
          }).then((response) => {
            this.templates = response.data.DATA;
            this.$set(this.col, "template", '');
            this.isLoading = false;
            this.$toast.open({
              message: "Deleted template.",
              type: "success",
            });
          });
      });
    },
    detachTemplate() {
      this.$set(this.col, "template", "");
      this.$forceUpdate();
    },
    categoriesDropdown() {
      let categories = [];
      this.categories.forEach((category) => {
        const total = this.componentDropdown(category).length;
        if (total > 0) {
          categories.push(category);
        }
      });
      return categories;
    },
    componentDropdown(category) {
      const components = this.components.filter((obj) => {
        let condition = obj.category === category["value"];
        if (
          obj.category === category["value"] &&
          obj.navigations &&
          obj.navigations.indexOf(this.navigation) > -1
        ) {
          condition = true;
        } else if (
          obj.category === category["value"] &&
          obj.navigations &&
          obj.navigations.indexOf(this.navigation) === -1
        ) {
          condition = false;
        } else if (
          obj.category === category["value"] &&
          obj.type === "estate" &&
          obj.type !== this.page.settings.template
        ) {
          condition = false;
        } else if (
          obj.category === category["value"] &&
          obj.type === "blog" &&
          obj.type !== this.page.settings.template
        ) {
          condition = false;
        }
        return condition;
      });

      return components.sort(function (a, b) {
        var textA = a.name.toUpperCase();
        var textB = b.name.toUpperCase();
        return textA < textB ? -1 : textA > textB ? 1 : 0;
      });
    },
    setWidth() {
      bus.$emit("setWidth", this.col.width);
    },
    setComponentData(col) {
      bus.$emit("setComponentData", col);
    },
    resetFields() {
      bus.$emit("resetFields");
    },
    syncFields() {
      if (this.col.component !== "block" && this.col.component !== "content") {
        const fields = this.components.find(
          (obj) => obj.id === this.col.component
        ).fields;

        Object.entries(this.col.data.default).forEach(([key, colDefaults]) => {
          const field = fields.find((field) => field.id === key);

          // Add non-existing fields
          if (!this.col.data.default[key]) {
            this.$set(this.col.data.default, key, field.default);
          }

          // Sync locale values (these are hardcoded)
          if (key === "locale") {
            this.$set(this.col.data.default, key, field.default);
          }
        });
      }
    },
  },
  mounted() {
    bus.$on("setCol", (col) => {
      this.col = col;
      if (this.col) {
        this.getTemplates();
      }
    });
    this.getTemplates();
    this.syncFields();
  },
  beforeDestroy() {
    bus.$off("setCol");
  },
};
</script>
