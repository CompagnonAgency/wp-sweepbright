<template>
  <div class="flex-1">
    <div
      v-click-outside="closeSuggest"
      class="relative transition-all duration-300 border-2"
      :class="`${theme.rounded_lg} ${
        location.focus ? 'border-primary' : 'border-gray-200'
      }`"
    >
      <i
        class="absolute top-0 left-0 mt-2 ml-5 text-lg text-gray-300 far fa-search"
      ></i>
      <input
        type="text"
        class="py-3 pl-12 pr-5 font-semibold placeholder-gray-400 bg-transparent border-none"
        v-model="searchDefault"
        @input="openSuggest"
        @focus="searchFocus(true)"
        @blur="searchFocus(false)"
        placeholder="Waar zoekt u een woning?"
      />
      <GeoSuggest
        class="absolute top-0 left-0 w-full overflow-hidden border-2 border-gray-200 mt-14"
        :country="data.search_country"
        :types="['(cities)']"
        :search="searchDefault"
        :suggestion="config.geosuggest.selectedSuggestion"
        @geocoded="geocode($event)"
      >
        <template v-slot="{ suggestions }">
          <div
            class="overflow-hidden bg-white"
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
  </div>
</template>

<script>
import { GeoSuggest, loadGmaps } from "vue-geo-suggest";
import ClickOutside from "vue-click-outside";

export default {
  props: ["component", "location", "search"],
  components: {
    GeoSuggest,
  },
  directives: {
    ClickOutside,
  },
  computed: {
    searchDefault: {
      get() {
        const val = this.store.searchEdited ? this.store.search : this.search;
        return val;
      },
      set(value) {
        this.store.search = value;
      },
    },
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      store: {
        search: "",
        searchEdited: false,
      },
      config: {
        geosuggest: {
          open: false,
          selectedSuggestion: null,
          address: null,
        },
      },
    };
  },
  methods: {
    searchFocus(focus) {
      if (!focus && !this.searchDefault) {
        this.$bus.$emit("setLocation", false);
      }
      this.$bus.$emit("searchFocus", focus);
    },
    openSuggest() {
      this.store.searchEdited = true;
      this.config.geosuggest.open = true;
    },
    closeSuggest() {
      this.config.geosuggest.open = false;
    },
    setSuggestion(suggestion) {
      this.config.geosuggest.selectedSuggestion = suggestion;
      this.closeSuggest();
    },
    geocode($event) {
      this.$bus.$emit("setLocation", $event.location);
      this.searchDefault = $event.name;
    },
  },
  mounted() {
    loadGmaps("AIzaSyDeE8uomuOzvqJ43ULf_gJLrVj3vJWb7uo");
  },
};
</script>
