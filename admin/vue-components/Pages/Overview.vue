<template>
  <div class="mb-5">
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

    <div class="mb-5 text-right" v-if="user_roles.includes('administrator')">
      <button
        class="btn btn-primary"
        @click.prevent="
          $router.push({ name: 'editor', params: { id: 'create' } })
        "
      >
        <i class="mr-1 fal fa-plus"></i>
        Create Page
      </button>
    </div>

    <div
      class="overflow-hidden border border-gray-400 rounded-md shadow-sm"
      v-if="pages.length > 0"
    >
      <div
        class="px-4 py-2 font-medium text-gray-600 bg-gray-200 border-b border-gray-400 "
      >
        <ul class="flex">
          <li class="w-10/12">Title</li>
          <li class="w-2/12 text-right">Actions</li>
        </ul>
      </div>
      <div class="bg-white">
        <ul
          class="flex items-center px-4 py-5 transition duration-200 border-b  last:border-0 hover:bg-gray-100"
          v-for="(page, index) in pages"
          :key="index"
        >
          <li class="w-10/12">
            <router-link
              :to="{ name: 'editor', params: { id: page.id } }"
              class="inline-flex items-center text-base font-semibold transition duration-200  focus:shadow-none hover:text-blue-500 group"
            >
              <i
                class="w-6 text-lg text-gray-500 fad fa-file-alt"
                v-if="!page.locked"
              ></i>
              <i
                class="w-6 text-lg text-gray-500 fad fa-lock-alt"
                v-if="page.locked"
              ></i>
              <span>{{ page.title }}</span>
            </router-link>

            <p
              class="ml-6 text-sm text-gray-500"
              v-if="page.template === 'default'"
            >
              Default
            </p>
            <p
              class="ml-6 text-sm text-gray-500"
              v-if="page.template === 'estate'"
            >
              Estate
            </p>
            <p
              class="ml-6 text-sm text-gray-500"
              v-if="page.template === 'base'"
            >
              Base
            </p>
            <p
              class="ml-6 text-sm text-gray-500"
              v-if="page.template === '404'"
            >
              404
            </p>
          </li>
          <li class="w-2/12 text-right">
            <ul class="flex items-center justify-end space-x-5 text-xl">
              <li>
                <router-link
                  :to="{ name: 'editor', params: { id: page.id } }"
                  v-tooltip="{ content: 'Edit' }"
                  class="text-gray-500 transition duration-200  hover:text-green-500 focus:shadow-none focus:text-gray-500"
                >
                  <i class="fad fa-edit"></i>
                </router-link>
              </li>
              <li v-if="!page.locked && user_roles.includes('administrator')">
                <a
                  href="#"
                  @click="deletePage(page.id)"
                  v-tooltip="{ content: 'Delete' }"
                  class="text-gray-500 transition duration-200  hover:text-red-500 focus:shadow-none focus:text-gray-500"
                >
                  <i class="fad fa-trash"></i>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  components: {
    Loading,
  },
  computed: {},
  data() {
    return {
      user_roles: window.wp_user_roles,
      isLoading: false,
      defaultPages: [
        {
          id: "estate-default",
          title: "Estate - default",
          locked: true,
          template: "estate",
        },
        {
          id: "estate-project",
          title: "Estate - project",
          locked: true,
          template: "estate",
        },
        {
          id: "estate-unit",
          title: "Estate - unit",
          locked: true,
          template: "estate",
        },
        {
          id: "404",
          title: "404 - Page not found",
          locked: true,
          template: "404",
        },
      ],
      pages: [],
      settings: false,
    };
  },
  methods: {
    getSettings() {
      this.isLoading = true;
      axios({
        method: "get",
        url: "/wp-json/v1/sweepbright/pages/settings",
      }).then((response) => {
        this.settings = response.data.DATA;
        this.isLoading = false;
        this.getPages();
      });
    },
    getPages() {
      this.isLoading = true;
      axios({
        method: "get",
        url: "/wp-json/v1/sweepbright/pages/list/",
      }).then((response) => {
        this.pages = this.defaultPages;

        if (this.user_roles.includes("administrator")) {
          this.pages.push({
            id: "base",
            title: "Base layout",
            locked: true,
            template: "base",
          });
        }

        response.data.PAGES.forEach((page) => {
          let locked = false;

          if (page.post_name === "home") {
            locked = true;
          }

          if (page.post_name === "favorites") {
            locked = true;
          }

          let data = {
            id: page.ID,
            title: page.post_title,
            template: "page",
            locked: locked,
          };

          if (page.post_name === "favorites") {
            if (this.settings.favorites) {
              this.pages.push(data);
            }
          } else {
            this.pages.push(data);
          }
        });

        this.sortPages();
        this.isLoading = false;
      });
    },
    sortPages() {
      this.pages.sort((a, b) =>
        a.title < b.title ? -1 : a.title > b.title ? 1 : 0
      );

      this.pages = this.pages.sort((a, b) => b.locked - a.locked);
    },
    deletePage(id) {
      this.$dialog.confirm("Please confirm to delete page").then((dialog) => {
        axios({
          method: "post",
          url: "/wp-json/v1/sweepbright/pages/delete",
          data: {
            id,
          },
        }).then((response) => {
          this.pages = this.pages.filter((obj) => {
            return obj.id !== id;
          });
          this.sortPages();
        });
      });
    },
  },
  mounted() {
    this.getSettings();
  },
};
</script>
