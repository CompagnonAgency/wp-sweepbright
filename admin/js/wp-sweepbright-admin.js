import $ from 'jquery';

import '../css/style.css';

import properties from "./properties/properties.js";
import pages from "./pages/pages.js";

$(document).ready(() => {
	properties();
	pages();
});
