<template>
  <div class="flex text-lg" :class="`${theme.rounded_lg}`">
    <div
      class="flex items-center justify-center w-12 px-0 transition duration-200 border-r-0 rounded-r-none cursor-pointer btn form-input"
      :class="`${theme.rounded_lg_l} ${
        view === 'list'
          ? 'text-white btn-primary border-primary'
          : 'text-primary'
      }`"
      @click="setView('list')"
    >
      <i class="-mr-1 fas fa-th"></i>
    </div>
    <div
      class="flex items-center justify-center w-12 px-0 transition duration-200 border-l-0 rounded-l-none cursor-pointer btn form-input"
      :class="`${theme.rounded_lg_r} ${
        view === 'map'
          ? 'text-white btn-primary border-primary'
          : 'text-primary'
      }`"
      @click="setView('map')"
    >
      <i class="-ml-1 fas fa-map-marker-alt"></i>
    </div>
  </div>
</template>

<script>
export default {
  props: ["view"],
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      store: {},
    };
  },
  methods: {
    setView(view) {
      this.$bus.$emit("setView", view);
    },
  },
  mounted() {},
};
</script>
