<template>
  <div>
    <div class="mb-5 divide-gray-200 divide-y-1">
      <draggable
        v-if="col.data.default[field.id]"
        v-model="col.data.default[field.id]"
        @change="endDrag($event, col.data.default[field.id])"
      >
        <div
          class="flex items-center p-3 cursor-pointer hover:bg-gray-200"
          v-for="page in col.data.default[field.id]"
          :key="page.uuid"
        >
          <i class="mr-3 text-lg text-gray-500 cursor-move far fa-bars"></i>
          <p class="flex-1 text-base font-medium text-gray-700">
            <template v-if="pages.find((obj) => obj.ID === page.id)">
              {{ pages.find((obj) => obj.ID === page.id).post_title }}
            </template>
            <template v-else>[Page removed]</template>
          </p>
          <i
            v-tooltip="{ content: 'Remove page' }"
            @click="deletePage(field, page.uuid)"
            class="ml-3 text-lg text-red-500 far fa-trash"
          ></i>
        </div>
      </draggable>
    </div>
    <select class="mb-5" v-model="defaultFields.active" @change="setFields">
      <option
        v-for="(page, index) in pages"
        :key="index"
        :value="page.post_name"
      >
        {{ page.post_title }}
      </option>
    </select>
    <button class="btn btn-primary" @click="addPage(col, field)">
      Add Page
    </button>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["pages", "col", "pageSelect", "field"],
  components: {
    draggable,
  },
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
    endDrag($event, data) {
      bus.$emit("endDrag", {
        $event,
        data,
      });
      this.$forceUpdate();
    },
  },
  mounted() {
    this.$forceUpdate();
  },
};
</script>
