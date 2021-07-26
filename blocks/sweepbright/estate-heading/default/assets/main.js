import $ from "jquery";

$(document).ready(() => {
  $("[data-slide-action='back']").on("click", () => {
    const e = new CustomEvent("estateSliderAction", {
      detail: {
        action: "back",
      },
      bubbles: true,
      cancelable: true,
      composed: false,
    });
    window.dispatchEvent(e);
  });

  $("[data-slide-action='next']").on("click", () => {
    const e = new CustomEvent("estateSliderAction", {
      detail: {
        action: "next",
      },
      bubbles: true,
      cancelable: true,
      composed: false,
    });
    window.dispatchEvent(e);
  });
});
