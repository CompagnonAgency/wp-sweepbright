import "./main.css";

import $ from "jquery";
import Glide from "@glidejs/glide";

$(".testimonials-default").each((index, el) => {
  new Glide(`[data-component=${$(el).data("component")}]`, {
    autoplay: false,
    animationDuration: 500,
    type: "carousel",
    gap: 20,
    perView: 1,
    hoverpause: false,
    dragThreshold: false,
    breakpoints: {
      1024: {
        dragThreshold: 120,
      },
    },
  }).mount();
});
