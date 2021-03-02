import $ from "jquery";
import Vue from "vue";
import List from "./components/List.vue";

import SweepBright from "../../../../../dist/wp-sweepbright-public";
import WPSweepBright from "../../../_shared/WPSweepBright";

Vue.use(SweepBright);
Vue.use(WPSweepBright);

Vue.config.productionTip = false;
Vue.config.devtools = false;

$(".list-default").each((index, el) => {
  new Vue({
    el: `.${el.className}`,
    render: (h) => h(List, {
      props: {
        component: $(el).data("component"),
      },
    }),
  });
});
