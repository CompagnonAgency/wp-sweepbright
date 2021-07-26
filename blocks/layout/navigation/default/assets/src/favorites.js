const favorites = () => {
  if (document.querySelector("[data-favorites]")) {
    window.addEventListener("favorite", (args) => {
      if (args.detail.favorited) {
        window.totalFavorites += 1;
      } else {
        window.totalFavorites -= 1;
      }
      if (window.totalFavorites > 0) {
        document.querySelector("[data-favorites-total]").classList.add("flex");
        document.querySelector("[data-favorites-total]").classList.remove("hidden");

        document.querySelector("[data-favorites-icon]").classList.remove("fal");
        document.querySelector("[data-favorites-icon]").classList.add("fas");
      } else {
        document.querySelector("[data-favorites-total]").classList.remove("flex");
        document.querySelector("[data-favorites-total]").classList.add("hidden");

        document.querySelector("[data-favorites-icon]").classList.add("fal");
        document.querySelector("[data-favorites-icon]").classList.remove("fas");
      }
      document.querySelector("[data-favorites-total] span").innerHTML = window.totalFavorites;
    });
  }
};

export default favorites;
