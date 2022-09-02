<template>
  <div>
    <!-- Notification -->
    <div
      class="mx-0 mb-4 notice notice-success is-dismissible"
      v-if="refreshNotification"
    >
      <p>Properties refreshed successfully.</p>
    </div>

    <!-- Statistics -->
    <div class="flex mb-8 space-x-5">
      <div class="flex-1 p-5 bg-white rounded shadow-sm">
        <p
          class="mb-2 text-sm font-medium tracking-wider text-gray-600 uppercase "
        >
          <i class="dashicons dashicons-update"></i> Synchronization
        </p>
        <p class="pt-2 text-2xl font-light" v-if="!logsLoaded">
          Loading status...
        </p>
        <p
          class="pt-2 text-2xl font-light"
          v-else-if="logs.STATUS.status === 'running'"
        >
          <i class="fal fa-sync fa-spin"></i>
          <span class="ml-2">{{ logs.STATUS.message }}</span>
        </p>
        <p
          class="pt-2 text-2xl font-light"
          v-else-if="logs.STATUS.status === 'idle'"
        >
          <span class="ml-2">{{ logs.STATUS.message }}</span>
        </p>
        <p
          class="pt-2 text-2xl font-light"
          v-else-if="logs.STATUS.status === 'completed'"
        >
          <i class="fal fa-check"></i>
          <span class="ml-2">{{ logs.STATUS.message }}</span>
        </p>
        <p class="pt-2 text-2xl font-light" v-else>
          {{ logs.STATUS.message }}
        </p>
      </div>

      <div class="flex-1 p-5 bg-white rounded shadow-sm">
        <p
          class="mb-2 text-sm font-medium tracking-wider text-gray-600 uppercase "
        >
          <i class="dashicons dashicons-building"></i> Total properties
        </p>
        <p class="text-2xl font-light">{{ totalPosts }}</p>
      </div>

      <div class="flex-1 p-5 bg-white rounded shadow-sm">
        <p
          class="mb-2 text-sm font-medium tracking-wider text-gray-600 uppercase "
        >
          <i class="dashicons dashicons-clock"></i> Last updated
        </p>
        <p
          class="text-2xl font-light"
          v-if="logsLoaded && logs.STATUS.status !== 'idle'"
        >
          {{ logs.STATUS.date | moment("from", "now") }}
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
    </div>

    <!-- Overview table -->
    <table
      class="mb-4 wp-list-table widefat striped table-view-list pages"
      data-sweepbright-list
    >
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
          <td class="flex items-center" data-colname="Property" v-if="estate">
            <a href="#" class="inline-block">
              <router-link
                :to="{ name: 'edit', params: { id: estate.meta.estate.id } }"
              >
                <img
                  v-if="estate.meta.features.images[0]"
                  width="50"
                  height="50"
                  class="border-none"
                  :src="estate.meta.features.images[0].sizes.thumbnail"
                />
              </router-link>
            </a>
            <div class="ml-3">
              <router-link
                :to="{ name: 'edit', params: { id: estate.meta.estate.id } }"
                class="font-semibold text-blue-500"
                >{{ estate.meta.estate.title[language] }}</router-link
              >

              <p v-if="estate.meta.location.street">
                {{ estate.meta.location.street }}
                {{ estate.meta.location.number }},
                {{ estate.meta.location.city }}
              </p>
            </div>
          </td>
          <td class="capitalize" data-colname="Status" v-if="estate">
            <template v-if="estate.meta.estate.is_project">
              <i class="fal fa-check"></i>
            </template>
            <template v-else> - </template>
          </td>
          <td class="capitalize" data-colname="Status" v-if="estate">
            <template v-if="estate.meta.estate.is_project">
              <template v-if="estate.meta.estate.properties">
                {{ estate.meta.estate.properties.length }}
              </template>
              <template v-else> 0 </template>
            </template>
            <template v-else> - </template>
          </td>
          <td class="capitalize" data-colname="Status" v-if="estate">
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
        :page-range="5"
        :page-count="totalPages"
        :click-handler="$sweepBrightPaginate"
        :prev-link-class="'prev-page button'"
        :next-link-class="'next-page button'"
        :container-class="'flex items-center space-x-3'"
        :page-link-class="'focus:outline-none focus:shadow-none'"
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
        return this.request.page;
      },
      set() {},
    },
  },
  data() {
    return {
      refreshNotification: false,
      estates: [],
      totalPages: 0,
      totalPosts: 0,
      request: {
        page: 1,
        sort: {
          order: "desc", // desc, asc
          orderBy: "date", // relevance, price, date
        },
        recent: false,
        filters: {
          status: false, // available, sold, option, under_contract, rented
          negotiation: false, // sale, let, projects, sale_non_projects
          category: [], // see API docs `type`
          facilities: {
            bedrooms: {
              min: false,
              max: false,
            },
          },
          price: {
            min: false,
            max: false,
          },
          plot_area: {
            min: false,
            max: false,
          },
          liveable_area: {
            min: false,
            max: false,
          },
          location: {
            region: "",
            lat: false,
            lng: false,
          },
        },
      },
      logs: {},
      logsLoaded: false,
      language: window.sweepbrightLanguage,
      currentPage: 0,
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
    getLogs() {
      axios.get("/wp-json/v1/sweepbright/logs").then((response) => {
        this.logs = response.data;
        this.logsLoaded = true;
      });
    },
    refresh() {
      this.refreshNotification = true;
      this.getEstates();

      setTimeout(() => {
        this.refreshNotification = false;
      }, 3000);
    },
    getEstates(params) {
      axios
        .post("/wp-json/v1/sweepbright/list", this.request)
        .then((response) => {
          console.log(response.data);

          if (response.data.estates && response.data.estates.length > 0) {
            this.estates = this.generateUid(response.data.estates);
          }
          this.totalPages = response.data.totalPages;
          this.totalPosts = response.data.totalPosts;
          if (params && params.scrollTop) {
            $("html, body").animate(
              {
                scrollTop: $("[data-sweepbright-list]").offset().top,
              },
              200
            );
          }
        });
    },
    $sweepBrightPaginate(page) {
      this.request.page = page;
      window.location.hash = page;
      this.getEstates({
        scrollTop: true,
      });
    },
    $sweepBrightInit() {
      if (window.location.hash) {
        this.request.page = parseInt(window.location.hash.substring(2), 10);
      }
      this.getEstates();
    },
  },
  mounted() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    this.getLogs();
    this.$sweepBrightInit();

    this.counterInterval = setInterval(() => {
      this.getLogs();
    }, 3000);
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
