<template>
  <div data-sweepbright-list>
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

    <div class="relative z-10 w-11/12 mx-auto lg:w-10/12">
      <p class="mb-10 text-4xl tracking-wider uppercase">Bekijk ons aanbod</p>

      <div class="flex mb-16 space-x-5 text-base">
        <Search
          :location="config.location"
          :component="component"
          :search="config.location.search"
        ></Search>

        <Dropdown
          id="negotiation"
          :dropdown="config.dropdowns.negotiation"
        ></Dropdown>

        <Dropdown
          id="category"
          :dropdown="config.dropdowns.category"
        ></Dropdown>

        <Range id="price" :range="config.dropdowns.price"></Range>

        <Range id="sizes" :range="config.dropdowns.sizes"></Range>

        <ToggleMode :mode="config.mode"></ToggleMode>
      </div>
    </div>

    <div class="py-10 bg-gray-100 lg:py-20">
      <div class="w-11/12 mx-auto lg:w-10/12">
        <template v-if="config.mode === 'list'">
          <div class="flex flex-col flex-wrap -m-5 lg:flex-row">
            <div
              class="w-full min-w-0 p-5 lg:w-1/3"
              v-for="(estate, index) in estates"
              :key="index"
            >
              <Card
                :estate="estate"
                :component="component"
                variant="light"
              ></Card>
            </div>
          </div>

          <div v-if="estates.length > 0" class="mt-12">
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
              :active-class="'bg-primary text-white'"
              :prev-text="'&larr; Vorige'"
              :next-text="'Volgende &rarr;'"
              :hide-prev-next="false"
            />
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import ClickOutside from "vue-click-outside";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import Card from "../../../../_shared/Card/Default";
import Search from "./Search";
import Dropdown from "./Dropdown";
import Range from "./Range";
import ToggleMode from "./ToggleMode";

export default {
  props: ["component"],
  components: {
    Loading,
    Card,
    Search,
    Dropdown,
    Range,
    ToggleMode,
  },
  directives: {
    ClickOutside,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      request: {
        sort: {
          order: "desc", // desc, asc
          orderBy: "relevance", // relevance, price, date
        },
        filters: {},
      },
      config: {
        location: {
          focus: false,
          search: "",
        },
        mode: "list",
        dropdowns: {
          negotiation: {
            open: false,
            selected: false,
            placeholder: "Buy or rent?",
            dropdown: [
              {
                value: false,
                label: "Buy & rent",
              },
              {
                value: "sale",
                label: "Buy",
              },
              {
                value: "let",
                label: "Rent",
              },
            ],
          },
          category: {
            open: false,
            selected: false,
            placeholder: "Categories",
            dropdown: [
              {
                value: [],
                label: "All categories",
              },
              {
                value: ["house"],
                label: "House",
              },
              {
                value: ["apartment"],
                label: "Apartment",
              },
              {
                value: ["land"],
                label: "Land",
              },
              {
                value: ["office"],
                label: "Office",
              },
              {
                value: ["commercial"],
                label: "Commercial",
              },
              {
                value: ["parking"],
                label: "Parking",
              },
            ],
          },
          price: {
            open: false,
            selected: {
              price: [0, 1000000],
            },
            placeholder: "Price",
            range: [
              {
                id: "price",
                symbol: "€",
                symbol_position: "before",
                label: "Price in",
                interval: 10000,
                min: 0,
                max: 1000000,
              },
            ],
          },
          sizes: {
            open: false,
            selected: {
              plot_area: [0, 1000],
              liveable_area: [0, 500],
            },
            placeholder: "Size",
            range: [
              {
                id: "plot_area",
                symbol: "m²",
                symbol_position: "after",
                label: "Lot size",
                interval: 10,
                min: 0,
                max: 1000,
              },
              {
                id: "liveable_area",
                symbol: "m²",
                symbol_position: "after",
                label: "Liveable area",
                interval: 10,
                min: 0,
                max: 500,
              },
            ],
          },
        },
      },
    };
  },
  mounted() {
    this.$events();
    this.$list();
  },
};
</script>
