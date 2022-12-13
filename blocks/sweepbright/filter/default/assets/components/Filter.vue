<template>
  <div data-sweepbright-list>
    <div class="relative z-20">
      <div class="mb-5 lg:mb-10 post">
        <h2>
          {{ data.heading }}
        </h2>
      </div>

      <div
        class="flex flex-col space-y-3 text-base lg:items-start lg:flex-row lg:space-x-7 lg:space-y-0 text-dark"
      >
        <MultiSearch
          :zIndex="50"
          :location="config.location"
          :locations="config.locations"
          :component="component"
          :filters="request.filters"
        ></MultiSearch>

        <Dropdown
          :zIndex="40"
          v-if="this.data.filters === 'all'"
          id="negotiation"
          :dropdown="config.dropdowns.negotiation"
        ></Dropdown>

        <Dropdown
          :zIndex="30"
          id="category"
          :dropdown="config.dropdowns.category"
        ></Dropdown>

        <Dropdown
          v-if="data.subcategory_filter === 'true'"
          :zIndex="20"
          id="subcategory"
          :dropdown="config.dropdowns.subcategory"
        ></Dropdown>

        <Range :zIndex="10" id="price" :range="config.dropdowns.price"></Range>

        <Range :zIndex="0" id="sizes" :range="config.dropdowns.sizes"></Range>
      </div>
    </div>
  </div>
</template>

<script>
import MultiSearch from './MultiSearch'
import Dropdown from './Dropdown'
import Range from './Range'

export default {
  props: ['component'],
  components: {
    MultiSearch,
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
        locations: [],
        location: {
          lat: false,
          lng: false,
          region: '',
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
                value: 'sale',
                label:
                  window[this.component].locale[window.lang].filters.negotiation
                    .sale,
              },
              {
                value: 'let',
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
                value: ['house'],
                label: window[this.component].locale[window.lang].type.house,
              },
              {
                value: ['apartment'],
                label:
                  window[this.component].locale[window.lang].type.apartment,
              },
              {
                value: ['land'],
                label: window[this.component].locale[window.lang].type.land,
              },
              {
                value: ['office'],
                label: window[this.component].locale[window.lang].type.office,
              },
              {
                value: ['commercial'],
                label:
                  window[this.component].locale[window.lang].type.commercial,
              },
              {
                value: ['parking'],
                label: window[this.component].locale[window.lang].type.parking,
              },
            ],
          },
          subcategory: {
            open: false,
            selected: false,
            placeholder:
              window[this.component].locale[window.lang].type.subcategories,
            dropdown: [
              {
                value: [],
                label:
                  window[this.component].locale[window.lang].type
                    .all_subcategories,
              },
              {
                value: ['semi_detached'],
                label:
                  window[this.component].locale[window.lang].type.semi_detached,
              },
              {
                value: ['detached'],
                label: window[this.component].locale[window.lang].type.detached,
              },
              {
                value: ['terraced'],
                label: window[this.component].locale[window.lang].type.terraced,
              },
              {
                value: ['bungalow'],
                label: window[this.component].locale[window.lang].type.bungalow,
              },
              {
                value: ['villa'],
                label: window[this.component].locale[window.lang].type.villa,
              },
              {
                value: ['condo'],
                label: window[this.component].locale[window.lang].type.condo,
              },
              {
                value: ['loft'],
                label: window[this.component].locale[window.lang].type.loft,
              },
              {
                value: ['duplex'],
                label: window[this.component].locale[window.lang].type.duplex,
              },
              {
                value: ['penthouse'],
                label:
                  window[this.component].locale[window.lang].type.penthouse,
              },
              {
                value: ['student_accommodation'],
                label:
                  window[this.component].locale[window.lang].type
                    .student_accommodation,
              },
              {
                value: ['healthcare'],
                label:
                  window[this.component].locale[window.lang].type.healthcare,
              },
              {
                value: ['industrial'],
                label:
                  window[this.component].locale[window.lang].type.industrial,
              },
              {
                value: ['leasure_and_sports'],
                label:
                  window[this.component].locale[window.lang].type
                    .leasure_and_sports,
              },
              {
                value: ['restaurant_and_café'],
                label:
                  window[this.component].locale[window.lang].type
                    .restaurant_and_café,
              },
              {
                value: ['retail'],
                label: window[this.component].locale[window.lang].type.retail,
              },
              {
                value: ['shop'],
                label: window[this.component].locale[window.lang].type.shop,
              },
              {
                value: ['warehouse'],
                label:
                  window[this.component].locale[window.lang].type.warehouse,
              },
              {
                value: ['townhouse'],
                label:
                  window[this.component].locale[window.lang].type.townhouse,
              },
              {
                value: ['cottage'],
                label: window[this.component].locale[window.lang].type.cottage,
              },
              {
                value: ['mansion'],
                label: window[this.component].locale[window.lang].type.mansion,
              },
              {
                value: ['farm'],
                label: window[this.component].locale[window.lang].type.farm,
              },
              {
                value: ['investment_property'],
                label:
                  window[this.component].locale[window.lang].type
                    .investment_property,
              },
              {
                value: ['agricultural'],
                label:
                  window[this.component].locale[window.lang].type.agricultural,
              },
              {
                value: ['buildable'],
                label:
                  window[this.component].locale[window.lang].type.buildable,
              },
              {
                value: ['recreational'],
                label:
                  window[this.component].locale[window.lang].type.recreational,
              },
              {
                value: ['pasture_land'],
                label:
                  window[this.component].locale[window.lang].type.pasture_land,
              },
              {
                value: ['coworking'],
                label:
                  window[this.component].locale[window.lang].type.coworking,
              },
              {
                value: ['flex_office'],
                label:
                  window[this.component].locale[window.lang].type.flex_office,
              },
              {
                value: ['open_office'],
                label:
                  window[this.component].locale[window.lang].type.open_office,
              },
              {
                value: ['private_garage'],
                label:
                  window[this.component].locale[window.lang].type
                    .private_garage,
              },
              {
                value: ['indoor_parking_space'],
                label:
                  window[this.component].locale[window.lang].type
                    .indoor_parking_space,
              },
              {
                value: ['outdoor_parking_space'],
                label:
                  window[this.component].locale[window.lang].type
                    .outdoor_parking_space,
              },
              {
                value: ['covered_outdoor_space'],
                label:
                  window[this.component].locale[window.lang].type
                    .covered_outdoor_space,
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
                id: 'price',
                symbol: '€',
                symbol_position: 'before',
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
                id: 'plot_area',
                symbol: 'm²',
                symbol_position: 'after',
                label:
                  window[this.component].locale[window.lang].filters.sizes
                    .plot_area_label,
                interval: 10,
                min: 0,
                max: 1000,
              },
              {
                id: 'liveable_area',
                symbol: 'm²',
                symbol_position: 'after',
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
    }
  },
  methods: {
    defaultLocales() {
      this.config.dropdowns.price.range[0].symbol = this.$getUnits(
        window.lang
      ).currency
      this.config.dropdowns.sizes.range[0].symbol = this.$getUnits(
        window.lang
      ).plot_area
      this.config.dropdowns.sizes.range[1].symbol = this.$getUnits(
        window.lang
      ).liveable_area
    },
    defaultFilters() {
      // Negotiation
      if (this.$urlParam().negotiation) {
        this.config.dropdowns.negotiation.selected =
          this.$urlParam().negotiation
      }

      // Location: latitude
      if (this.$urlParam().lat) {
        this.config.location.lat = this.$urlParam().lat
      }

      // Location: longitude
      if (this.$urlParam().lng) {
        this.config.location.lng = this.$urlParam().lng
      }

      // Location: region
      if (this.$urlParam().region) {
        this.config.location.region = this.$urlParam().region
      }

      // Locations
      if (this.$urlParam().locations) {
        const locations = this.$urlParam().locations
          ? decodeURIComponent(this.$urlParam().locations.replace(/\+/g, '%20'))
              .split('[')
              .slice(1)
          : false

        const locationArr = []
        if (locations) {
          locations.forEach((item) => {
            const location = item.slice(0, -1).split(',')
            location[1] = parseFloat(location[1])
            location[2] = parseFloat(location[2])
            locationArr.push({
              value: location[0],
              latLng: {
                lat: location[1],
                lng: location[2],
              },
            })
          })
        }

        this.config.locations = locationArr
      }

      // Category
      if (this.$urlParam().category) {
        this.config.dropdowns.category.selected = [this.$urlParam().category]
      }

      // Subcategory
      if (this.$urlParam().subcategory) {
        this.config.dropdowns.subcategory.selected = [
          this.$urlParam().subcategory,
        ]
      }

      // Plot area
      const plot_area = this.$urlParam().plot_area
        ? decodeURIComponent(this.$urlParam().plot_area).split(',')
        : false
      if (this.$urlParam().plot_area && plot_area && plot_area.length === 2) {
        plot_area[1] =
          plot_area[1] === 'false'
            ? this.config.dropdowns.sizes.range[1].max
            : plot_area[1]
        this.config.dropdowns.sizes.selected.plot_area = plot_area
      }

      // Liveable area
      const liveable_area = this.$urlParam().liveable_area
        ? decodeURIComponent(this.$urlParam().liveable_area).split(',')
        : false
      if (
        this.$urlParam().liveable_area &&
        liveable_area &&
        liveable_area.length === 2
      ) {
        liveable_area[1] =
          liveable_area[1] === 'false'
            ? this.config.dropdowns.sizes.range[1].max
            : liveable_area[1]
        this.config.dropdowns.sizes.selected.liveable_area = liveable_area
      }

      // Price
      const price = this.$urlParam().price
        ? decodeURIComponent(this.$urlParam().price).split(',')
        : false
      if (this.$urlParam().price && price && price.length === 2) {
        price[1] =
          price[1] === 'false'
            ? this.config.dropdowns.price.range[0].max
            : price[1]
        this.config.dropdowns.price.selected.price = price
      }
    },
    setUrlParam(name, value) {
      const searchParams = new URLSearchParams(window.location.search)
      searchParams.set(name, value)

      const newRelativePathQuery = `${
        window.location.pathname
      }?${searchParams.toString()}`
      window.history.pushState(null, '', newRelativePathQuery)
    },
    removeUrlParam(name) {
      const searchParams = new URLSearchParams(window.location.search)
      searchParams.delete(name)

      const newRelativePathQuery = `${
        window.location.pathname
      }?${searchParams.toString()}`
      window.history.pushState(null, '', newRelativePathQuery)
    },
    setFilter(filters) {
      switch (filters.type) {
        case 'string':
          const stringDefault = filters.value ? filters.value : ''
          this.setUrlParam(filters.name, stringDefault)
          break
        case 'array':
          const arrayDefault = filters.value[0] ? filters.value[0] : ''
          this.setUrlParam(filters.name, arrayDefault)
          break
        case 'range':
          this.setUrlParam(
            filters.name,
            filters.value[0] + ',' + filters.value[1]
          )
          break
        case 'location':
          const region = filters.value.region ? filters.value.region : ''
          const lat = filters.value.lat ? filters.value.lat : ''
          const lng = filters.value.lng ? filters.value.lng : ''

          this.setUrlParam('region', region)
          this.setUrlParam('lat', lat)
          this.setUrlParam('lng', lng)
          break
        case 'locations':
          let locations = ''

          filters.value.forEach((location, index) => {
            locations += '['
            locations += location.value + ','
            locations += location.latLng.lat + ','
            locations += location.latLng.lng
            locations += ']'

            if (index < filters.value.length - 1) {
              locations += ','
            }
          })

          this.setUrlParam(filters.name, locations)
          break
      }
    },
    init() {
      this.configCache = JSON.parse(JSON.stringify(this.config))
      this.configCache.location.region = ''
      this.defaultFilters()
      this.$events()

      window.addEventListener('filterReset', (e) => {
        this.removeUrlParam('locations')
        this.removeUrlParam('category')
        this.removeUrlParam('subcategory')
        this.removeUrlParam('negotiation')
        this.removeUrlParam('price')
        this.removeUrlParam('plot_area')
        this.removeUrlParam('liveable_area')
        this.config = JSON.parse(JSON.stringify(this.configCache))
      })

      window.addEventListener('filterUpdate', (e) => {
        this.setFilter(e.detail.field)
      })
    },
  },
  mounted() {
    this.init()
  },
}
</script>
