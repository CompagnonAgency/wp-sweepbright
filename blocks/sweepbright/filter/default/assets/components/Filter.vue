<template>
  <div data-sweepbright-list>
    <div class="relative z-20">
      <div class="mb-5 lg:mb-10 post">
        <h2>
          {{ data.heading }}
        </h2>
      </div>

      <div
        class="
          flex flex-col
          space-y-3
          text-base
          lg:flex-row
          lg:space-x-7 lg:space-y-0
          text-dark
        "
      >
        <Search
          :zIndex="40"
          :location="config.location"
          :component="component"
          :filters="request.filters"
        ></Search>

        <Dropdown
          :zIndex="30"
          v-if="this.data.filters === 'all'"
          id="negotiation"
          :dropdown="config.dropdowns.negotiation"
        ></Dropdown>

        <Dropdown
          :zIndex="20"
          id="category"
          :dropdown="config.dropdowns.category"
        ></Dropdown>

        <Range :zIndex="10" id="price" :range="config.dropdowns.price"></Range>

        <Range :zIndex="0" id="sizes" :range="config.dropdowns.sizes"></Range>
      </div>
    </div>
  </div>
</template>

<script>
import Search from "./Search";
import Dropdown from "./Dropdown";
import Range from "./Range";

export default {
  props: ["component"],
  components: {
    Search,
    Dropdown,
    Range,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      configCache: {},
      config: {
        location: {
          focus: false,
          region: "",
        },
        dropdowns: {
          negotiation: {
            open: false,
            selected: false,
            placeholder:
              window[this.component].locale[window.lang].filters.negotiation
                .placeholder,
            dropdown: [
              {
                value: false,
                label:
                  window[this.component].locale[window.lang].filters.negotiation
                    .label,
              },
              {
                value: "sale",
                label:
                  window[this.component].locale[window.lang].filters.negotiation
                    .sale,
              },
              {
                value: "let",
                label:
                  window[this.component].locale[window.lang].filters.negotiation
                    .let,
              },
            ],
          },
          category: {
            open: false,
            selected: false,
            placeholder:
              window[this.component].locale[window.lang].type.categories,
            dropdown: [
              {
                value: [],
                label:
                  window[this.component].locale[window.lang].type
                    .all_categories,
              },
              {
                value: ["house"],
                label: window[this.component].locale[window.lang].type.house,
              },
              {
                value: ["apartment"],
                label:
                  window[this.component].locale[window.lang].type.apartment,
              },
              {
                value: ["land"],
                label: window[this.component].locale[window.lang].type.land,
              },
              {
                value: ["office"],
                label: window[this.component].locale[window.lang].type.office,
              },
              {
                value: ["commercial"],
                label:
                  window[this.component].locale[window.lang].type.commercial,
              },
              {
                value: ["parking"],
                label: window[this.component].locale[window.lang].type.parking,
              },
            ],
          },
          price: {
            open: false,
            selected: {
              price: [0, 1000000],
            },
            placeholder:
              window[this.component].locale[window.lang].filters.price
                .placeholder,
            range: [
              {
                id: "price",
                symbol: "",
                symbol_position: "before",
                label:
                  window[this.component].locale[window.lang].filters.price
                    .label,
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
            placeholder:
              window[this.component].locale[window.lang].filters.sizes
                .placeholder,
            range: [
              {
                id: "plot_area",
                symbol: "m²",
                symbol_position: "after",
                label:
                  window[this.component].locale[window.lang].filters.sizes
                    .plot_area_label,
                interval: 10,
                min: 0,
                max: 1000,
              },
              {
                id: "liveable_area",
                symbol: "m²",
                symbol_position: "after",
                label:
                  window[this.component].locale[window.lang].filters.sizes
                    .liveable_area_label,
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
  methods: {
    defaultLocales() {
      this.config.dropdowns.price.range[0].symbol = this.$getUnits(
        window.lang
      ).currency;
      this.config.dropdowns.sizes.range[0].symbol = this.$getUnits(
        window.lang
      ).plot_area;
      this.config.dropdowns.sizes.range[1].symbol = this.$getUnits(
        window.lang
      ).liveable_area;
    },
    defaultFilters() {
      if (this.$urlParam().negotiation) {
        this.config.dropdowns.negotiation.selected =
          this.$urlParam().negotiation;
      }

      if (this.$urlParam().region) {
        this.config.location.region = this.$urlParam().region;
      }
    },
    init() {
      this.defaultFilters();
      this.defaultLocales();
      this.configCache = JSON.parse(JSON.stringify(this.config));
      this.configCache.location.region = "";
      this.$events();

      window.addEventListener("filterReset", () => {
        this.config = JSON.parse(JSON.stringify(this.configCache));
        this.$forceUpdate();
      });
    },
  },
  mounted() {
    this.init();
  },
};
</script>
