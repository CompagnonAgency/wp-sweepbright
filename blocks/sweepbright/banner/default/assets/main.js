import $ from "jquery";
import Vue from "vue";
import Search from "./components/Search.vue";

Vue.config.productionTip = false;
Vue.config.devtools = false;

$(".banner-default-search").each((index, el) => {
  new Vue({
    el: `.${el.className}`,
    render: (h) => h(Search, {
      props: {
        component: $(el).data("component"),
      },
    }),
  });
});
