import $ from "jquery";

if (document.querySelector(".js-read-more")) {
  $(".js-read-more").on("click", (e) => {
    e.preventDefault();
    $(e.currentTarget).parents().find(".js-read-more-content").slideToggle();
    $(e.currentTarget).parents().find(".js-txt-read-less").toggle();
    $(e.currentTarget).parents().find(".js-txt-read-more").toggle();
  });
}
