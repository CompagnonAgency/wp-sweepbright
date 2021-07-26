<template>
  <div>
    <loading
      v-if="data.mode === 'filter' || data.mode === 'favorites'"
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

    <transition name="fade">
      <div
        v-if="modalOpen"
        @click="hideModal"
        class="fixed top-0 left-0 z-50 flex flex-col items-center justify-center w-full h-screen pb-20 overflow-hidden overflow-y-auto bg-black  bg-opacity-70"
      >
        <div
          class="max-w-lg mb-10 overflow-hidden text-center rounded shadow-lg"
          @click.stop="modalOpen = true"
        >
          <div class="p-5 font-semibold text-white bg-primary">
            {{ data.locale[lang].empty.title }}
          </div>
          <div class="p-8 bg-gray-100">
            <div class="mb-5 text-4xl text-gray-300">
              <i class="fal fa-home-lg"></i>
            </div>
            <p>
              {{ data.locale[lang].empty.description }}
            </p>
            <div class="flex justify-center mt-5 space-x-2">
              <button class="btn btn-default" @click.stop="hideModal">
                {{ data.locale[lang].empty.close }}
              </button>
              <a
                :href="data.button_url"
                class="btn btn-primary"
                v-if="data.empty_button === 'true'"
              >
                {{ data.button_label }}
                <i class="ml-2 fal fa-long-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Filter (toggle / clear filters) -->
    <div
      class="relative z-10 flex flex-col mb-10 text-base  lg:items-center lg:justify-between text-dark lg:flex-row"
      v-if="data.mode === 'filter' || data.mode === 'favorites'"
    >
      <div class="mb-5 text-2xl font-semibold lowercase lg:w-1/2 lg:mb-0">
        <template v-if="estates.length > 0 && totalPages > 0">
          {{ totalPosts }} {{ data.locale[lang].properties }}
        </template>
      </div>
      <div class="flex lg:justify-end lg:w-1/2">
        <button
          class="hidden mr-5 btn btn-primary lg:inline-block"
          @click="$clearFilters"
          v-if="filtered"
        >
          <i class="mr-1 fal fa-filter"></i>
          {{ data.locale[lang].clear_filters }}
        </button>
        <div class="flex-1 inline-block mr-5 lg:flex-initial">
          <Dropdown id="sort" :dropdown="config.dropdowns.sort"></Dropdown>
        </div>
        <ToggleMode :view="view" v-if="data.mode === 'filter'"></ToggleMode>
      </div>
    </div>

    <!-- List -->
    <template v-if="view === 'list'">
      <div
        class="relative z-0 flex flex-col flex-wrap space-y-5  lg:-m-5 lg:flex-row lg:space-y-0"
        v-if="estates.length > 0 && totalPages > 0"
      >
        <div
          class="w-full min-w-0 lg:p-5"
          :class="`lg:w-1/${data.results_per_row}`"
          v-for="estate in estates"
          :key="estate.id"
        >
          <Card
            :estate="estate"
            :component="component"
            :favorites="favorites"
          ></Card>
        </div>
      </div>

      <!-- Empty: filter -->
      <div
        v-if="
          estates.length === 0 && totalPages === 0 && data.mode === 'filter'
        "
        class="text-center"
      >
        {{ data.locale[lang].no_results }}
      </div>

      <!-- Empty: favorites -->
      <div
        v-if="
          estates.length === 0 && totalPages === 0 && data.mode === 'favorites'
        "
        class="text-center"
      >
        {{ data.locale[lang].no_favorites }}
      </div>

      <!-- Empty: recent -->
      <div
        class="text-center"
        v-if="
          estates.length === 0 && totalPages !== -1 && data.mode === 'recent'
        "
      >
        {{ data.locale[lang].no_results }}
      </div>

      <!-- Pagination -->
      <div
        v-if="
          estates.length > 0 &&
          (data.mode === 'filter' || data.mode === 'favorites')
        "
        class="mt-12"
      >
        <paginate
          v-model="activePage"
          :page-count="totalPages"
          :click-handler="$sweepBrightPaginate"
          :disabled-class="'hidden'"
          :prev-link-class="'font-medium tracking-widest uppercase text-sm transition duration-200 hover:text-black focus:outline-none mr-5'"
          :next-link-class="'font-medium tracking-widest uppercase text-sm transition duration-200 hover:text-black focus:outline-none ml-5'"
          :container-class="'flex items-center space-x-3 justify-center'"
          :page-link-class="'focus:outline-none'"
          :page-class="`inline-block text-lg w-10 h-10 ${theme.rounded_lg} flex items-center justify-center font-medium`"
          :active-class="'btn btn-primary px-0 text-white'"
          :prev-text="`&larr; ${data.locale[lang].back}`"
          :next-text="`${data.locale[lang].next} &rarr;`"
          :hide-prev-next="false"
        />
      </div>
    </template>

    <!-- Map -->
    <template v-if="view === 'map' && data.mode === 'filter'">
      <Map
        :component="component"
        :markerData="markers"
        v-if="markersLoaded"
      ></Map>
    </template>
  </div>
</template>

<script>
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import Dropdown from "./Dropdown";
import Card from "./Card";
import Map from "./Map";
import ToggleMode from "./ToggleMode";

export default {
  props: ["component"],
  components: {
    Loading,
    ToggleMode,
    Dropdown,
    Card,
    Map,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      favorites: [],
      view: "list",
      modalOpen: false,
      markersLoaded: false,
      filtered: false,
      configCache: {},
      config: {
        dropdowns: {
          sort: {
            open: false,
            selected: false,
            placeholder: window[this.component].locale[window.lang].sort.label,
            dropdown: [
              {
                value: "relevance",
                label:
                  window[this.component].locale[window.lang].sort.relevance,
              },
              {
                value: "price_asc",
                label:
                  window[this.component].locale[window.lang].sort.price_asc,
              },
              {
                value: "price_desc",
                label:
                  window[this.component].locale[window.lang].sort.price_desc,
              },
              {
                value: "date_asc",
                label: window[this.component].locale[window.lang].sort.date_asc,
              },
              {
                value: "date_desc",
                label:
                  window[this.component].locale[window.lang].sort.date_desc,
              },
            ],
          },
        },
      },
    };
  },
  methods: {
    openModal() {
      this.modalOpen = true;
    },
    hideModal() {
      this.modalOpen = false;
    },
    defaultFilters() {
      this.request.maxPerPage = this.data.max_results_per_page;
      this.request.geoDistance = this.data.geo_distance;

      if (this.$urlParam().negotiation || this.$urlParam().region) {
        this.filtered = true;
      }
    },
    negotiationFilter() {
      switch (this.data.filter) {
        case "sale":
          this.request.filters.negotiation = "sale";
          break;
        case "let":
          this.request.filters.negotiation = "let";
          break;
        case "projects":
          this.request.filters.negotiation = "projects";
          break;
        case "sale_non_projects":
          this.request.filters.negotiation = "sale_non_projects";
          break;
        default:
          this.request.filters.negotiation = false;
          break;
      }
    },
    loadMap() {
      window.addEventListener("loadedMap", () => {
        this.markersLoaded = true;
      });
      this.$search({
        mapMode: true,
      });
    },
    loadFavorites() {
      axios.get("/wp-json/v1/sweepbright/favorites").then((response) => {
        this.favorites = response.data.DATA;
      });
    },
    listeners() {
      this.$bus.$on("favorite", (action, id) => {
        this.isLoading = true;
        axios
          .post("/wp-json/v1/sweepbright/favorites/update", {
            action,
            id,
          })
          .then(() => {
            if (!action) {
              this.favorites = this.favorites.filter((key) => key !== id);
            } else {
              this.favorites.push(id);
            }

            const event = new CustomEvent("favorite", {
              bubbles: true,
              cancelable: true,
              composed: false,
              detail: {
                favorited: action,
              },
            });
            window.dispatchEvent(event);

            this.isLoading = false;
          });
      });

      this.$bus.$on("openModal", (data) => {
        this.openModal(data);
      });

      this.$bus.$on("setView", (view) => {
        this.view = view;
      });

      window.addEventListener("filterChange", (args) => {
        this.request = args.detail.request;
        this.filtered = true;

        this.$search();
        this.$search({
          mapMode: true,
        });
      });
    },
    init() {
      this.configCache = JSON.parse(JSON.stringify(this.config));
      this.loadFavorites();
      this.listeners();
      this.defaultFilters();
      this.negotiationFilter();
      this.$events();
      this.$cloneFilters();

      if (this.data.mode === "filter") {
        this.$list();
        this.loadMap();
      } else if (this.data.mode === "recent") {
        this.$sweepBrightInit({
          recent: this.data.results_per_row,
        });
      } else if (this.data.mode === "favorites") {
        this.request.favorites = true;
        this.$list();
      }
    },
  },
  mounted() {
    this.init();
  },
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  @apply transition-all duration-200;
}
.fade-enter,
.fade-leave-to {
  @apply opacity-0 transform translate-y-1;
}
</style>
