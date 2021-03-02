<template>
  <div
    v-click-outside="closeSuggest"
    class="flex flex-col w-full p-2 mx-auto mt-16 transition-all duration-300 bg-white border-2 shadow-md lg:max-w-2xl lg:flex-row lg:items-center"
    :class="`${theme.rounded} lg:${theme.rounded_lg} ${
      config.geosuggest.focus ? 'border-primary' : 'border-gray-300'
    }`"
  >
    <div class="relative z-20 flex-1">
      <input
        type="text"
        class="px-5 font-semibold text-center placeholder-gray-400 bg-transparent border-none lg:text-left"
        :placeholder="data.search_placeholder"
        v-model="store.search"
        @input="openSuggest"
        @focus="highlight"
        @blur="config.geosuggest.focus = false"
      />
      <GeoSuggest
        class="absolute top-0 left-0 w-full mt-12 border border-gray-200"
        :country="data.search_country"
        :types="['(cities)']"
        :search="store.search"
        :suggestion="config.geosuggest.selectedSuggestion"
        @geocoded="geocode($event)"
      >
        <template v-slot="{ suggestions }">
          <div
            class="overflow-hidden bg-white shadow-md"
            :class="`${theme.rounded}`"
            v-if="suggestions.length && config.geosuggest.open"
          >
            <ul class="font-medium divide-y divide-gray-100">
              <li
                v-for="(suggestion, index) in suggestions"
                :key="index"
                @click="setSuggestion(suggestion)"
                class="px-3 py-2 transition duration-200 cursor-pointer hover:bg-gray-100"
              >
                {{ suggestion.description }}
              </li>
            </ul>
          </div>
        </template>
      </GeoSuggest>
    </div>

    <div
      class="relative z-10 justify-center flex-shrink-0 py-3 mb-5 border-t border-b lg:mr-5 lg:mb-0 lg:border-0 lg:py-0 lg:justify-start"
    >
      <div
        class="flex items-center justify-center w-full h-full font-semibold text-gray-700 cursor-pointer select-none"
        @click="
          config.negotiation.open = !config.negotiation.open;
          config.geosuggest.open = false;
        "
      >
        <p class="flex-shrink-0">
          <template v-if="store.negotiation">
            {{
              config.negotiation.dropdown.find(
                (obj) => obj.value === store.negotiation
              ).label
            }}
          </template>
          <template v-else>
            {{ data.search_dropdown }}
          </template>
        </p>
        <i class="mt-1 ml-2 text-base text-gray-400 far fa-chevron-down"></i>
      </div>

      <div
        class="absolute top-0 left-0 w-full overflow-hidden bg-white border border-gray-200 shadow-md lg:w-32 mt-9"
        :class="`${theme.rounded}`"
        v-if="config.negotiation.open"
      >
        <ul class="font-medium divide-y divide-gray-100">
          <li
            v-for="(dropdown, index) in config.negotiation.dropdown"
            :key="index"
            @click="
              store.negotiation = dropdown.value;
              config.negotiation.open = false;
            "
            class="px-3 py-2 transition duration-200 cursor-pointer hover:bg-gray-100"
          >
            {{ dropdown.label }}
          </li>
        </ul>
      </div>
    </div>

    <button class="flex-shrink-0 btn bg-primary" @click="search">
      <i class="mr-1 fal fa-search"></i>
      {{ data.search_button }}
    </button>
  </div>
</template>

<script>
import { GeoSuggest, loadGmaps } from "vue-geo-suggest";
import ClickOutside from "vue-click-outside";

export default {
  props: ["component"],
  components: {
    GeoSuggest,
  },
  directives: {
    ClickOutside,
  },
  data() {
    return {
      theme: window.theme,
      data: window[this.component],
      config: {
        negotiation: {
          open: false,
          dropdown: [],
        },
        geosuggest: {
          focus: false,
          open: false,
          selectedSuggestion: null,
          address: null,
        },
      },
      store: {
        lat: false,
        lng: false,
        search: "",
        negotiation: "",
      },
    };
  },
  methods: {
    search() {
      let destination = this.data.destination_page.url;

      if (this.store.search || this.store.negotiation) {
        destination += "?";
      }

      if (this.store.search) {
        destination += `region=${this.store.search}&lat=${this.store.lat}&lng=${this.store.lng}&`;
      }

      if (this.store.negotiation) {
        destination += `negotiation=${this.store.negotiation}&`;
      }

      if (this.store.search || this.store.negotiation) {
        destination = destination.slice(0, -1);
      }
      window.location.href = destination;
    },
    highlight() {
      this.config.geosuggest.focus = true;
      this.config.negotiation.open = false;
    },
    openSuggest() {
      this.config.geosuggest.open = true;
    },
    closeSuggest() {
      this.config.geosuggest.open = false;
      this.config.negotiation.open = false;
    },
    setSuggestion(suggestion) {
      this.config.geosuggest.selectedSuggestion = suggestion;
      this.closeSuggest();
    },
    geocode($event) {
      this.store.search = $event.name;
      this.store.lat = $event.location.lat;
      this.store.lng = $event.location.lng;
    },
    setDropdown() {
      this.config.negotiation.dropdown = [
        {
          label: this.data.buy,
          value: "sale",
        },
        {
          label: this.data.rent,
          value: "let",
        },
      ];
    },
  },
  mounted() {
    this.setDropdown();
    loadGmaps("AIzaSyDeE8uomuOzvqJ43ULf_gJLrVj3vJWb7uo");
  },
};
</script>
