<template>
  <div class="flex flex-col space-y-10 lg:flex-row lg:space-x-10 lg:space-y-0">
    <div class="w-full min-w-0" v-for="(estate, index) in estates" :key="index">
      <Card :estate="estate" :component="component" variant="default"></Card>
    </div>
  </div>
</template>

<script>
import Card from "../../../../_shared/Card/Default";

export default {
  props: ["component"],
  components: {
    Card,
  },
  data() {
    return {
      theme: window.theme,
      data: window[this.component],
    };
  },
  methods: {},
  mounted() {
    this.$sweepBrightInit({
      recent: this.data.total_estates,
    });
  },
};
</script>
