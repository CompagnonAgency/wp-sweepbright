<template>
  <div class="relative flex-1 wp-filter" :class="'z-' + zIndex">
    <i
      class="absolute top-0 left-0 mt-3.5 text-base far fa-search z-10"
      :class="theme.form_style === 'line' ? 'ml-0' : 'ml-5'"
    ></i>
    <tags-input
      ref="tagsinput"
      :wrapper-class="`tags-input-wrapper-default ${theme.rounded_lg} ${
        theme.form_style === 'line' ? 'is-line' : 'is-regular'
      }`"
      input-id="tag-input"
      @tag-added="filterResults"
      @tag-removed="filterResults"
      :only-existing-tags="true"
      :placeholder="data.search_placeholder"
      :typeahead-activation-threshold="2"
      :typeahead="true"
      typeahead-style="dropdown"
      :typeahead-callback="search_tags"
      :typeahead-hide-discard="true"
      v-model="store.selectedTags"
    >
    </tags-input>
  </div>
</template>

<script>
import VoerroTagsInput from '@voerro/vue-tagsinput'

export default {
  props: ['component', 'location', 'locations', 'filters', 'zIndex'],
  components: {
    'tags-input': VoerroTagsInput,
  },
  watch: {
    locations() {
      this.store.selectedTags = this.locations
    },
    'location.region'() {
      const region = this.location.region
      const decodedRegion = decodeURIComponent(region)

      this.store.selectedTags.push({
        key: this.location.region,
        placeId: false,
        latLng: {
          lat: parseFloat(this.location.lat),
          lng: parseFloat(this.location.lng),
        },
        value: decodedRegion,
      })
    },
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      store: {
        selectedTags: [],
      },
    }
  },
  methods: {
    async search_tags(query) {
      return await fetch(
        `/wp-json/v1/sweepbright/locations?search=${query}&country=${this.data.search_country}`
      )
        .then((response) => response.json())
        .then((data) => {
          setTimeout(() => {
            const searchParam = query
              .normalize('NFD')
              .replace(/[\u0300-\u036f]/g, '')
            this.$refs.tagsinput.doSearch(searchParam)
          }, 100)
          return data
        })
    },
    removeUrlParam(name) {
      const searchParams = new URLSearchParams(window.location.search)
      searchParams.delete(name)

      const newRelativePathQuery = `${
        window.location.pathname
      }?${searchParams.toString()}`
      window.history.pushState(null, '', newRelativePathQuery)
    },
    filterResults() {
      this.removeUrlParam('region')
      this.removeUrlParam('lat')
      this.removeUrlParam('lng')

      this.$bus.$emit(
        'setLocations',
        JSON.parse(JSON.stringify(this.store.selectedTags))
      )
    },
  },
}
</script>
