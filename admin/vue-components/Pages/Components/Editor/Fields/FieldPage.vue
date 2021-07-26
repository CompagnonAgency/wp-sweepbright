<template>
  <div>
    <div
      class="flex items-center"
      v-for="page in col.data.default[field.id]"
      :key="page.id"
    >
      <p class="flex-1 text-base font-medium text-gray-700">
        <template v-if="pages.find((obj) => obj.ID === page.id)">
          {{ pages.find((obj) => obj.ID === page.id).post_title }}
        </template>
        <template v-else>[Page removed]</template>
      </p>
      <i
        v-tooltip="{ content: 'Remove page' }"
        @click="deletePage(field, page.uuid)"
        class="ml-3 text-lg text-red-500 cursor-pointer far fa-trash"
      ></i>
    </div>
    <select
      v-model="defaultFields.active"
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
      class="mt-5 btn btn-primary"
      @click="addPage(col, field)"
    >
      Add Page
    </button>
  </div>
</template>

<script>
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["pages", "col", "pageSelect", "field"],
  components: {},
  computed: {},
  data() {
    return {
      defaultFields: this.pageSelect,
    };
  },
  methods: {
    addPage(col, field) {
      bus.$emit("addPage", {
        col,
        field,
      });
      this.$forceUpdate();
    },
    deletePage(field, id) {
      bus.$emit("deletePage", {
        field,
        id,
        col: this.col,
      });
      this.$forceUpdate();
    },
    setFields() {
      bus.$emit("setFields", this.defaultFields);
    },
  },
  mounted() {
    this.$forceUpdate();
  },
};
</script>
