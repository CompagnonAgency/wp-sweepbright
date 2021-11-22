<template>
  <div>
    <!-- Navigation -->
    <nav
      role="navigation"
      class="fixed top-0 flex w-full py-3 bg-white shadow z-60 lg:hidden js-navigation-mobile"
    >
      <div class="flex items-center flex-1 ml-4">
        <a href="/" class="inline-block w-32 outline-none">
          <img :src="data.large_logo" />
        </a>
      </div>

      <div class="flex items-center mx-4 space-x-5">
        <Favorites v-if="data.favorites.enabled" :component="component"></Favorites>
        <button
          class="flex items-center justify-center w-10 text-xl outline-none btn btn-primary"
          @click="toggleMenu"
        >
          <i v-if="!menuOpen" class="fal fa-bars"></i>
          <i v-else class="fal fa-times"></i>
        </button>
      </div>
    </nav>

    <!-- Menu -->
    <transition
      enter-active-class="transition-all duration-300 ease-in-out"
      leave-active-class="transition-all duration-300 ease-in-out"
      enter-class="transform translate-x-full opacity-0"
      enter-to-class="transform translate-x-0 opacity-100"
      leave-class="transform translate-x-0 opacity-100"
      leave-to-class="transform translate-x-full opacity-0"
    >
      <div
        v-if="menuOpen"
        class="fixed top-0 z-50 w-full h-screen pt-24 overflow-hidden bg-primary lg:hidden"
      >
        <div class="p-10">
          <ul class="space-y-8 text-3xl font-normal">
            <li v-for="(page, index) in data.pages" :key="index">
              <a :href="page.url" class="font-semibold text-white">{{ page.label }}</a>
            </li>

            <li v-if="data.call_to_action.label && data.call_to_action.link">
              <a
                :href="data.call_to_action.link"
                class="block w-full text-center outline-none btn btn-default"
                >{{ data.call_to_action.label }}</a
              >
            </li>

            <li v-if="data.multilanguage.enabled">
              <div class="relative inline-block pt-5 text-white border-t border-white border-opacity-25 group">
                <p class="mb-3 text-base font-semibold tracking-wide uppercase opacity-60">
                  <template v-if="data.multilanguage.current_lang === 'nl'"
                    >Kies uw taal</template
                  >
                  <template v-if="data.multilanguage.current_lang === 'fr'"
                    >Choisissez votre langue</template
                  >
                  <template v-if="data.multilanguage.current_lang === 'en'"
                    >Choose your language</template
                  >
                </p>
                <div class="flex items-center space-x-2">
                  <p class="text-lg font-semibold">
                    <template v-if="data.multilanguage.current_lang === 'nl'"
                      >Nederlands</template
                    >
                    <template v-if="data.multilanguage.current_lang === 'fr'"
                      >Français</template
                    >
                    <template v-if="data.multilanguage.current_lang === 'en'"
                      >English</template
                    >
                  </span>
                  <i class="text-lg fas fa-caret-down"></i>
                </div>
                <ul
                  class="absolute bottom-0 hidden w-auto py-2 mb-10 text-base font-semibold tracking-wide uppercase bg-white rounded shadow group-hover:block text-dark"
                >
                  <li v-if="data.multilanguage.enabled_nl">
                    <a
                      href="?lang=nl"
                      class="block px-5 py-2 transition duration-200 hover:bg-gray-100"
                    >
                      Nederlands
                    </a>
                  </li>
                  <li v-if="data.multilanguage.enabled_fr">
                    <a
                      href="?lang=fr"
                      class="block px-5 py-2 transition duration-200 hover:bg-gray-100"
                    >
                      Français
                    </a>
                  </li>
                  <li v-if="data.multilanguage.enabled_en">
                    <a
                      href="?lang=en"
                      class="block px-5 py-2 transition duration-200 hover:bg-gray-100"
                    >
                      English
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import Favorites from "./Favorites";

export default {
  props: ["component"],
  components: {
    Favorites,
  },
  data() {
    return {
      lang: window.currentLang,
      theme: window.theme,
      data: window[this.component],
      menuOpen: false,
      totalFavorites: window.totalFavorites,
    };
  },
  methods: {
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
      if (this.menuOpen) {
        document
          .querySelector("body")
          .classList.add("overflow-hidden", "h-screen");
      } else {
        document
          .querySelector("body")
          .classList.remove("overflow-hidden", "h-screen");
      }
    },
  },
  mounted() {
    window.addEventListener("favorite", (args) => {
      if (args.detail.favorited) {
        this.totalFavorites += 1;
      } else {
        this.totalFavorites -= 1;
      }
    });
  },
};
</script>
