<template>
  <div
    class="relative flex-1"
    :class="'z-' + zIndex"
  >
    <tags-input 
      :wrapper-class="`tags-input-wrapper-default ${theme.rounded_lg}`" 
      input-id="tag-input"
      @change="autoComplete"
      @tag-removed="filterResults"
      :only-existing-tags="true"
      :before-adding-tag="beforeAddingTag"
      :placeholder="data.search_placeholder"
      :limit="5"
      :typeahead="true"
      typeahead-style="dropdown"
      :existing-tags="config.geosuggest.suggestions"
      :typeahead-hide-discard="true"
      v-model="store.selectedTags">
    </tags-input>

    <GeoSuggest
      class="absolute top-0 left-0 w-full overflow-hidden border border-gray-200 opacity-25 mt-14"
      :country="data.search_country"
      :types="['sublocality', 'postal_code', 'locality']"
      :search="searchDefault"
      @suggestions="loadSuggestions"
    >
    </GeoSuggest>
  </div>
</template>

<script>
import { GeoSuggest, loadGmaps } from "vue-geo-suggest";
import ClickOutside from "vue-click-outside";
import VoerroTagsInput from '@voerro/vue-tagsinput';

export default {
  props: ["component", "location", "filters", "zIndex"],
  components: {
    GeoSuggest,
    'tags-input': VoerroTagsInput,
  },
  directives: {
    ClickOutside,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      searchDefault: '',
      store: {
        searchEdited: false,
        search: "",
        selectedTags: [],
      },
      config: {
        geosuggest: {
          open: false,
          suggestions: [],
        },
      },
    };
  },
  methods: {
    autoComplete(e) {
      this.searchDefault = e;
      this.store.searchEdited = true;
    },
    loadSuggestions(e) {
      const suggestions = [];
      e.forEach(suggestion => {
        suggestions.push({
          key: suggestion.placeId,
          placeId: suggestion.placeId,
          latLng: false,
          value: suggestion.description
        });
      });

      this.config.geosuggest.suggestions = suggestions;
    },
    filterResults() {
      this.$bus.$emit("setLocations", JSON.parse(JSON.stringify(this.store.selectedTags)));
    },
    beforeAddingTag(e) {
      if (e.placeId) {
        e.value = '';

        const geocoder = new google.maps.Geocoder();

        geocoder
            .geocode({ placeId: e.placeId })
            .then(({ results }) => {
                if (results && results[0]) {
                  const location = results[0];

                  let postalCode = location.address_components.find((component) => {
                    return component.types.includes("postal_code");
                  });

                  let locality = location.address_components.find((component) => {
                    return component.types.includes("locality");
                  });

                  let sublocality = location.address_components.find((component) => {
                    return component.types.includes("sublocality");
                  });

                  value = locality.short_name;
                  if (sublocality) {
                    value = sublocality.short_name;
                  }

                  if (postalCode) {
                    value += ` (${postalCode.short_name})`;
                  }

                  e.latLng = {
                    lat: location.geometry.location.lat(),
                    lng: location.geometry.location.lng(),
                  };
                  e.value = value;

                  this.filterResults();
                }
            })
            .catch((e) => console.log("Geocoder failed due to: " + e));
      }

      return true;
    },
  },
  mounted() {
    if (this.location.region) {
      this.$nextTick(() => {
        this.store.selectedTags.push({
          key: this.location.region,
          placeId: false,
          latLng: {
            lat: this.location.lat,
            lng: this.location.lng,
          },
          value: this.location.region,
        });
      });
    }
    loadGmaps("AIzaSyDeE8uomuOzvqJ43ULf_gJLrVj3vJWb7uo");
  },
};
</script>
