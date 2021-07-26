<template>
  <div class="text-sm">
    <loading
      :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="true"
      :opacity="0.4"
      blur="0"
      background-color="#1a202c"
      :height="50"
      :width="50"
      color="#fff"
    ></loading>

    <div
      class="px-2 mb-4 bg-white border-b border-gray-400 wp-admin-heading"
      v-if="onboardingComplete && $route.name !== 'editor' && settingsLoaded"
    >
      <div class="flex items-center">
        <router-link
          :to="{ name: 'home' }"
          class="inline-block mr-6 text-base font-semibold"
        >
          <i class="mr-1 text-gray-500 dashicons dashicons-admin-page"></i>
          Pages
        </router-link>
        <ul class="flex space-x-8 text-base">
          <li>
            <router-link
              active-class="border-blue-500"
              :to="{ name: 'home' }"
              exact
              class="inline-block py-4 border-b-2 border-transparent  focus:shadow-none"
              >Overview</router-link
            >
          </li>
          <li v-if="user_roles.includes('administrator')">
            <router-link
              active-class="border-blue-500"
              :to="{ name: 'theme' }"
              class="inline-block py-4 border-b-2 border-transparent  focus:shadow-none"
              >Theme</router-link
            >
          </li>
          <li v-if="user_roles.includes('administrator')">
            <router-link
              active-class="border-blue-500"
              :to="{ name: 'settings' }"
              class="inline-block py-4 border-b-2 border-transparent  focus:shadow-none"
              >Settings</router-link
            >
          </li>
        </ul>
      </div>
    </div>

    <div class="mr-4" v-if="onboardingComplete && settingsLoaded">
      <router-view></router-view>
    </div>

    <Onboarding v-if="!onboardingComplete" v-show="settingsLoaded" />
  </div>
</template>

<script>
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import bus from "../../js/pages/bus.js";
import Onboarding from "./Components/Onboarding";

export default {
  components: {
    Loading,
    Onboarding,
  },
  computed: {},
  data() {
    return {
      user_roles: window.wp_user_roles,
      isLoading: false,
      settingsLoaded: false,
      onboardingComplete: false,
      onboarding: false,
    };
  },
  methods: {
    getSettings() {
      this.isLoading = true;
      axios({
        method: "get",
        url: "/wp-json/v1/sweepbright/pages/settings",
      }).then((response) => {
        this.onboardingComplete = response.data.DATA.onboarded;
        this.settingsLoaded = true;
        bus.$emit("settingsLoaded", response.data.DATA);
        this.isLoading = false;
      });
    },
    setup() {
      this.isLoading = true;
      axios({
        method: "post",
        url: "/wp-json/v1/sweepbright/pages/setup",
        data: {
          onboarding: this.onboarding,
        },
      }).then((response) => {
        this.onboardingComplete = true;
        this.isLoading = false;
      });
    },
    listeners() {
      bus.$once("onboardingComplete", (onboarding) => {
        this.onboarding = onboarding;
        this.setup();
      });
    },
  },
  mounted() {
    this.listeners();
    this.getSettings();
  },
};
</script>

<style>
.wp-admin-heading {
  padding-left: 20px;
  margin-left: -20px;
}

.fade-enter-active {
  transition: all 150ms cubic-bezier(1, 0.5, 0.8, 1);
}
.fade-leave-active {
  transition: all 150ms cubic-bezier(1, 0.5, 0.8, 1);
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
