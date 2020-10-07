<template>
  <div>
    <!-- Notification -->
    <div
      class="mx-0 mb-4 notice notice-success is-dismissible"
      v-if="refreshNotification"
    >
      <p>Properties refreshed successfully.</p>
      <button
        type="button"
        class="notice-dismiss"
        @click="refreshNotification = false"
      ></button>
    </div>

    <div
      class="mx-0 mb-4 notice notice-success is-dismissible"
      v-if="cacheNotification"
    >
      <p>Cache has been cleared successfully.</p>
      <button
        type="button"
        class="notice-dismiss"
        @click="cacheNotification = false"
      ></button>
    </div>

    <!-- Statistics -->
    <div class="flex mb-8 space-x-5">
      <div class="flex-1 p-5 bg-white rounded shadow-sm">
        <p
          class="mb-2 text-sm font-medium tracking-wider text-gray-600 uppercase"
        >
          <i class="dashicons dashicons-update"></i> Synchronization
        </p>
        <p class="text-2xl font-light" v-if="!logsLoaded">Loading...</p>
        <p class="text-2xl font-light" v-else-if="logs.SYNCHRONIZATION">
          Up to date
        </p>
        <div
          class="pt-2 text-2xl font-light"
          v-else-if="!logs.SYNCHRONIZATION && logsLoaded && logs.TOTAL > 0"
        >
          <i class="fal fa-sync fa-spin"></i>
          <span class="ml-2">Updating...</span>
        </div>
        <div class="pt-2 text-2xl font-light" v-else>-</div>
      </div>

      <div class="flex-1 p-5 bg-white rounded shadow-sm">
        <p
          class="mb-2 text-sm font-medium tracking-wider text-gray-600 uppercase"
        >
          <i class="dashicons dashicons-building"></i> Total properties
        </p>
        <p class="text-2xl font-light">{{ estatesFiltered.length }}</p>
      </div>

      <div class="flex-1 p-5 bg-white rounded shadow-sm">
        <p
          class="mb-2 text-sm font-medium tracking-wider text-gray-600 uppercase"
        >
          <i class="dashicons dashicons-clock"></i> Last updated
        </p>
        <p class="text-2xl font-light" v-if="logsLoaded && logs.TOTAL > 0">
          {{ logs.LAST_UPDATED | moment("from", "now") }}
        </p>
        <p class="text-2xl font-light" v-else-if="!logsLoaded">Loading...</p>
        <div class="pt-2 text-2xl font-light" v-else>-</div>
      </div>
    </div>

    <!-- Heading -->
    <div class="flex items-center mb-4">
      <p class="mr-3 text-3xl font-light">Properties</p>

      <a
        href="#"
        class="button action"
        @click.prevent="refresh()"
        style="margin-right: 8px"
      >
        <i class="mt-1 dashicons dashicons-update"></i>
        Refresh list
      </a>
      <a href="#" class="button action" @click.prevent="clearCache()">
        <i class="mt-1 dashicons dashicons-trash"></i>
        Clear cache
      </a>
    </div>

    <!-- Overview table -->
    <table class="mb-4 wp-list-table widefat striped table-view-list pages">
      <thead>
        <tr>
          <th scope="col" class="manage-column column-title column-primary">
            Property
          </th>
          <th scope="col" class="manage-column column-title column-primary">
            Project
          </th>
          <th scope="col" class="manage-column column-title column-primary">
            Units
          </th>
          <th scope="col" class="manage-column column-title column-primary">
            Status
          </th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="estate in estates" :key="estate.uid">
          <td class="flex items-center" data-colname="Property">
            <a href="#" class="inline-block">
              <router-link :to="{ name: 'edit', params: { id: estate.id } }">
                <img
                  width="50"
                  height="50"
                  class="border-none"
                  :src="estate.meta.features.images[0].sizes.thumbnail"
                />
              </router-link>
            </a>
            <div class="ml-3">
              <router-link
                :to="{ name: 'edit', params: { id: estate.id } }"
                class="font-semibold text-blue-500"
                >{{ estate.meta.estate.title[language] }}</router-link
              >

              <p>
                {{ estate.meta.location.street }}
                {{ estate.meta.location.number }},
                {{ estate.meta.location.city }}
              </p>
            </div>
          </td>
          <td class="capitalize" data-colname="Status">
            <template v-if="estate.meta.estate.is_project">
              <i class="fal fa-check"></i>
            </template>
            <template v-else> - </template>
          </td>
          <td class="capitalize" data-colname="Status">
            <template v-if="estate.meta.estate.is_project">
              <template v-if="estate.meta.estate.properties">
                {{ estate.meta.estate.properties.length }}
              </template>
              <template v-else> 0 </template>
            </template>
            <template v-else> - </template>
          </td>
          <td class="capitalize" data-colname="Status">
            {{ estate.meta.estate.status }}
          </td>
        </tr>
      </tbody>

      <tfoot>
        <tr>
          <th scope="col" class="manage-column column-title column-primary">
            Property
          </th>
          <th scope="col" class="manage-column column-title column-primary">
            Project
          </th>
          <th scope="col" class="manage-column column-title column-primary">
            Units
          </th>
          <th scope="col" class="manage-column column-title column-primary">
            Status
          </th>
        </tr>
      </tfoot>
    </table>

    <!-- Pagination -->
    <div v-if="estates.length > 0" class="flex justify-end my-5">
      <paginate
        v-model="activePage"
        :page-count="totalPages"
        :click-handler="paginate"
        :prev-link-class="'prev-page button'"
        :next-link-class="'next-page button'"
        :container-class="'flex items-center space-x-3'"
        :page-link-class="'focus:outline-none'"
        :page-class="'inline-block text-gray-700'"
        :active-class="'font-medium bg-gray-300 py-1 px-2 rounded text-white border border-gray-500'"
        :prev-text="'&larr; Back'"
        :next-text="'Next &rarr;'"
        :hide-prev-next="true"
      >
      </paginate>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";
import Paginate from "vuejs-paginate";

export default {
  components: {
    Paginate,
  },
  computed: {
    activePage: {
      get() {
        return this.currentPage + 1;
      },
      set() {},
    },
  },
  data() {
    return {
      refreshNotification: false,
      cacheNotification: false,
      estates: [],
      estatesFiltered: [],
      logs: [],
      logsLoaded: false,
      language: window.sweepbrightLanguage,
      currentPage: 0,
      totalPages: 0,
      postsPerPage: 10,
    };
  },
  methods: {
    generateUUID() {
      let d = new Date().getTime();
      if (
        typeof performance !== "undefined" &&
        typeof performance.now === "function"
      ) {
        d += performance.now();
      }
      return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, (c) => {
        const r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c === "x" ? r : (r & 0x3) | 0x8).toString(16);
      });
    },
    generateUid(estates) {
      estates.forEach((estate) => {
        estate.uid = this.generateUUID();
      });
      return estates;
    },
    filterQuery() {
      this.estates = this.estatesFiltered;
      this.setTotalPages();
    },
    navigate() {
      this.filterQuery();

      const paged = this.estates.slice(
        this.currentPage * this.postsPerPage,
        this.currentPage * this.postsPerPage + this.postsPerPage
      );

      this.estates = this.generateUid(paged);
    },
    setTotalPages() {
      this.totalPages = Math.ceil(this.estates.length / this.postsPerPage);
    },
    paginate(page) {
      this.currentPage = page - 1;
      this.navigate();
      $("html, body").animate({ scrollTop: 0 }, 200);
    },
    getEstates() {
      axios.get("/wp-json/v1/sweepbright/list").then((response) => {
        this.estates = this.generateUid(response.data.data);
        this.estatesFiltered = this.generateUid(response.data.data);
        this.setTotalPages();
        this.navigate();
      });
    },
    getLogs() {
      axios.get("/wp-json/v1/sweepbright/logs").then((response) => {
        this.logs = response.data;
        this.logsLoaded = true;
      });
    },
    clearCache() {
      axios.get("/wp-json/v1/sweepbright/cache").then(() => {
        this.getEstates();
        this.cacheNotification = true;
      });
    },
    refresh() {
      this.refreshNotification = true;
      this.getEstates();
    },
  },
  mounted() {
    this.getEstates();
    this.getLogs();

    this.counterInterval = setInterval(() => {
      this.getLogs();
    }, 5000);
  },
  beforeRouteLeave(to, from, next) {
    clearInterval(this.counterInterval);
    next();
  },
};
</script>

<style>
dd,
li {
  margin: 0;
}
</style>
