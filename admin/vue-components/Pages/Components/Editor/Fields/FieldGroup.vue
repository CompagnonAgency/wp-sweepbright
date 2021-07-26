<template>
  <div>
    <div class="mb-5 divide-gray-200 divide-y-1">
      <draggable
        v-if="col.data.default[field.id]"
        v-model="col.data.default[field.id]"
        @change="endDrag($event, col.data.default[field.id])"
        :scroll-sensitivity="190"
        :force-fallback="true"
      >
        <div
          class="flex items-center p-3 bg-white cursor-pointer hover:bg-gray-200"
          v-for="group in col.data.default[field.id]"
          :key="group.id"
        >
          <i class="mr-3 text-lg text-gray-500 cursor-move far fa-bars"></i>
          <div class="flex-1">
            <Renderer
              :isGroup="true"
              :components="components"
              :pageSelect="pageSelect"
              :col="fieldCopy(group.fields)"
              :fields="field.subfields"
              :pages="pages"
              :lang="lang"
            >
            </Renderer>
          </div>

          <i
            v-tooltip="{ content: 'Remove group' }"
            @click="deleteGroup(field, group.id)"
            class="ml-3 text-lg text-red-500 far fa-trash"
          ></i>
        </div>
      </draggable>
    </div>
    <button class="w-full btn btn-primary" @click="addGroup(col, field)">
      Add New
    </button>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["pages", "components", "field", "col", "lang", "pageSelect"],
  components: {
    draggable,
  },
  data() {
    return {
      cache: [],
      frame: false,
    };
  },
  methods: {
    fieldCopy(field) {
      // Todo: need to deep clone this or empty first?
      return field;
    },
    inputData() {
      let data = this.col.data.default[this.field.id];
      if (!data) {
        data = this.field.default;
        this.$set(this.col.data.default, this.field.id, data);
      }
      this.cache = JSON.parse(JSON.stringify(data));
      this.$forceUpdate();
    },
    addGroup() {
      // Generate a similar `col` data structure, so this can be passed
      // down to the <Renderer> component.
      let fields = {
        data: {
          default: {},
          nl: {},
          fr: {},
          en: {},
        },
      };

      for (const [key, field] of Object.entries(this.field.subfields)) {
        fields.data.default[field.id] = "";
        fields.data.nl[field.id] = "";
        fields.data.fr[field.id] = "";
        fields.data.en[field.id] = "";
      }

      bus.$emit("addGroup", {
        fields,
        col: this.col,
        field: this.field,
      });
      this.$forceUpdate();
    },
    deleteGroup(field, id) {
      bus.$emit("deleteItem", {
        field,
        id,
        col: this.col,
      });
      this.$forceUpdate();
    },
    endDrag($event, data) {
      bus.$emit("endDrag", {
        $event,
        data,
      });
      this.col.data["default"][this.field.id] = data;
      this.$forceUpdate();
    },
    setDefaults() {
      // This is needed because otherwise it would cache the subfields,
      // and result in duplicate subfield data within in the editor
      this.$set(
        this.col.data.default,
        this.field.id,
        JSON.parse(JSON.stringify(this.cache))
      );
    },
  },
  mounted() {
    this.inputData();
    this.setDefaults();
  },
};
</script>
