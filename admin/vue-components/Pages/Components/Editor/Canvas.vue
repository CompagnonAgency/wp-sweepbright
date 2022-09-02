<template>
  <div
    class="p-10 bg-gray-200 pages-canvas"
    @click.self="deselect()"
    v-if="isLoaded"
  >
    <div class="max-w-screen-lg mx-auto">
      <!-- Row -->
      <draggable
        v-model="page.layout"
        group="rows"
        handle=".handle-row"
        @start="drag=true"
        @end="dragRowEnd"
      >
        <div
          class="p-2 rounded group"
          :class="`${
            activeRow.id === row.id
              ? 'bg-blue-200 hover:border-blue-400 shadow'
              : ''
          }`"
          v-for="(row, rowIndex) in page.layout"
          :key="rowIndex"
        >
          <div
            v-if="row.order === 0"
            class="flex justify-center mb-5 space-x-2 transition duration-200 opacity-0 group-hover:opacity-100"
            @click.self="deselect()"
          >
            <div
              v-tooltip="{ content: 'Insert row ↓' }"
              @click="insertRowBefore(row.id)"
              class="flex items-center justify-center w-8 h-8 text-white transition duration-200 bg-blue-500 rounded-full cursor-pointer hover:bg-blue-400 active:bg-blue-600"
              v-if="user_roles.includes('administrator')"
            >
              <i class="text-lg fal fa-plus"></i>
            </div>
          </div>

          <!-- Column -->
          <draggable
            class="relative flex space-x-5"
            :class="`${row.style.justify_align}`"
            v-model="row.columns"
            group="columns"
            @start="drag=true"
            @end="drag=false; dragEnd(row)"
          >
            <div
              v-for="(column, columnIndex) in row.columns"
              :key="columnIndex"
              class="relative p-6 text-center transition duration-200 border-2 rounded cursor-pointer "
              :class="`${column.width} ${
                activeCol.id === column.id || activeRow.id === row.id
                  ? 'border-blue-500 bg-blue-200 text-blue-500 hover:border-blue-400'
                  : 'border-gray-400 border-dashed text-gray-500 hover:border-gray-500'
              }`"
              @click="highlightCol(column)"
            >
              <div
                class="absolute right-0 -mr-4 space-y-2 transition duration-200 transform -translate-y-1/2 opacity-0 group-hover:opacity-100"
                style="top: 50%"
              >
                <div
                  class="flex items-center justify-center w-8 h-8 text-white transition duration-200 bg-red-500 rounded-full cursor-pointer hover:bg-red-400 active:bg-red-600"
                  @click="removeColumn(row.id, column.id)"
                  v-if="
                    row.columns.length > 1 && user_roles.includes('administrator')
                  "
                  v-tooltip="{ content: 'Remove column' }"
                >
                  <i class="text-lg far fa-trash"></i>
                </div>
                <div
                  class="flex items-center justify-center w-8 h-8 text-white transition duration-200 bg-blue-500 rounded-full cursor-pointer hover:bg-blue-400 active:bg-blue-600"
                  @click="insertColumn(row.id, column.id)"
                  v-tooltip="{ content: 'Insert column →' }"
                  v-if="user_roles.includes('administrator')"
                >
                  <i class="text-lg fal fa-plus"></i>
                </div>
              </div>

              <template v-if="column.component !== 'block'">
                <i
                  :class="`mb-3 text-5xl opacity-80 ${
                    components.find((obj) => obj.id === column.component).icon
                  }`"
                ></i>
              </template>
              <template v-else>
                <i class="mb-3 text-5xl opacity-80 fal fa-plus-circle"></i>
              </template>

              <p class="text-sm font-medium tracking-wide uppercase">
                <template v-if="column.component !== 'block'">
                  {{ components.find((obj) => obj.id === column.component).name }}
                </template>
                <template v-else>Add component</template>
              </p>
            </div>
          </draggable>

          <div
            class="flex justify-center mt-5 space-x-2 transition duration-200 opacity-0 group-hover:opacity-100"
            @click.self="deselect()"
          >
            <div
              class="flex items-center justify-center w-8 h-8 text-white transition duration-200 bg-red-500 rounded-full cursor-pointer hover:bg-red-400 active:bg-red-600"
              v-tooltip="{ content: 'Remove row' }"
              v-if="
                page.layout.length > 1 && user_roles.includes('administrator')
              "
              @click="removeRow(row.id)"
            >
              <i class="text-lg far fa-trash"></i>
            </div>
            <div
              class="flex items-center justify-center w-8 h-8 text-white transition duration-200 bg-green-500 rounded-full cursor-pointer hover:bg-green-400 active:bg-green-600"
              v-tooltip="{ content: 'Edit row' }"
              @click="editRow(row.id)"
              v-if="user_roles.includes('administrator')"
            >
              <i class="text-base far fa-pencil"></i>
            </div>
            <div
              class="flex items-center justify-center w-8 h-8 text-white transition duration-200 bg-gray-500 rounded-full cursor-move handle-row hover:bg-gray-400 active:bg-gray-600"
              v-tooltip="{ content: 'Move row' }"
            >
              <i class="text-base far fa-arrows"></i>
            </div>
            <div
              v-tooltip="{ content: 'Insert row ↓' }"
              @click="insertRow(row.id)"
              class="flex items-center justify-center w-8 h-8 text-white transition duration-200 bg-blue-500 rounded-full cursor-pointer hover:bg-blue-400 active:bg-blue-600"
              v-if="user_roles.includes('administrator')"
            >
              <i class="text-lg fal fa-plus"></i>
            </div>
          </div>
        </div>
      </draggable>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import bus from "../../../../js/pages/bus.js";

export default {
  props: ["isLoaded", "page", "components", "activeRow", "activeCol"],
  components: {
    draggable,
  },
  computed: {},
  data() {
    return {
      user_roles: window.wp_user_roles,
    };
  },
  methods: {
    deselect() {
      bus.$emit("deselect");
    },
    highlightCol(column) {
      bus.$emit("highlightCol", column);
    },
    removeColumn(rowId, columnId) {
      bus.$emit("removeColumn", {
        rowId,
        columnId,
      });
    },
    insertColumn(rowId, columnId) {
      bus.$emit("insertColumn", {
        rowId,
        columnId,
      });
    },
    removeRow(rowId) {
      bus.$emit("removeRow", rowId);
    },
    editRow(rowId) {
      bus.$emit("editRow", rowId);
    },
    insertRow(rowId) {
      bus.$emit("insertRow", rowId);
    },
    insertRowBefore(rowId) {
      bus.$emit("insertRowBefore", rowId);
    },
    dragEnd(row) {
      if (row.columns.length === 0) {
        bus.$emit("removeRow", row.id, { skipConfirm: true, });
      }
    },
    dragRowEnd(args) {
      bus.$emit("orderRow", args);
    },
  },
  mounted() {},
};
</script>
