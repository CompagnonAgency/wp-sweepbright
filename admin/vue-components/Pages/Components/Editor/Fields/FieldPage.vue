<template>
  <div>
    <div class="mb-5">
      <div
        class="flex items-center"
        v-for="page in col.data.default[field.id]"
        :key="page.order"
      >
        <p class="flex-1 text-base font-medium text-gray-700">
          <template v-if="pages.find((obj) => obj.post_name === page.slug)">
            {{ pages.find((obj) => obj.post_name === page.slug).post_title }}
          </template>
          <template v-else>[Page removed]</template>
        </p>
        <i
          v-tooltip="{ content: 'Remove page' }"
          @click="deletePage(field, page.order)"
          class="ml-3 text-lg text-red-500 cursor-pointer far fa-trash"
        ></i>
      </div>
    </div>

    <select
      class="mb-5"
      v-model="defaultFields.page_select_multiple.active"
      @change="setFields"
      v-if="
        !col.data.default[field.id] || col.data.default[field.id].length === 0
      "
    >
      <option
        v-for="(page, index) in pages"
        :key="index"
        :value="page.post_name"
      >
        {{ page.post_title }}
      </option>
    </select>

    <button
      v-if="
        !col.data.default[field.id] || col.data.default[field.id].length === 0
      "
      class="btn btn-primary"
      @click="addPage(col, field)"
    >
      Add Page
    </button>
  </div>
</template>

<script>
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["pages", "activeCol", "fields", "field"],
  components: {},
  computed: {},
  data() {
    return {
      defaultFields: this.fields,
      col: this.activeCol,
    };
  },
  methods: {
    addPage(col, field) {
      bus.$emit("addPage", {
        col,
        field,
      });
    },
    deletePage(field, order) {
      bus.$emit("deletePage", {
        field,
        order,
      });
    },
    setFields() {
      bus.$emit("setFields", this.defaultFields);
    },
  },
  mounted() {},
};
</script>
