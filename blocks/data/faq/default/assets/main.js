import "./main.css";

import $ from "jquery";

$(".faq-default .question").on("click", (el) => {
  $(el.currentTarget).parent().find(".answer").slideToggle("fast");
  $(el.currentTarget).toggleClass("on");
  return false;
});

