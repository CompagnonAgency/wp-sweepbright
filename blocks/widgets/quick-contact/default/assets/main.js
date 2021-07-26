import $ from "jquery";

import Vue from "vue";
import QuickContact from "./components/QuickContact.vue";

$(".quick-contact-default").each((index, el) => {
  Vue.config.productionTip = false;
  Vue.config.devtools = false;

  new Vue({
    el: `.${el.className}`,
    render: (h) => h(QuickContact, {
      props: {
        component: $(el).data("component"),
      },
    }),
  });
});
