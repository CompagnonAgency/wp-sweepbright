<template>
  <a :href="estate.permalink" class="flex flex-col h-full">
    <div class="relative overflow-hidden" :class="`${theme.rounded_t}`">
      <vue-glide :perView="1" type="carousel" :gap="0">
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
          <button
            data-glide-dir="<"
            class="absolute bottom-0 right-0 w-12 py-2 mr-12 text-lg text-white transition duration-200 bg-black appearance-none focus:outline-none hover:bg-gray-800"
          >
            <i class="far fa-chevron-left"></i>
          </button>
          <button
            data-glide-dir=">"
            class="absolute bottom-0 right-0 w-12 py-2 text-lg text-white transition duration-200 bg-black appearance-none focus:outline-none hover:bg-gray-800"
          >
            <i class="-mr-1 far fa-chevron-right"></i>
          </button>
        </template>
      </vue-glide>
    </div>

    <div
      class="flex-1 p-10 transition duration-200"
      :class="`${variant === 'light' ? 'bg-white' : 'bg-gray-100'} ${
        theme.rounded_b
      }`"
    >
      <div class="flex items-center justify-between">
        <p class="text-sm tracking-wide uppercase">
          {{ data.locale[lang].type[estate.meta.features.type] }}
        </p>
        <i class="text-base text-red-500 cursor-pointer fas fa-heart"></i>
      </div>
      <p :href="estate.permalink" class="mt-2 mb-3 text-2xl">
        {{ estate.meta.estate.title[lang] }}
      </p>
      <p class="text-base font-semibold tracking-wide uppercase">
        {{ price }}
      </p>
    </div>
  </a>
</template>

<script>
import { VueAgile } from "vue-agile";
import { Glide, GlideSlide } from "vue-glide-js";
import "vue-glide-js/dist/vue-glide.css";

export default {
  props: ["component", "estate", "variant"],
  components: {
    agile: VueAgile,
    [Glide.name]: Glide,
    [GlideSlide.name]: GlideSlide,
  },
  data() {
    return {
      theme: window.theme,
      data: window[this.component],
      lang: window.lang,
      config: {
        negotiation: {
          open: false,
          dropdown: [],
        },
      },
      store: {},
    };
  },
  methods: {},
  mounted() {},
};
</script>
