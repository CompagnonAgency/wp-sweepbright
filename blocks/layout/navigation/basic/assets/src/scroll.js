import $ from "jquery";

const scroll = () => {
  if (document.querySelector(".js-navigation")) {
    // Fixed navigation
    let init = 0;

    const detachMenu = () => {
      document.querySelector(".js-navigation").classList.add("is-detached");
      document.querySelector(".js-navigation").classList.remove("is-attached");
    };

    const attachMenu = () => {
      document.querySelector(".js-navigation").classList.add("is-attached");
      document.querySelector(".js-navigation").classList.remove("is-detached");
      if (init < 21) {
        init += 1;
      }

      if (init === 20) {
        document.querySelector(".js-navigation").classList.add("is-animated");
      }
    };

    const hideMenu = () => {
      document.querySelector(".js-navigation").classList.add("is-hidden");
    };

    const showMenu = () => {
      document.querySelector(".js-navigation").classList.remove("is-hidden");
    };

    if (document.querySelector(".js-navigation")) {
      let lastScroll = 0;
      const offset = 70;

      const fixedScroll = () => {
        const currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollPos = window.scrollY;
        if (scrollPos > 0) {
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
          attachMenu();
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
