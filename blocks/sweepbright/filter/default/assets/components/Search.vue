<template>
  <div
    v-click-outside="closeSuggest"
    class="relative flex-1"
    :class="'z-' + zIndex"
  >
    <i
      class="absolute top-0 left-0 mt-3 lg:mt-3.5 text-base far fa-search"
      :class="theme.form_style === 'line' ? 'ml-0' : 'ml-5'"
    ></i>
    <input
      type="text"
      class="w-full h-full pr-5 placeholder-black placeholder-opacity-50  form-input"
      :class="`${theme.form_style === 'line' ? 'pl-7' : 'pl-11'} ${
        theme.rounded_lg
      } ${location.focus ? 'border-primary' : ''}`"
      v-model="searchDefault"
      @input="openSuggest"
      @focus="searchFocus(true)"
      @blur="searchFocus(false)"
      :placeholder="data.search_placeholder"
    />
    <GeoSuggest
      class="absolute top-0 left-0 w-full overflow-hidden border border-gray-200  mt-14"
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
              class="px-3 py-2 transition duration-200 cursor-pointer  hover:bg-gray-100"
            >
              {{ suggestion.description }}
            </li>
          </ul>
        </div>
      </template>
    </GeoSuggest>
  </div>
</template>

<script>
import { GeoSuggest, loadGmaps } from 'vue-geo-suggest'
import ClickOutside from 'vue-click-outside'

export default {
  props: ['component', 'location', 'filters', 'zIndex'],
  components: {
    GeoSuggest,
  },
  directives: {
    ClickOutside,
  },
  computed: {
    searchDefault: {
      get() {
        const val = this.store.searchEdited
          ? this.store.search
          : decodeURI(this.location.region)
        return val
      },
      set(value) {
        this.store.search = value
      },
    },
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      store: {
        searchEdited: false,
        search: '',
      },
      config: {
        geosuggest: {
          open: false,
          selectedSuggestion: null,
          address: null,
        },
      },
    }
  },
  methods: {
    searchFocus(focus) {
      if (!focus && !this.searchDefault) {
        this.$bus.$emit('setLocation', false)
      }
      this.$bus.$emit('searchFocus', focus)
    },
    openSuggest() {
      this.store.searchEdited = true
      this.config.geosuggest.open = true
    },
    closeSuggest() {
      this.config.geosuggest.open = false
    },
    setSuggestion(suggestion) {
      this.config.geosuggest.selectedSuggestion = suggestion
      this.closeSuggest()
    },
    geocode($event) {
      const location = $event.location
      location.region =
        this.config.geosuggest.selectedSuggestion.description.split(',')[0]
      this.$bus.$emit('setLocation', location)
      this.searchDefault = $event.name
    },
  },
  mounted() {
    loadGmaps('AIzaSyDeE8uomuOzvqJ43ULf_gJLrVj3vJWb7uo')
  },
}
</script>
