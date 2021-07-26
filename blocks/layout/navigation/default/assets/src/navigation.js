import $ from "jquery";
import Vue from "vue";

import List from "../components/Navigation.vue";

const navigation = () => {
  $(".navigation-default").each((index, el) => {
    Vue.config.productionTip = false;
    Vue.config.devtools = false;

    new Vue({
      el: `.${el.className}`,
      render: (h) => h(List, {
        props: {
          component: $(el).data("component"),
        },
      }),
    });
  });
};

export default navigation;
