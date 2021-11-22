<template>
  <a :href="data.favorites.link" class="relative z-10 inline-block" data-favorites>
    <div class="absolute right-0 items-center justify-center w-5 h-5 -mt-1 -mr-4 text-sm font-medium text-white bg-red-500 rounded-full" data-favorites-total
      :class="totalFavorites > 0 ? 'flex' : 'hidden'">
      <span>
        {{totalFavorites}}
      </span>
    </div>
    <i data-favorites-icon class="text-xl fa-heart"
      :class="totalFavorites > 0 ? 'fas' : 'fal'"></i>
  </a>
</template>

<script>
export default {
  props: ["component"],
  components: {},
  data() {
    return {
      lang: window.currentLang,
      theme: window.theme,
      data: window[this.component],
      totalFavorites: 0,
    };
  },
  methods: {
    getCookie(cname) {
      const name = `${cname}=`;
      const ca = document.cookie.split(";");
      for (let i = 0; i < ca.length; i += 1) {
        let c = ca[i];
        while (c.charAt(0) === " ") {
          c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    },
  },
  mounted() {
    // Get total favorites
    const cookie = decodeURIComponent(this.getCookie("favorites"));

    if (cookie) {
      const favorites = [...new Set(JSON.parse(cookie))];
      this.totalFavorites = favorites.length;
    }

    // Add & remove favorites
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
