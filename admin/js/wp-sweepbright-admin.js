import Vue from 'vue';
import VueRouter from 'vue-router';
import $ from 'jquery';
import '../css/style.css';
import wysiwyg from 'vue-wysiwyg';
import Properties from '../vue-components/Properties.vue';

// Pages
import Overview from '../vue-components/Overview.vue';
import Edit from '../vue-components/Edit.vue';
import Units from '../vue-components/Units.vue';

Vue.use(require('vue-moment'));

Vue.use(wysiwyg, {
	hideModules: {
		bold: false,
		italic: false,
		underline: false,
		justifyLeft: true,
		justifyCenter: true,
		justifyRight: true,
		headings: true,
		link: true,
		code: true,
		orderedList: true,
		unorderedList: true,
		image: true,
		table: true,
		removeFormat: false,
		separator: true,
	},
	maxHeight: false,
	forcePlainTextOnPaste: true,
});

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
		path: '/:id',
		name: 'overview',
		component: Overview,
	},
	{
		path: '/edit/:id',
		name: 'edit',
		component: Edit,
	},
	{
		path: '/edit/:id/units',
		name: 'units',
		component: Units,
	},
	{
		path: '/edit/:id/units/:page',
		name: 'units',
		component: Units,
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
