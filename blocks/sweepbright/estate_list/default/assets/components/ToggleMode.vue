<template>
  <div class="flex text-lg" :class="`${theme.rounded_lg}`">
    <div
      class="flex items-center justify-center w-12 transition duration-200 border-t-2 border-b-2 border-l-2 cursor-pointer"
      :class="`${theme.rounded_lg_l} ${
        mode === 'list'
          ? 'text-white bg-primary border-primary'
          : 'text-primary border-gray-200'
      }`"
      @click="setMode('list')"
    >
      <i class="-mr-1 fas fa-th-large"></i>
    </div>
    <div
      class="flex items-center justify-center w-12 transition duration-200 border-t-2 border-b-2 border-r-2 cursor-pointer"
      :class="`${theme.rounded_lg_r} ${
        mode === 'map'
          ? 'text-white bg-primary border-primary'
          : 'text-primary border-gray-200'
      }`"
      @click="setMode('map')"
    >
      <i class="-ml-1 fas fa-map"></i>
    </div>
  </div>
</template>

<script>
export default {
  props: ["mode"],
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      store: {},
    };
  },
  methods: {
    setMode(mode) {
      this.$bus.$emit("setMode", mode);
    },
  },
  mounted() {},
};
</script>
