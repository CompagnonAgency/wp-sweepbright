import $ from "jquery";

import Vue from "vue";
import Filter from "./components/Filter.vue";

import SweepBright from "../../../../../dist/wp-sweepbright-public";
import WPSweepBright from "../../../_shared/WPSweepBright";

Vue.use(SweepBright);
Vue.use(WPSweepBright);

$(".filter-default").each((index, el) => {
  Vue.config.productionTip = false;
  Vue.config.devtools = false;

  new Vue({
    el: `.${el.className}`,
    render: (h) => h(Filter, {
      props: {
        component: $(el).data("component"),
      },
    }),
  });
});
