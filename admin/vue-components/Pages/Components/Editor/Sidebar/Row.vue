<template>
  <div>
    <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
      Row settings
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
      <select v-model="row.template" @change="setTemplate" v-if="templates !== null && typeof templates != 'undefined' && Object.keys(templates).length > 0">
        <option value="" disabled>-- Choose a template --</option>
        <option :value="template.name" v-for="(template, index) in templates" :key="index">
          {{ template.name }}
        </option>
      </select>
      <div class="flex mt-5 space-x-5">
        <div class="flex-1" v-if="row.template && templates !== null && typeof templates != 'undefined' && Object.keys(templates).length > 0">
          <button class="flex items-center justify-center w-full btn btn-default" @click="deleteTemplate">
            <i class="mr-2 text-base text-gray-500 fad fa-trash"></i>
            Delete
          </button>
        </div>
        <div class="flex-1" v-if="row.template">
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
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-expand-wide"></i>
        Container
      </p>
      <select v-model="row.style.container" @change="setStyle">
        <option value="contained">Centered (default)</option>
        <option value="background">Centered (+ background)</option>
        <option value="large">Large</option>
        <option value="full-width">Full width</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-arrow-alt-to-bottom"></i>
        Margin bottom
      </p>
      <select v-model="row.style.margin_bottom" @change="setStyle">
        <option value="mb-0">None</option>
        <option value="mb-10">Normal</option>
        <option value="mb-20">Medium (default)</option>
        <option value="mb-32">Large</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-arrows-h"></i>
        Horizontal alignment
      </p>
      <select v-model="row.style.justify_align" @change="setStyle">
        <option value="">Auto (default)</option>
        <option value="justify-start">Start</option>
        <option value="justify-center">Centered</option>
        <option value="justify-between">Between</option>
        <option value="justify-end">End</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-arrows-v"></i>
        Vertical alignment
      </p>
      <select v-model="row.style.items_align" @change="setStyle">
        <option value="items-start">Start (default)</option>
        <option value="items-center">Centered</option>
        <option value="items-end">End</option>
        <option value="items-stretch">Stretch</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-mobile-alt"></i>
        Mobile: direction
      </p>
      <select v-model="row.style.mobile_direction" @change="setStyle">
        <option value="flex-col">Normal (default)</option>
        <option value="flex-col-reverse">Reverse</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-mobile-alt"></i>
        Mobile: columns per row
      </p>
      <select v-model="row.style.columns_per_row" @change="setStyle">
        <option value="">1 (default)</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
      </select>
    </div>
    <SettingSyle :active="row" apply="row"></SettingSyle>
  </div>
</template>

<script>
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { v4 as uuidv4 } from "uuid";
import SettingSyle from "../Settings/SettingSyle";
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["activeRow"],
  components: {
    Loading,
    SettingSyle,
  },
  computed: {},
  data() {
    return {
      isLoading: false,
      row: this.activeRow,
      templates: {},
    };
  },
  methods: {
    getTemplates() {
      this.isLoading = true;
      if (!this.row.template) {
        this.$set(this.row, "template", "");
      }

      axios({
        method: "post",
        url: `/wp-json/v1/sweepbright/pages/row`,
      }).then((response) => {
        this.templates = response.data.DATA;

        if (this.templates && !this.templates[`row_${this.row.template}`]) {
          this.$set(this.row, "template", "");
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
            url: `/wp-json/v1/sweepbright/pages/row/save`,
            data: {
              name: dialog.data,
              data: this.row,
            },
          }).then((response) => {
            this.templates = response.data.DATA;
            this.$set(this.row, "template", response.data.NAME);
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
      if (this.row.template) {
        const template = Object.entries(this.templates).find(([key, template]) => {
          return template.name === this.row.template;
        })[1];

        const columns = template.data.columns;

        columns.forEach(col => {
          col.id = uuidv4();
          col.rowId = this.row.id;
        });

        this.$set(this.row, "style", template.data.style);
        this.$set(this.row, "columns", columns);

        if (!skip) {
          this.$toast.open({
            message: `Switched template: ${this.row.template}`,
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
            url: `/wp-json/v1/sweepbright/pages/row/delete`,
            data: {
              name: this.row.template,
            },
          }).then((response) => {
            this.templates = response.data.DATA;
            this.$set(this.row, "template", '');
            this.isLoading = false;
            this.$toast.open({
              message: "Deleted template.",
              type: "success",
            });
          });
      });
    },
    detachTemplate() {
      this.$set(this.row, "template", "");
      this.$forceUpdate();
    },
    setDefaults() {
      if (!this.row.style.columns_per_row) {
        this.$set(this.row.style, "columns_per_row", "");
      }
    },
    setStyle() {
      bus.$emit("setStyle", this.row.style);
    },
  },
  mounted() {
    this.getTemplates();
    this.setDefaults();

    bus.$on("setRow", (row) => {
      this.row = row;
      this.getTemplates();
      this.setDefaults();
    });
  },
  beforeDestroy() {
    bus.$off("setRow");
  },
};
</script>
