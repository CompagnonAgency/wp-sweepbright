import $ from "jquery";

require("readmore-js");

$(".js-description").readmore({
  speed: 75,
  collapsedHeight: 200,
  moreLink: "<a href=\"#\" class=\"inline-block mt-4 font-semibold text-base lowercase\">Read more</a>",
  lessLink: "<a href=\"#\" class=\"inline-block mt-4 font-semibold text-base lowercase\">Read less</a>",
});
