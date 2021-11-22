import $ from "jquery";
import Sharer from "sharer.npm.js";

if (document.querySelector("[data-sharer]")) {
  $("[data-sharer]").on("click", (e) => {
    e.preventDefault();
    const sharer = new Sharer(e.currentTarget);
    sharer.share();
  });
}
