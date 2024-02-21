import "./main.css";

import $ from "jquery";
import Glide from "@glidejs/glide";

$(".slider-default").each((index, el) => {
  const data = window[$(el).data("component")];

  let autoplay;
  let gap;
  let perView;

  switch (data.autoplay) {
    case "true":
      autoplay = 4000;
      break;
    default:
      autoplay = false;
      break;
  }

  switch (data.gap) {
    case "large":
      gap = 60;
      break;
    case "medium":
      gap = 30;
      break;
    case "none":
      gap = 0;
      break;
    default:
      gap = 10;
      break;
  }

  switch (data.slides_per_view) {
    case "1":
      perView = 1;
      break;
    case "2":
      perView = 2;
      break;
    case "3":
      perView = 3;
      break;
    case "4":
      perView = 4;
      break;
    case "5":
      perView = 5;
      break;
    case "6":
      perView = 6;
      break;
    default:
      perView = 2;
      break;
  }

  new Glide(`[data-component=${$(el).data("component")}]`, {
    autoplay,
    animationDuration: 500,
    type: "carousel",
    gap,
    perView,
    hoverpause: false,
    dragThreshold: false,
    breakpoints: {
      1024: {
        perView: 2,
        dragThreshold: 120,
      },
    },
  }).mount();
});
