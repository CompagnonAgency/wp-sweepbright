<template>
  <div>
    <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
      Settings
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Container
      </p>
      <select v-model="row.style.container" @change="setStyle">
        <option value="contained">Contained</option>
        <option value="full-width">Full width</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Horizontal alignment
      </p>
      <select v-model="row.style.justify_align" @change="setStyle">
        <option value="">Auto</option>
        <option value="justify-start">Start</option>
        <option value="justify-center">Centered</option>
        <option value="justify-between">Between</option>
        <option value="justify-end">End</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Vertical alignment
      </p>
      <select v-model="row.style.items_align" @change="setStyle">
        <option value="items-start">Start</option>
        <option value="items-center">Centered</option>
        <option value="items-end">End</option>
        <option value="items-stretch">Stretch</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Mobile direction
      </p>
      <select v-model="row.style.mobile_direction" @change="setStyle">
        <option value="flex-col">Normal</option>
        <option value="flex-col-reverse">Reverse</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Margin bottom
      </p>
      <select v-model="row.style.margin_bottom" @change="setStyle">
        <option value="mb-32">Large</option>
        <option value="mb-20">Medium</option>
        <option value="mb-10">Normal</option>
        <option value="mb-0">None</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Padding
      </p>
      <select v-model="row.style.padding" @change="setStyle">
        <option value="p-0">None</option>
        <option value="p-5">Small</option>
        <option value="p-10">Medium</option>
        <option value="p-20">Large</option>
      </select>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Background color
      </p>
      <input
        v-model="row.style.background"
        type="checkbox"
        @change="setStyle"
      />
      <div class="mt-2">
        <input
          v-model="row.style.background_color"
          type="color"
          v-if="row.style.background"
          @change="setStyle"
        />
      </div>
    </div>
  </div>
</template>

<script>
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["activeRow"],
  components: {},
  computed: {},
  data() {
    return {
      row: this.activeRow,
    };
  },
  methods: {
    setStyle() {
      bus.$emit("setStyle", this.row.style);
    },
  },
  mounted() {
    bus.$on("setRow", (row) => {
      this.row = row;
    });
  },
  beforeDestroy() {
    bus.$off("setRow");
  },
};
</script>
