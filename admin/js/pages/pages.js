
import Vue from 'vue';
import VueRouter from 'vue-router';
import Pages from '../../vue-components/Pages/Pages.vue';

// Modules
import VTooltip from 'v-tooltip';
import VuejsDialog from 'vuejs-dialog';
import VueToast from 'vue-toast-notification';
// import 'vue-toast-notification/dist/theme-default.css';
import 'vue-toast-notification/dist/theme-sugar.css';

// Tell Vue to install the plugin.
Vue.use(VuejsDialog);
Vue.use(VueToast);

// Renderer
import Renderer from '../../vue-components/Pages/Components/Editor/Fields/Renderer.vue';
Vue.component('Renderer', Renderer)

// Pages
import Overview from '../../vue-components/Pages/Overview.vue';
import Settings from '../../vue-components/Pages/Settings.vue';
import Theme from '../../vue-components/Pages/Theme.vue';
import Editor from '../../vue-components/Pages/Components/Editor.vue';

const pages = () => {
  if (document.querySelector('#sweepbright-pages')) {
    // Devtools
    Vue.config.productionTip = false;
    Vue.config.devtools = false;

    // Modules
    Vue.use(VTooltip);

    // Router
    Vue.use(VueRouter);

    const routes = [
      {
        path: '/',
        name: 'home',
        component: Overview,
      },
      {
        path: '/settings',
        name: 'settings',
        component: Settings,
      },
      {
        path: '/theme',
        name: 'theme',
        component: Theme,
      },
      {
        path: '/editor/:id',
        name: 'editor',
        component: Editor,
      },
    ];

    const router = new VueRouter({
      routes, // short for `routes: routes`
    });

    new Vue({
      router,
      el: '#sweepbright-pages',
      render: (h) => h(Pages),
    });
  }
};

export default pages;
