import $ from "jquery";

const scroll = () => {
  if (document.querySelector(".js-navigation")) {
    // Estate pages have a different navigation
    const id = document.querySelector(".js-navigation").dataset.component;
    const component = window[id];

    // Fixed navigation
    let init = 0;

    const fixBanner = () => {
      if (document.querySelector("[data-banner]")) {
        document.querySelector(".js-navigation").classList.add("text-black");
        document.querySelector(".js-navigation").classList.remove("text-shadow");
        document.querySelector(".js-navigation").classList.remove("text-white");
      }
    };

    const unfixBanner = () => {
      if (document.querySelector("[data-banner]")) {
        document.querySelector(".js-navigation").classList.add("text-white");
        document.querySelector(".js-navigation").classList.add("text-shadow");
        document.querySelector(".js-navigation").classList.remove("text-black");
      }
    };

    const showSmallLogo = () => {
      if (component.is_front_page) {
        document.querySelector(".js-navigation-logo-large").classList.remove("is-visible");
        document.querySelector(".js-navigation-logo-large").classList.add("is-hidden");

        document.querySelector(".js-navigation-logo-small").classList.remove("is-hidden");
        document.querySelector(".js-navigation-logo-small").classList.add("is-visible");
      }
    };

    const showLargeLogo = (isMidScroll) => {
      if (document.querySelector(".js-navigation-logo-large") && ((init > 1 && component.is_front_page) || isMidScroll)) {
        document.querySelector(".js-navigation-logo-large").classList.remove("is-hidden");
        document.querySelector(".js-navigation-logo-large").classList.add("is-visible");

        document.querySelector(".js-navigation-logo-small").classList.remove("is-visible");
        document.querySelector(".js-navigation-logo-small").classList.add("is-hidden");
      }
    };

    const showEstateMenu = () => {
      if (component.estate_id) {
        document.querySelector(".js-navigation-links").classList.add("-translate-y-full");
        document.querySelector(".js-navigation-estate").classList.remove("-translate-y-full");
      }
    };

    const hideEstateMenu = () => {
      if (component.estate_id) {
        document.querySelector(".js-navigation-links").classList.remove("-translate-y-full");
        document.querySelector(".js-navigation-estate").classList.add("-translate-y-full");
      }
    };

    const detachMenu = () => {
      document.querySelector(".js-navigation").classList.add("is-detached");
      document.querySelector(".js-navigation").classList.remove("is-attached");
      fixBanner();
    };

    const attachMenu = (isMidScroll) => {
      document.querySelector(".js-navigation").classList.add("is-attached");
      document.querySelector(".js-navigation").classList.remove("is-detached");
      if (init < 21) {
        init += 1;
      }

      if (init === 20) {
        document.querySelector(".js-navigation").classList.add("is-animated");
      }
      unfixBanner();
      showLargeLogo(isMidScroll);
      hideEstateMenu();
    };

    const hideMenu = () => {
      document.querySelector(".js-navigation").classList.add("is-hidden");
      showSmallLogo();
      showEstateMenu();
    };

    const showMenu = () => {
      document.querySelector(".js-navigation").classList.remove("is-hidden");
    };

    if (document.querySelector(".js-navigation")) {
      let lastScroll = 0;
      const offset = 70;
      let isMidScroll = false;

      const fixedScroll = () => {
        const currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollPos = window.scrollY;
        if (scrollPos > 0) {
          isMidScroll = true;

          if (currentScroll > 0 && currentScroll < offset) {
            // Down
            lastScroll = currentScroll;
            detachMenu();
          } else if (currentScroll > offset && lastScroll <= currentScroll) {
            // Down
            lastScroll = currentScroll;
            hideMenu();
          } else {
            // Up
            lastScroll = currentScroll;
            showMenu();
            detachMenu();
          }
        } else {
          lastScroll = 0;
          showMenu();
          attachMenu(isMidScroll);
        }
      };
      fixedScroll();

      $(document).on("scroll", () => {
        fixedScroll();
      });

      $(document).on("resize", () => {
        fixedScroll();
      });
    }
  }
};

export default scroll;
