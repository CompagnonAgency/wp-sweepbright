<template>
  <div>
    <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
      Settings
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
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

    <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
      Component
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
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
      v-if="col.component !== 'block'"
    >
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
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

    <template v-if="col.component !== 'block'">
      <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
        {{ components.find((obj) => obj.id === col.component).name }}
      </div>
      <div
        class="p-5 border-b border-gray-400 last:border-0"
        v-for="(field, index) in components.find(
          (obj) => obj.id === col.component
        ).fields"
        :key="index"
      >
        <template v-if="field.type !== 'locale'">
          <p
            class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
          >
            {{ field.name }}
          </p>

          <template v-if="field.type === 'page_select'">
            <FieldPage
              :activeCol="activeCol"
              :fields="fields"
              :field="field"
              :pages="pages"
            ></FieldPage>
          </template>

          <template v-else-if="field.type === 'page_select_multiple'">
            <FieldPageMultiple
              :activeCol="activeCol"
              :fields="fields"
              :field="field"
              :pages="pages"
            ></FieldPageMultiple>
          </template>

          <template v-else-if="field.type === 'select'">
            <FieldSelect :field="field" :col="col" :lang="lang"></FieldSelect>
          </template>

          <template v-else-if="field.type === 'range'">
            <FieldRange :field="field" :col="col" :lang="lang"></FieldRange>
          </template>

          <template v-else-if="field.type === 'number'">
            <FieldNumber :field="field" :col="col" :lang="lang"></FieldNumber>
          </template>

          <template v-else>
            <FieldText :field="field" :col="col" :lang="lang"></FieldText>
          </template>
        </template>
      </div>
    </template>
  </div>
</template>

<script>
import FieldText from "../Fields/FieldText";
import FieldNumber from "../Fields/FieldNumber";
import FieldRange from "../Fields/FieldRange";
import FieldSelect from "../Fields/FieldSelect";
import FieldPage from "../Fields/FieldPage";
import FieldPageMultiple from "../Fields/FieldPageMultiple";
import bus from "../../../../../js/pages/bus.js";

export default {
  props: [
    "components",
    "activeCol",
    "fields",
    "categories",
    "pages",
    "lang",
    "navigation",
  ],
  components: {
    FieldText,
    FieldNumber,
    FieldRange,
    FieldSelect,
    FieldPage,
    FieldPageMultiple,
  },
  computed: {},
  data() {
    return {
      col: this.activeCol,
    };
  },
  methods: {
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
      return this.components.filter((obj) => {
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
        }
        return condition;
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
  },
  mounted() {
    bus.$on("setCol", (col) => {
      this.col = col;
    });
  },
  beforeDestroy() {
    bus.$off("setCol");
  },
};
</script>
