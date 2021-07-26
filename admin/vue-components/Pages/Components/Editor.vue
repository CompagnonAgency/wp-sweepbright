<template>
  <div class="absolute h-full bg-gray-300 pages-container">
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

    <template v-if="lang">
      <!-- Navigation -->
      <Navigation
        :isLoaded="isLoaded"
        :page="page"
        :defaultLang="lang"
        :settings="settings"
      ></Navigation>

      <!-- Canvas -->
      <Canvas
        :isLoaded="isLoaded"
        :page="page"
        :components="components"
        :activeRow="activeRow"
        :activeCol="activeCol"
      ></Canvas>

      <!-- Sidebar -->
      <Sidebar
        :isLoaded="isLoaded"
        :page="page"
        :components="components"
        :categories="categories"
        :activeRow="activeRow"
        :activeCol="activeCol"
        :pageSelect="page_select_multiple"
        :lang="lang"
        :pages="pages"
        :navigation="navigation"
      ></Sidebar>
    </template>
  </div>
</template>

<script>
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { v4 as uuidv4 } from "uuid";
import bus from "../../../js/pages/bus.js";

import Navigation from "./Editor/Navigation";
import Canvas from "./Editor/Canvas";
import Sidebar from "./Editor/Sidebar";

export default {
  components: {
    Loading,
    Navigation,
    Canvas,
    Sidebar,
  },
  data() {
    return {
      isLoading: false,
      isLoaded: false,
      activeCol: false,
      activeRow: false,
      settings: {},
      components: [],
      categories: [],
      lang: "",
      navigation: false,
      base: [],
      page: [],
      pages: [],
      link: "",
      page_select_multiple: {
        active: false,
      },
    };
  },
  methods: {
    preview() {
      const win = window.open(this.link, "_blank");
      win.focus();
    },
    getSettings() {
      this.isLoading = true;
      axios({
        method: "get",
        url: "/wp-json/v1/sweepbright/pages/settings",
      }).then((response) => {
        this.settings = response.data.DATA;
        this.isLoading = false;
        this.isLoaded = true;
      });
    },
    getPage() {
      this.isLoading = true;
      axios({
        method: "get",
        url: `/wp-json/v1/sweepbright/pages/${this.$route.params.id}`,
      }).then((response) => {
        this.lang = response.data.LANG;
        this.page = response.data.PAGE;
        this.link = response.data.LINK;
        this.pages = response.data.PAGES;

        this.loadCategories(response.data.CATEGORIES);
        this.components = response.data.COMPONENTS.data;

        if (this.$route.params.id !== "base") {
          this.base = response.data.BASE.layout;
        }
        this.init();
        this.getSettings();
      });
    },
    init() {
      if (this.page.layout.length === 0) {
        this.insertRow();
      }

      this.setNavigation();
      this.sortLayout();
    },
    savePage() {
      this.isLoading = true;
      axios({
        method: "post",
        url: `/wp-json/v1/sweepbright/pages/save`,
        data: {
          page: this.page,
        },
      }).then((response) => {
        this.page.updated = response.data.UPDATED;
        this.link = response.data.LINK;
        this.isLoading = false;

        if (this.page.id === "create") {
          this.page.id = response.data.ID;
          this.$router.push({ name: "editor", params: { id: this.page.id } });

          this.$toast.open({
            message: "Published successfully.",
            type: "success",
          });
        } else {
          this.$toast.open({
            message: "Saved successfully.",
            type: "success",
          });
        }
      });
    },
    loadCategories(categories) {
      categories.forEach((category) => {
        if (category["value"] !== "layout") {
          this.categories.push(category);
        } else if (category["value"] === "layout" && this.page.id === "base") {
          this.categories.push(category);
        }
      });
    },
    addPage(col, field) {
      const id = this.pages.find(
        (obj) => obj.post_name === this.page_select_multiple.active
      ).ID;
      const uuid = uuidv4();

      if (!col.data.default[field.id]) {
        col.data.default[field.id] = [];
      }
      const page = {
        id,
        uuid,
        slug: this.page_select_multiple.active,
        order: col.data.default[field.id].length,
      };
      col.data.default[field.id].push(page);
    },
    addImage(id, url, col, field) {
      if (!col.data.default[field.id]) {
        col.data.default[field.id] = [];
      }
      const image = {
        id,
        url,
        order: col.data.default[field.id].length,
      };
      col.data.default[field.id].push(image);
    },
    addGroup(fields, col, field) {
      const colUid = uuidv4();
      const group = {
        id: colUid,
        field: field.id,
        fields,
        order: col.data.default[field.id].length,
      };
      col.data.default[field.id].push(group);
      this.$forceUpdate();
    },
    endDrag(e, pages) {
      pages.splice(e.newIndex, 0, pages.splice(e.oldIndex, 1)[0]);
      pages.forEach(function (item, index) {
        item.order = index;
      });
      this.$forceUpdate();
    },
    resetPageOrder(field, col) {
      col.data.default[field["id"]].forEach((page, index) => {
        page.order = index;
      });
    },
    deletePage(field, id, col) {
      col.data.default[field["id"]] = col.data.default[field["id"]].filter(
        (obj) => {
          return obj.uuid !== id;
        }
      );
      this.resetPageOrder(field, col);
      this.$forceUpdate();
    },
    deleteItem(field, id, col) {
      col.data.default[field["id"]] = col.data.default[field["id"]].filter(
        (obj) => {
          return obj.id !== id;
        }
      );
      this.resetPageOrder(field, col);
      this.$forceUpdate();
    },
    resetFields() {
      this.page_select_multiple.active = this.pages[0].post_name;
    },
    setComponentData(col) {
      this.resetFields();
      const component = this.components.find((obj) => obj.id === col.component);

      if (component) {
        if (component.type === "navigation") {
          col.type = "navigation";
        } else {
          delete col.type;
        }

        // Garbage collection > remove any existing data first
        this.$set(col.data, "nl", {});
        this.$set(col.data, "fr", {});
        this.$set(col.data, "en", {});
        this.$set(col.data, "default", {});

        // Set the data
        component.fields.forEach((field) => {
          const data = field.default;
          if (field.sync) {
            this.$set(col.data.default, field.id, data);
          } else {
            this.$set(col.data.en, field.id, data);
            this.$set(col.data.nl, field.id, data);
            this.$set(col.data.fr, field.id, data);
          }
        });
      }
    },
    highlightCol(col) {
      this.activeRow = false;
      this.resetFields();

      // This is needed when a page contains no data and the object is empty.
      // Empty objects are saved and returned as an empty array in PHP, so it breaks reactivity in Vue.
      if (col.data.nl.length === 0) {
        col.data.nl = {};
      }
      if (col.data.fr.length === 0) {
        col.data.fr = {};
      }
      if (col.data.en.length === 0) {
        col.data.en = {};
      }
      if (col.data.default.length === 0) {
        col.data.default = {};
      }

      if (this.activeCol && this.activeCol.id === col.id) {
        this.activeCol = false;
      } else {
        this.activeCol = col;
      }

      bus.$emit("setCol", this.activeCol);
    },
    deselect() {
      this.activeCol = false;
      this.activeRow = false;
    },
    insertColumn(rowId, colId) {
      // Current row
      const row = this.page.layout.find((obj) => {
        return obj.id === rowId;
      });

      // Order
      let order = 0;

      if (colId) {
        // Current column
        const column = row.columns.find((obj) => {
          return obj.id === colId;
        });

        // Set order
        order = column.order + 1;

        // Set higher orders
        row.columns = row.columns.filter((obj) => {
          if (obj.order >= order && obj.rowId === rowId) {
            obj.order += 1;
          }
          return obj;
        });
      }

      // Add column
      const colUid = uuidv4();

      row.columns.push({
        id: colUid,
        rowId: rowId,
        width: "w-full",
        component: "block",
        variant: "default",
        data: {
          nl: {},
          fr: {},
          en: {},
          default: {},
        },
        order,
      });

      // Sort layout
      this.sortLayout();
    },
    removeColumn(rowId, id) {
      this.$dialog
        .confirm("Please confirm to delete column.")
        .then((dialog) => {
          // Current row
          const row = this.page.layout.find((obj) => {
            return obj.id === rowId;
          });

          if (row.columns.length > 1) {
            // Current column
            const column = row.columns.find((obj) => {
              return obj.id === id;
            });

            // Remove row
            row.columns = row.columns.filter((obj) => {
              return obj.id !== id;
            });

            // Set higher orders
            row.columns = row.columns.filter((obj) => {
              if (obj.order >= column.order && obj.rowId === rowId) {
                obj.order -= 1;
              }
              return obj;
            });
            this.sortLayout();
            this.deselect();
          }
        });
    },
    insertRow(id) {
      let order = 0;

      if (this.page.layout.length > 0) {
        // Set order
        const previous = this.page.layout.find((obj) => {
          return obj.id === id;
        });

        order = previous.order + 1;

        // Set higher orders
        this.page.layout = this.page.layout.filter((obj) => {
          if (obj.order >= order && obj.columns) {
            obj.order += 1;
          }
          return obj;
        });
      }

      // Add row
      const rowId = uuidv4();

      this.page.layout.push({
        id: rowId,
        order,
        style: {
          container: "contained",
          justify_align: "",
          items_align: "items-start",
          mobile_direction: "flex-col",
          margin_bottom: "mb-20",
        },
        columns: [],
      });

      // Add column
      this.insertColumn(rowId, false);
    },
    editRow(id) {
      this.activeCol = false;
      this.activeRow = this.page.layout.find((obj) => {
        return obj.id === id;
      });
      bus.$emit("setRow", this.activeRow);
    },
    removeRow(id) {
      if (this.page.layout.length > 1) {
        this.$dialog.confirm("Please confirm to delete row").then((dialog) => {
          // Current row
          const row = this.page.layout.find((obj) => {
            return obj.id === id;
          });

          // Remove row
          this.page.layout = this.page.layout.filter((obj) => {
            return obj.id !== id;
          });

          // Set higher orders
          this.page.layout = this.page.layout.filter((obj) => {
            if (obj.order >= row.order && obj.columns) {
              obj.order -= 1;
            }
            return obj;
          });
          this.sortLayout();
          this.deselect();
        });
      }
    },
    sortLayout() {
      this.page.layout.sort((a, b) =>
        a.order < b.order ? -1 : a.order > b.order ? 1 : 0
      );

      this.page.layout.forEach((row) => {
        row.columns.sort((a, b) =>
          a.order < b.order ? -1 : a.order > b.order ? 1 : 0
        );
      });
    },
    setNavigation() {
      if (this.base) {
        this.base.forEach((row) => {
          const column = row.columns.find((obj) => {
            return obj.type === "navigation";
          });

          if (column) {
            this.navigation = column.component;
          }
        });
      }
    },
    listeners() {
      bus.$on("savePage", () => {
        this.savePage();
      });

      bus.$on("preview", () => {
        this.preview();
      });

      bus.$on("setLang", (lang) => {
        this.lang = lang;
      });

      bus.$on("deselect", () => {
        this.deselect();
      });

      bus.$on("highlightCol", (column) => {
        this.highlightCol(column);
      });

      bus.$on("removeColumn", (args) => {
        this.removeColumn(args.rowId, args.columnId);
      });

      bus.$on("insertColumn", (args) => {
        this.insertColumn(args.rowId, args.columnId);
      });

      bus.$on("removeRow", (rowId) => {
        this.removeRow(rowId);
      });

      bus.$on("editRow", (rowId) => {
        this.editRow(rowId);
      });

      bus.$on("insertRow", (rowId) => {
        this.insertRow(rowId);
      });

      bus.$on("setWidth", (width) => {
        this.activeCol.width = width;
      });

      bus.$on("setComponentData", (col) => {
        this.setComponentData(col);
      });

      bus.$on("resetFields", () => {
        this.resetFields();
      });

      bus.$on("addPage", (args) => {
        this.addPage(args.col, args.field);
      });

      bus.$on("addImage", (args) => {
        this.addImage(args.id, args.url, args.col, args.field);
      });

      bus.$on("addGroup", (args) => {
        this.addGroup(args.fields, args.col, args.field);
      });

      bus.$on("deleteItem", (args) => {
        this.deleteItem(args.field, args.id, args.col);
      });

      bus.$on("deletePage", (args) => {
        this.deletePage(args.field, args.id, args.col);
      });

      bus.$on("setFields", (fields) => {
        this.page_select_multiple = fields;
      });

      bus.$on("setStyle", (style, apply) => {
        if (apply === "row") {
          this.activeRow.style = style;
        } else if (apply === "col") {
          this.activeCol.style = style;
        }
      });

      bus.$on("setSettings", (settings) => {
        this.page.settings = settings;
      });

      bus.$on("setSlug", (slug) => {
        this.page.slug = slug;
      });

      bus.$on("endDrag", (args) => {
        this.endDrag(args.$event, args.data);
      });
    },
  },
  mounted() {
    if (document.querySelector("#wpfooter")) {
      document.querySelector("#wpfooter").remove();
    }
    this.listeners();
    this.getPage();
  },
  beforeDestroy() {
    bus.$off();
  },
};
</script>

<style>
:root {
  --topOffset: 0px;
  --containerWidthOffset: 20px;
  --wordpressSidebar: 160px;
  --editorSidebar: 400px;
  --offsetTop: 84px;
  --toolbarHeight: 52px;
}

.pages-navigation {
  width: calc(100% - var(--wordpressSidebar));
}

.pages-canvas {
  margin-top: var(--toolbarHeight);
  margin-left: var(--wordpressSidebar);
  width: calc(100% - var(--editorSidebar) - var(--wordpressSidebar));
  height: calc(100vh - var(--offsetTop));

  @apply fixed left-0 z-0 overflow-hidden overflow-y-auto;
}

.pages-sidebar {
  margin-top: var(--toolbarHeight);
  width: var(--editorSidebar);
  height: calc(100vh - var(--offsetTop));

  @apply fixed right-0 z-0 border-l border-gray-400 overflow-hidden overflow-y-auto;
}

.pages-container {
  top: var(--topOffset);
  margin-left: calc(1px - var(--containerWidthOffset));
  width: calc(100% + var(--containerWidthOffset));
  min-height: 100%;
}

.v-toast__item {
  @apply shadow-md rounded-lg;
}

.v-toast__item--success {
  @apply bg-blue-500;
}

.v-toast__item .v-toast__icon {
  width: 22px;
  min-width: 22px;
  height: 22px;
}

.v-toast__item .v-toast__text {
  @apply py-1 pr-4 pl-2 text-sm;
}

@media only screen and (max-width: 960px) {
  :root {
    --wordpressSidebar: 36px;
  }
}

@media only screen and (max-width: 782px) {
  :root {
    --wordpressSidebar: 0px;
    --containerWidthOffset: 10px;
    --toolbarHeight: 50px;
    --offsetTop: 96px;
  }
}

@media only screen and (max-width: 600px) {
  :root {
    --topOffset: 45px;
  }
}
</style>
