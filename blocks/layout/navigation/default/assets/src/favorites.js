import $ from "jquery";
import Vue from "vue";

import Favorites from "../components/Favorites.vue";

const favorites = () => {
  $(".favorites-default").each((index, el) => {
    Vue.config.productionTip = false;
    Vue.config.devtools = false;

    new Vue({
      el: `.${el.className}`,
      render: (h) => h(Favorites, {
        props: {
          component: $(el).data("component"),
        },
      }),
    });
  });
};

export default favorites;
