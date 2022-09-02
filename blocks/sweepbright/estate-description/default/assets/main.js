import $ from "jquery";

require("readmore-js");

const descriptionData = window[$(".js-description").data("component")];
const readMore = descriptionData.locale[descriptionData.lang].read_more;
const readLess = descriptionData.locale[descriptionData.lang].read_less;

$(".js-description").readmore({
  speed: 75,
  collapsedHeight: 200,
  moreLink: `<a href="#" class="inline-block mt-4 font-semibold text-base lowercase">${readMore}</a>`,
  lessLink: `<a href="#" class="inline-block mt-4 font-semibold text-base lowercase">${readLess}</a>`,
});
