<template>
  <div class="text-sm">
    <div class="px-2 mb-4 bg-white border-b border-gray-400 wp-admin-heading">
      <div class="flex items-center">
        <router-link
          :to="{ name: 'overview' }"
          class="inline-block mr-6 text-base font-semibold"
        >
          <i class="mr-1 text-gray-500 dashicons dashicons-admin-home"></i>
          Properties
        </router-link>
        <ul class="flex space-x-8 text-base">
          <li>
            <router-link
              active-class="border-blue-500"
              :to="{ name: 'overview' }"
              class="inline-block py-4 border-b-2 border-transparent focus:shadow-none"
              >Overview</router-link
            >
          </li>
        </ul>
      </div>
    </div>

    <div class="mr-4">
      <!-- Statistics -->
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {};
  },
  methods: {},
  mounted() {},
};
</script>

<style>
.wp-admin-heading {
  padding-left: 20px;
  margin-left: -20px;
}
</style>
