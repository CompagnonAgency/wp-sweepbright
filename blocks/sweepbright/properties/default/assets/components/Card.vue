<template>
  <a
    :href="estate.permalink"
    class="relative flex flex-col h-full overflow-hidden text-dark group"
    :class="`${data.card_border_radius}`"
    @click.prevent="openWindow(estate)"
  >
    <div
      class="absolute top-0 left-0 z-10 m-5 text-sm tracking-wide text-white uppercase"
    >
      <div
        class="inline-block p-2 mr-2 bg-black"
        :class="`${data.card_border_radius}`"
      >
        <template v-if="estate.meta.features.negotiation === 'sale'">
          {{ data.locale[lang].status[estate.meta.estate.status] }}
        </template>

        <template v-if="estate.meta.features.negotiation === 'let'">
          <template
            v-if="
              data.available_properties === 'available' &&
              estate.meta.estate.status === 'available'
            "
          >
            {{ data.locale[lang].status.for_rent }}
          </template>
          <template
            v-else-if="
              data.available_properties === 'under_contract' &&
              (estate.meta.estate.status === 'available' ||
                estate.meta.estate.status === 'under_contract')
            "
          >
            {{ data.locale[lang].status.for_rent }}
          </template>
          <template v-else>
            {{ data.locale[lang].status[estate.meta.estate.status] }}
          </template>
        </template>
      </div>
      <div
        class="inline-block p-2 mr-2 bg-black"
        :class="`${data.card_border_radius}`"
        v-if="
          estate.meta.open_homes.hasOpenHome &&
          estate.meta.estate.status === 'available'
        "
      >
        {{ data.locale[lang].open_home }}
      </div>
    </div>

    <div class="relative overflow-hidden estate-card-default">
      <vue-glide :perView="1" type="carousel" :gap="0" ref="carousel">
        <vue-glide-slide
          v-for="(image, index) in estate.meta.features.images.slice(0, 5)"
          :key="index"
        >
          <div class="relative">
            <div class="aspect-ratio-4/3"></div>
            <img
              class="absolute top-0 left-0 z-0 object-cover object-center w-full h-full"
              :src="image.sizes.large"
            />
          </div>
        </vue-glide-slide>
        <template slot="control">
          <div v-if="estate.meta.estate.status === 'available'">
            <button
              class="absolute bottom-0 right-0 w-12 py-2 mr-12 text-lg text-white transition duration-200 bg-black appearance-none focus:outline-none hover:bg-gray-800"
              @click.stop="back"
            >
              <i class="far fa-chevron-left"></i>
            </button>
            <button
              class="absolute bottom-0 right-0 w-12 py-2 text-lg text-white transition duration-200 bg-black appearance-none focus:outline-none hover:bg-gray-800"
              @click.stop="next"
            >
              <i class="-mr-1 far fa-chevron-right"></i>
            </button>
          </div>
        </template>
      </vue-glide>
    </div>

    <div
      class="flex-1 p-5 transition duration-200 lg:p-10"
      :class="`${data.card_style === 'light' ? 'bg-white' : 'bg-light'}`"
    >
      <div class="flex items-start justify-between">
        <p class="text-sm tracking-wide uppercase">
          {{ data.locale[lang].negotiation[estate.meta.features.negotiation] }}
          |
          {{ estate.meta.location.city }}
        </p>
        <div
          class="-mt-1 text-lg transition duration-200 transform cursor-pointer hover:scale-110"
          @click.stop="toggleFavorite"
          v-if="data.favorites"
        >
          <template v-if="checkFavorited">
            <i class="text-red-500 fas fa-heart"></i>
          </template>
          <template v-else>
            <i class="fal fa-heart"></i>
          </template>
        </div>
      </div>
      <p
        :href="estate.permalink"
        class="mt-2 text-lg font-semibold lg:text-xl font-secondary"
      >
        {{ estate.meta.estate.title[lang] }}
      </p>
      <div class="flex items-center mt-5">
        <div class="w-1/2">
          <p
            class="text-base font-semibold tracking-wide uppercase"
            v-if="
              (estate.meta.estate.status === 'available' &&
                data.available_properties === 'available') ||
              (estate.meta.estate.status === 'under_contract' &&
                data.available_properties === 'under_contract' &&
                !estate.meta.price.hidden)
            "
          >
            <template v-if="estate.meta.features.negotiation === 'sale'">
              {{ getPrice(estate.meta.price.amount) }}
            </template>
            <template v-else>
              {{ getPrice(estate.meta.price.amount) }}
              <span>/</span> {{ data.locale[lang].month }}
            </template>
          </p>
        </div>
        <div class="flex justify-end w-1/2">
          <i
            class="transition-all duration-200 transform fal fa-arrow-right group-hover:translate-x-2"
          ></i>
        </div>
      </div>
    </div>

    <div
      class="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 bg-black bg-opacity-10 group-hover:w-full"
    ></div>
  </a>
</template>

<script>
import { Glide, GlideSlide } from 'vue-glide-js'

export default {
  props: ['component', 'estate', 'favorites'],
  components: {
    [Glide.name]: Glide,
    [GlideSlide.name]: GlideSlide,
  },
  computed: {
    checkFavorited: {
      get() {
        let isFavorited = false
        if (this.favorites.includes(parseFloat(this.estate.id))) {
          isFavorited = true
        }
        this.isFavorited = isFavorited
        return isFavorited
      },
    },
  },
  data() {
    return {
      theme: window.theme,
      data: window[this.component],
      lang: window.lang,
      isFavorited: false,
      config: {
        negotiation: {
          open: false,
          dropdown: [],
        },
      },
      store: {},
    }
  },
  methods: {
    getPrice(price) {
      let currency = ''
      let format = ''
      switch (this.estate.meta.price.currency) {
        case 'EUR':
          currency = '€'
          format = 'DOT'
          break
        case 'USD':
          currency = '$'
          format = 'COMMA'
          break
        case 'GBP':
          currency = '£'
          format = 'COMMA'
          break
        default:
          break
      }
      return `${currency} ${this.$formatNumber(price, format)}`
    },
    toggleFavorite(e) {
      this.isFavorited = !this.isFavorited
      e.preventDefault()
      this.$bus.$emit('favorite', this.isFavorited, parseFloat(this.estate.id))
      return false
    },
    back(e) {
      this.$refs.carousel.glide.go('<')
      e.preventDefault()
      return false
    },
    next(e) {
      this.$refs.carousel.glide.go('>')
      e.preventDefault()
      return false
    },
    openWindow(data) {
      if (data.meta.estate.status === 'available') {
        window.location.href = data.permalink
      } else if (
        data.meta.estate.status !== 'available' &&
        this.data.unavailable_properties === 'visible'
      ) {
        window.location.href = data.permalink
      } else if (
        data.meta.estate.status === 'under_contract' &&
        this.data.available_properties === 'under_contract'
      ) {
        window.location.href = data.permalink
      } else {
        this.$bus.$emit('openModal', data)
      }
    },
  },
}
</script>
