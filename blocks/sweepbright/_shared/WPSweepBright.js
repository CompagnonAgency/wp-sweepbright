import EventBus from './bus.js'

export default {
  install(Vue) {
    Vue.mixin(EventBus)

    Vue.mixin({
      computed: {
        price() {
          let symbol = "";
          let amount = 0;
          switch (this.estate.meta.price.currency) {
            case "EUR":
              symbol = "€";
              amount = this.$formatNumber(this.estate.meta.price.amount, "DOT");
              break;
            case "GBP":
              symbol = "£";
              amount = this.$formatNumber(this.estate.meta.price.amount, "COMMA");
            case "USD":
              symbol = "$";
              amount = this.$formatNumber(this.estate.meta.price.amount, "COMMA");
              break;
          }
          return `${symbol} ${amount}`;
        },
      },
      methods: {
        $urlParam() {
          let vars = {};
          let url = window.location.href;

          if (url.indexOf('#') > -1) {
            url = window.location.href.substr(0, window.location.href.lastIndexOf("#"));
          }

          url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
          });
          return vars;
        },
        $urlQuery() {
          const negotiation = this.$urlParam()["negotiation"];
          const region = this.$urlParam()["region"];
          const lat = parseFloat(this.$urlParam()["lat"]);
          const lng = parseFloat(this.$urlParam()["lng"]);

          if (negotiation) {
            this.config.dropdowns.negotiation.selected = negotiation;
            this.request.filters['negotiation'] = negotiation;
          }

          if (lat && lng && region) {
            this.config.location.search = region;
            this.request.filters['location'] = {
              lat,
              lng,
            };
          }
          this.$search();
          this.$forceUpdate();
        },
        $list() {
          this.$urlQuery();
        },
        $search() {
          this.config.isLoading = true;
          this.$sweepBrightInit({
            page: 1,
            sort: this.request.sort,
            filters: this.request.fiters,
          });
        },
        $events() {
          this.$bus.$on("toggleDropdown", (dropdown) => {
            this.$bus.$emit("hideDropdowns", dropdown);

            this.config.dropdowns[dropdown].open = !this.config.dropdowns[dropdown]
              .open;
          });

          this.$bus.$on("hideDropdowns", (dropdown) => {
            for (const [key] of Object.entries(this.config.dropdowns)) {
              if (dropdown) {
                if (dropdown !== key) {
                  this.config.dropdowns[key].open = false;
                }
              } else {
                this.config.dropdowns[key].open = false;
              }
            }
          });

          this.$bus.$on("setDropdown", (active, dropdown) => {
            this.config.dropdowns[active].selected = dropdown.value;
            this.request.filters[active] = dropdown.value;
            this.$search();
          });

          this.$bus.$on("setRange", (group, item, value) => {
            this.request.filters[item] = {
              min: value[0],
              max: value[1],
            };
            this.$search();
          });

          this.$bus.$on("setLocation", (location) => {
            this.request.filters['location'] = location;
            this.$search();
          });

          this.$bus.$on("setMode", (mode) => {
            this.config.mode = mode;
            this.$search();
          });

          this.$bus.$on("searchFocus", (focus) => {
            this.config.location.focus = focus;
          });
        },
        $formatNumber(price, format) {
          switch (format) {
            case "DOT":
              price = price
                .toString()
                .replace(/\./g, ",")
                .toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              break;
            case "COMMA":
              price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              break;
          }
          return price;
        },
        $getNumberFormat(lang) {
          let format = "";
          switch (lang) {
            case "nl":
              format = "DOT";
              break;
            case "fr":
              format = "COMMA";
              break;
            case "en":
              format = "COMMA";
              break;
            default:
              format = "COMMA";
              break;
          }
          return format;
        }
      },
    });
  },
};
