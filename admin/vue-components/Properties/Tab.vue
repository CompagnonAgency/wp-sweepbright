<template>
  <div>
    <ul class="flex mt-10 space-x-2" v-if="!estate.meta.estate.project_id">
      <li>
        <router-link
          :to="{ name: 'edit', params: { id: estate.meta.estate.id } }"
          class="inline-block p-2 px-3 -mb-px text-black border-t border-l border-r border-gray-500 bg-wp-dark hover:bg-white"
          exact-active-class="font-semibold bg-wp"
        >
          Property
        </router-link>
      </li>
      <li v-if="estate.meta.estate.is_project">
        <router-link
          :to="{ name: 'units', params: { id: estate.meta.estate.id } }"
          class="inline-block p-2 px-3 -mb-px text-black border-t border-l border-r border-gray-500 bg-wp-dark hover:bg-white"
          active-class="font-semibold bg-wp"
        >
          Units
        </router-link>
      </li>
    </ul>

    <div class="mt-10 mb-10" v-if="estate.meta.estate.project_id">
      <router-link
        :to="{ name: 'units', params: { id: estate.meta.estate.project_id } }"
        class="button action"
      >
        <i class="fal fa-long-arrow-left"></i>
        Back to parent project
      </router-link>
    </div>

    <div class="p-5 mb-10 border border-gray-500 bg-wp">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: ["estate"],
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
