<template>
  <div>
    <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
      Row settings
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
import SettingSyle from "../Settings/SettingSyle";
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["activeRow"],
  components: {
    SettingSyle,
  },
  computed: {},
  data() {
    return {
      row: this.activeRow,
    };
  },
  methods: {
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
    this.setDefaults();

    bus.$on("setRow", (row) => {
      this.row = row;
      this.setDefaults();
    });
  },
  beforeDestroy() {
    bus.$off("setRow");
  },
};
</script>
