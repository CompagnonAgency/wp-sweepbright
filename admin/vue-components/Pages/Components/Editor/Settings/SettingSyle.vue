<template>
  <div v-if="row.style">
    <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
      Style
    </div>

    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-fill-drip"></i>
        Background color
      </p>
      <select v-model="row.style.background" @change="setStyle">
        <option value="">None (default)</option>
        <option value="bg-white">White</option>
        <option value="bg-light">Light color</option>
        <option value="bg-dark">Dark color</option>
        <option value="bg-secondary">Secondary color</option>
        <option value="bg-primary">Primary color</option>
      </select>
    </div>

    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-expand-wide"></i>
        Background style
      </p>
      <select v-model="row.style.background_style" @change="setStyle">
        <option value="">Fill (default)</option>
        <option value="top-half">Top half</option>
        <option value="bottom-half">Bottom half</option>
      </select>
    </div>

    <div
      class="p-5 border-b border-gray-400 last:border-0"
      v-if="apply !== 'row'"
    >
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-align-left"></i>
        Text align
      </p>
      <select v-model="row.style.text_align" @change="setStyle">
        <option value="">Left (default)</option>
        <option value="text-center">Center</option>
        <option value="text-right">Right</option>
      </select>
    </div>

    <div
      class="p-5 border-b border-gray-400 last:border-0"
      v-if="apply !== 'row'"
    >
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fas fa-text"></i>
        Text color
      </p>
      <select v-model="row.style.text_color" @change="setStyle">
        <option value="text-dark">Dark (default)</option>
        <option value="text-light">Light</option>
      </select>
    </div>

    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-border-outer"></i>
        Border
      </p>
      <select v-model="row.style.border" @change="setStyle">
        <option value="">None (default)</option>
        <option value="border-t border-b">Top & bottom</option>
        <option value="border-t">Top</option>
        <option value="border-b">Bottom</option>
      </select>
    </div>

    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-circle-notch"></i>
        Border radius
      </p>
      <select v-model="row.style.border_radius" @change="setStyle">
        <option value="">None (default)</option>
        <option value="rounded">Small</option>
        <option value="rounded-lg">Large</option>
        <option value="rounded-xl">XL</option>
      </select>
    </div>

    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-arrow-to-top"></i>
        Top offset
      </p>
      <select v-model="row.style.top_offset" @change="setStyle">
        <option value="-mt-56">(-) large</option>
        <option value="-mt-40">(-) medium</option>
        <option value="-mt-32">(-) small</option>
        <option value="">None (default)</option>
        <option value="mt-32">(+) small</option>
        <option value="mt-40">(+) medium</option>
        <option value="mt-56">(+) large</option>
      </select>
    </div>

    <div
      class="p-5 border-b border-gray-400 last:border-0"
      v-if="row.style.padding_y === ''"
    >
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-expand-arrows"></i>
        Padding - around
      </p>
      <select v-model="row.style.padding" @change="setStyle">
        <option value="">None (default)</option>
        <option value="p-5">Small</option>
        <option value="p-10">Medium</option>
        <option value="p-20">Normal</option>
        <option value="p-32">XL</option>
      </select>
    </div>

    <div
      class="p-5 border-b border-gray-400 last:border-0"
      v-if="row.style.padding === ''"
    >
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-expand-alt"></i>
        Padding - top &amp; bottom
      </p>
      <select v-model="row.style.padding_y" @change="setStyle">
        <option value="">None (default)</option>
        <option value="py-5">Small</option>
        <option value="py-10">Medium</option>
        <option value="py-20">Normal</option>
        <option value="py-32">XL</option>
      </select>
    </div>

    <div
      class="p-5 border-b border-gray-400 last:border-0"
      v-if="row.style.padding === '' && row.style.padding_y !== ''"
    >
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-expand-alt"></i>
        Padding - top
      </p>
      <select v-model="row.style.padding_t" @change="setStyle">
        <option value="pt-0">None</option>
        <option value="">Auto (default)</option>
      </select>
    </div>

    <div
      class="p-5 border-b border-gray-400 last:border-0"
      v-if="row.style.padding === '' && row.style.padding_y !== ''"
    >
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        <i class="w-3 fad fa-expand-alt"></i>
        Padding - bottom
      </p>
      <select v-model="row.style.padding_b" @change="setStyle">
        <option value="pb-0">None</option>
        <option value="">Auto (default)</option>
      </select>
    </div>
  </div>
</template>

<script>
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["active", "apply"],
  components: {},
  computed: {},
  data() {
    return {
      row: this.active,
    };
  },
  methods: {
    setDefaults() {
      if (!this.row.style) {
        this.$set(this.row, "style", {});
      }
      if (!this.row.style.container) {
        this.$set(this.row.style, "container", "");
      }
      if (!this.row.style.top_offset) {
        this.$set(this.row.style, "top_offset", "");
      }
      if (!this.row.style.padding) {
        this.$set(this.row.style, "padding", "");
      }
      if (!this.row.style.text_align) {
        this.$set(this.row.style, "text_align", "");
      }
      if (!this.row.style.padding_y) {
        this.$set(this.row.style, "padding_y", "");
      }
      if (!this.row.style.padding_t) {
        this.$set(this.row.style, "padding_t", "");
      }
      if (!this.row.style.padding_b) {
        this.$set(this.row.style, "padding_b", "");
      }
      if (!this.row.style.text_color) {
        this.$set(this.row.style, "text_color", "text-dark");
      }
      if (!this.row.style.background) {
        this.$set(this.row.style, "background", "");
      }
      if (!this.row.style.background_style) {
        this.$set(this.row.style, "background_style", "");
      }
      if (!this.row.style.border_radius) {
        this.$set(this.row.style, "border_radius", "");
      }
      if (!this.row.style.border) {
        this.$set(this.row.style, "border", "");
      }
    },
    setStyle() {
      if (this.row.style.padding !== "") {
        this.$set(this.row.style, "padding_y", "");
        this.$set(this.row.style, "padding_t", "");
        this.$set(this.row.style, "padding_b", "");
      }
      if (this.row.style.padding_y !== "") {
        this.$set(this.row.style, "padding", "");
      }
      if (this.row.style.padding_y === "") {
        this.$set(this.row.style, "padding_t", "");
        this.$set(this.row.style, "padding_b", "");
      }
      bus.$emit("setStyle", this.row.style, this.apply);
    },
  },
  beforeDestroy() {
    bus.$off("setRow");
  },
  mounted() {
    this.setDefaults();

    bus.$on("setCol", (col) => {
      if (col) {
        this.row = col;
        this.setDefaults();
      }
    });

    bus.$on("setRow", (row) => {
      this.row = row;
      this.setDefaults();
    });
  },
};
</script>
