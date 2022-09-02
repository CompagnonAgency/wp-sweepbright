<template>
  <div>
    <transition name="fade">
      <div
        v-if="menuOpen"
        class="fixed top-0 left-0 z-50 w-full h-full bg-black opacity-80"
        @click="closeMenu"
      ></div>
    </transition>

    <transition name="menu">
      <div
        v-if="menuOpen"
        class="fixed top-0 right-0 z-50 w-full p-10 pt-24 bg-white shadow-xl lg:pt-10 lg:h-full lg:w-96"
      >
        <button
          class="absolute top-0 right-0 px-8 py-5 text-xl text-gray-300 transition duration-200 focus:outline-none hover:text-gray-500 lg:hidden"
          @click="closeMenu"
        >
          <i class="fal fa-times"></i>
        </button>

        <div class="space-y-10 lg:space-y-16 post">
          <div v-if="data.email || data.phone">
            <h2>{{ data.locale[lang].contact }}</h2>
            <div class="mt-8 space-y-8">
              <div v-if="data.email">
                <h4 v-if="!data.hide_labels">{{ data.locale[lang].email }}</h4>
                <div class="mt-2">
                  <i class="w-7 fas fa-envelope"></i>
                  <a :href="`mailto:${data.email}`">{{ data.email }}</a>
                </div>
              </div>

              <div v-if="data.phone">
                <h4 v-if="!data.hide_labels">{{ data.locale[lang].phone }}</h4>
                <div class="mt-2">
                  <i class="w-7 fas fa-phone"></i>
                  <a :href="`tel:${data.phone}`">{{ data.phone }}</a>
                </div>
              </div>

              <div v-if="data.instagram_username">
                <h4 v-if="!data.hide_labels">{{ data.locale[lang].instagram }}</h4>
                <div class="mt-2">
                  <i class="w-7 fab fa-instagram"></i>
                  <a :href="`https://instagram.com/${data.instagram_username}`" target="_blank">@{{ data.instagram_username }}</a>
                </div>
              </div>

              <div v-if="data.whatsapp">
                <h4 v-if="!data.hide_labels">{{ data.locale[lang].whatsapp }}</h4>
                <div class="mt-2">
                  <i class="w-7 fab fa-whatsapp"></i>
                  <a :href="data.whatsapp" target="_blank">{{ data.locale[lang].whatsapp_chat }}</a>
                </div>
              </div>
            </div>
          </div>

          <div v-if="!data.enable_location">
            <h2>{{ data.locale[lang].office }}</h2>
            <div class="mt-8" v-html="data.location"></div>
          </div>
        </div>
      </div>
    </transition>

    <div class="fixed bottom-0 right-0 z-40 p-3 lg:p-10">
      <button
        data-scroll
        class="flex items-center space-x-2 btn btn-secondary"
        :class="
          data.negotiator.photo && data.negotiator.photo.sizes.medium
            ? 'pl-1 pt-1 pb-1'
            : ''
        "
        @click="toggleMenu"
      >
        <i class="mt-1 fal fa-comment-dots" v-if="!data.estate_id"></i>
        <img
          v-if="data.negotiator.photo && data.negotiator.photo.sizes.medium"
          :src="data.negotiator.photo.sizes.medium"
          class="object-cover object-center w-10 h-10 rounded-full"
        />
        <p>
          {{ data.locale[lang].contact_us }}
        </p>
      </button>
    </div>
  </div>
</template>

<script>
import $ from "jquery";

export default {
  props: ["component"],
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      menuOpen: false,
      bodyFixed: ["overflow-hidden", "h-screen"],
    };
  },
  methods: {
    toggleMenu() {
      if (
        this.data.negotiator.photo
        && this.data.negotiator.photo.sizes.medium
      ) {
        $("html, body").animate(
          {
            scrollTop: $("[data-contact]").offset().top,
          },
          800
        );
      } else {
        this.menuOpen = !this.menuOpen;

        if (this.menuOpen) {
          document.querySelector("body").classList.add(...this.bodyFixed);
        } else {
          document.querySelector("body").classList.remove(...this.bodyFixed);
        }
      }
    },
    closeMenu() {
      this.menuOpen = false;
      document.querySelector("body").classList.remove(...this.bodyFixed);
    },
  },
  mounted() {},
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  @apply transition-all duration-200;
}
.fade-enter,
.fade-leave-to {
  @apply opacity-0;
}

.menu-enter-active,
.menu-leave-active {
  @apply transition-all duration-200;
}
.menu-enter,
.menu-leave-to {
  @apply opacity-0 transform translate-x-full;
}
</style>
