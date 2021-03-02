<template>
  <div class="absolute top-0 h-full bg-gray-300 pages-container">
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

    <!-- Navigation -->
    <Navigation
      :isLoaded="isLoaded"
      :page="page"
      :defaultLang="lang"
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
      :fields="fields"
      :lang="lang"
      :pages="pages"
      :navigation="navigation"
    ></Sidebar>
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
      components: [],
      categories: [],
      lang: "nl",
      navigation: false,
      base: [],
      page: [],
      pages: [],
      link: "",
      fields: {
        page_select_multiple: {
          active: false,
        },
      },
    };
  },
  methods: {
    preview() {
      const win = window.open(this.link, "_blank");
      win.focus();
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
        this.isLoaded = true;
        this.init();
        this.isLoading = false;
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
    sortedPages(pages) {
      let output = [];
      if (pages) {
        output = pages.sort((a, b) =>
          a.order < b.order ? -1 : a.order > b.order ? 1 : 0
        );
      }
      return output;
    },
    addPage(col, field) {
      const id = this.pages.find(
        (obj) => obj.post_name === this.fields.page_select_multiple.active
      ).ID;

      if (!col.data.default[field.id]) {
        col.data.default = {};
      }

      if (col.data.default[field.id]) {
        col.data.default[field.id].push({
          id,
          slug: this.fields.page_select_multiple.active,
          order: col.data.default[field.id].length,
        });
      } else {
        col.data.default[field.id] = [];
        col.data.default[field.id].push({
          id,
          slug: this.fields.page_select_multiple.active,
          order: col.data.default[field.id].length,
        });
      }
      this.$forceUpdate();
    },
    endDrag(e, pages) {
      pages.splice(e.newIndex, 0, pages.splice(e.oldIndex, 1)[0]);
      pages.forEach(function (item, index) {
        item.order = index;
      });
      this.$forceUpdate();
    },
    resetPageOrder(field) {
      this.activeCol.data.default[field["id"]].forEach((page, index) => {
        page.order = index;
      });
    },
    deletePage(field, order) {
      this.activeCol.data.default[field["id"]] = this.activeCol.data.default[
        field["id"]
      ].filter((obj) => {
        return obj.order !== order;
      });
      this.resetPageOrder(field);
      this.$forceUpdate();
    },
    resetFields() {
      this.fields.page_select_multiple.active = this.pages[0].post_name;
    },
    setComponentData(col) {
      this.resetFields();
      const component = this.components.find((obj) => obj.id === col.component);

      col.data = {
        nl: {},
        fr: {},
        en: {},
        default: {},
      };

      if (component) {
        if (component.type === "navigation") {
          col.type = "navigation";
        } else {
          delete col.type;
        }

        component.fields.forEach((field) => {
          if (!field.sync) {
            col.data["nl"][field["id"]] = "";
            col.data["en"][field["id"]] = "";
            col.data["fr"][field["id"]] = "";
          } else {
            col.data["default"][field["id"]] = "";
          }
        });
      }
    },
    highlightCol(col) {
      this.activeRow = false;
      this.resetFields();

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
          padding: "p-0",
          background: false,
          background_color: "",
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

      bus.$on("deletePage", (args) => {
        this.deletePage(args.field, args.order);
      });

      bus.$on("setFields", (fields) => {
        this.fields = fields;
      });

      bus.$on("setStyle", (style) => {
        this.activeRow.style = style;
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
.pages-navigation {
  width: calc(100% - 160px);
}

.pages-canvas {
  margin-top: 52px;
  margin-left: 160px;
  width: calc(100% - 300px - 160px);
  height: calc(100vh - 84px);

  @apply fixed left-0 z-0 overflow-hidden overflow-y-auto;
}

.pages-sidebar {
  margin-top: 52px;
  width: 300px;
  height: calc(100vh - 84px);

  @apply fixed right-0 z-0 border-l border-gray-400 overflow-hidden overflow-y-auto;
}

.pages-container {
  margin-left: -20px;
  width: calc(100% + 20px);
  min-height: 100%;
  height: calc(100vh - 32px);
}
</style>
