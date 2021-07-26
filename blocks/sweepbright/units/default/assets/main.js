import $ from "jquery";

import Vue from "vue";
import vueNumeralFilterInstaller from "vue-numeral-filter";

import List from "./components/Units.vue";

$(".units-default").each((index, el) => {
  Vue.config.productionTip = false;
  Vue.config.devtools = false;

  Vue.use(vueNumeralFilterInstaller, {
    locale: "nl-nl",
  });

  new Vue({
    el: `.${el.className}`,
    render: (h) => h(List, {
      props: {
        component: $(el).data("component"),
      },
    }),
  });
});
