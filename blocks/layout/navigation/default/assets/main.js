import $ from "jquery";

if (document.querySelector(".js-nav-space")) {
  if (document.querySelector("[data-banner]")) {
    document.querySelector(".js-nav-space").classList.add("-mt-32");
  } else {
    document.querySelector(".js-nav-space").classList.add("-mt-40");
  }
  document.querySelector(".js-nav-space").classList.add("h-screen");
}

if (document.querySelector(".js-navigation")) {
  if (document.querySelector("[data-banner]")) {
    document.querySelector("body").classList.add("pt-32");
  } else {
    document.querySelector("body").classList.add("pt-40");
  }

  const fix = () => {
    if (document.querySelector("[data-banner]")) {
      document.querySelector(".js-navigation").classList.add("text-black");
      document.querySelector(".js-navigation").classList.remove("text-shadow");
      document.querySelector(".js-navigation").classList.remove("text-white");
    }

    document.querySelector(".js-navigation").classList.add("bg-white");
    document.querySelector(".js-navigation").classList.add("shadow-md");

    document.querySelector(".js-navigation-logo").classList.add("m-3");
    document.querySelector(".js-navigation-logo").classList.remove("m-10");

    document.querySelector(".js-navigation-logo").classList.add("max-h-10");
    document.querySelector(".js-navigation-logo").classList.remove("max-h-12");
  };

  const unfix = () => {
    if (document.querySelector("[data-banner]")) {
      document.querySelector(".js-navigation").classList.add("text-white");
      document.querySelector(".js-navigation").classList.add("text-shadow");
      document.querySelector(".js-navigation").classList.remove("text-black");
    }
    document.querySelector(".js-navigation").classList.remove("bg-white");
    document.querySelector(".js-navigation").classList.remove("shadow-md");

    document.querySelector(".js-navigation-logo").classList.remove("m-3");
    document.querySelector(".js-navigation-logo").classList.add("m-10");

    document.querySelector(".js-navigation-logo").classList.remove("max-h-10");
    document.querySelector(".js-navigation-logo").classList.add("max-h-12");
  };

  // Fixed scroll
  const fixedScroll = () => {
    const scrollPos = window.scrollY;

    if (scrollPos > 0) {
      fix();
    } else {
      unfix();
    }
  };

  // Events
  $(document).on("scroll", () => {
    fixedScroll();
  });

  $(document).on("resize", () => {
    fixedScroll();
  });

  fixedScroll();
}
