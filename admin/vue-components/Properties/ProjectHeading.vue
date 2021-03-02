<template>
  <div class="flex items-center">
    <img
      width="100"
      height="100"
      class="mr-8 border-none"
      :src="estate.meta.features.images[0].sizes.thumbnail"
    />
    <div>
      <h1>{{ estate.meta.estate.title[language] }}</h1>
      <p class="mb-3 font-semibold">
        {{ estate.meta.location.street }}
        {{ estate.meta.location.number }},
        {{ estate.meta.location.city }}
      </p>
      <a :href="estate.permalink" class="button action" target="_blank"
        >View property</a
      >
    </div>
  </div>
</template>

<script>
export default {
  props: ["estate", "language"],
  components: {},
  data() {
    return {};
  },
  methods: {},
  mounted() {},
};
</script>

<style>
</style>
