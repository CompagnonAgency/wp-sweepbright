<template>
  <div class="wrap" v-if="estate.meta">
    <ProjectHeading :estate="estate" :language="language"></ProjectHeading>

    <!-- Tabs -->
    <Tab :estate="estate">
      <!-- Overview table -->
      <table
        v-if="estatesLoaded && estates.length > 0"
        class="mb-4 wp-list-table widefat striped table-view-list pages"
        data-sweepbright-list
      >
        <thead>
          <tr>
            <th scope="col" class="manage-column column-title column-primary">
              Property
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
                <router-link
                  :to="{ name: 'edit', params: { id: estate.meta.estate.id } }"
                >
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
                  :to="{ name: 'edit', params: { id: estate.meta.estate.id } }"
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
              Status
            </th>
          </tr>
        </tfoot>
      </table>

      <div v-if="estatesLoaded && estates.length === 0">
        This property has no published units.
      </div>

      <!-- Pagination -->
      <div
        v-if="estatesLoaded && estates.length > 0"
        class="flex justify-end my-5"
      >
        <paginate
          v-model="activePage"
          :page-count="totalPages"
          :page-range="5"
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
    </Tab>
  </div>
</template>

<script>
import axios from "axios";
import $ from "jquery";
import Paginate from "vuejs-paginate";
import ProjectHeading from "./ProjectHeading.vue";
import Tab from "./Tab.vue";

export default {
  components: {
    Paginate,
    ProjectHeading,
    Tab,
  },
  computed: {
    activePage: {
      get() {
        return this.currentPage;
      },
      set() {},
    },
  },
  data() {
    return {
      units: [],
      estate: {},
      estates: [],
      estatesLoaded: false,
      totalPages: 0,
      totalPosts: 0,
      currentPage: 1,
      postsPerPage: 10,
      language: window.sweepbrightLanguage,
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
    getEstates(params) {
      axios
        .get(
          `/wp-json/v1/sweepbright/property/${this.estate.id}/units_paged/${this.currentPage}`
        )
        .then((response) => {
          if (response.data && response.data.meta.length > 0) {
            this.estates = this.generateUid(response.data.meta);
          }
          this.totalPages = response.data.totalPages;
          this.totalPosts = response.data.totalPosts;
          this.estatesLoaded = true;
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
      this.currentPage = page;
      let url = window.location.hash;
      if (url.lastIndexOf("p=") > -1) {
        url = window.location.hash.substr(
          0,
          window.location.hash.lastIndexOf("p=") - 1
        );
        console.log(url);
        window.location.hash = `${url}/p=${page}-`;
      }
      window.location.hash = `${url}/p=${page}-`;
      this.getEstates({
        scrollTop: true,
      });
    },
    $sweepBrightInit() {
      if (window.location.hash && window.location.hash.indexOf("=") > -1) {
        const page = window.location.hash.split("=")[1].slice(0, -1);
        this.currentPage = parseInt(page, 10);
      }
      this.getEstates();
    },
    getEstate() {
      axios
        .get(`/wp-json/v1/sweepbright/property/${this.$route.params.id}`)
        .then((response) => {
          const [estate] = response.data;
          this.estate = estate;
          this.$sweepBrightInit();
        });
    },
  },
  mounted() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    this.getEstate();
  },
};
</script>

<style>
dd,
li {
  margin: 0;
}

.bg-wp {
  background-color: #f1f1f1;
}

.bg-wp-dark {
  background-color: #e4e4e4;
}
</style>
