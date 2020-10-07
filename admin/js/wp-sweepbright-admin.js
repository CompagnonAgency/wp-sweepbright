import Vue from 'vue';
import VueRouter from 'vue-router';
import $ from 'jquery';
import '../css/style.css';
import Properties from '../vue-components/Properties.vue';

// Pages
import Overview from '../vue-components/Overview.vue';
import Edit from '../vue-components/Edit.vue';

Vue.use(require('vue-moment'));

// Devtools
Vue.config.productionTip = false;
Vue.config.devtools = false;

// Router
Vue.use(VueRouter);

const routes = [
	{
		path: '/',
		name: 'overview',
		component: Overview,
	},
	{
		path: '/edit/:id',
		name: 'edit',
		component: Edit,
	},
];

const router = new VueRouter({
	routes, // short for `routes: routes`
});

$(document).ready(() => {
	if (document.querySelector('#sweepbright-properties')) {
		new Vue({
			router,
			el: '#sweepbright-properties',
			render: (h) => h(Properties),
		});
	}
});
