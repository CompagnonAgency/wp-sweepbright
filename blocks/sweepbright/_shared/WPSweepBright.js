export default {
  install(Vue) {
    const bus = new Vue();
    const busMixin = {
      beforeCreate() {
        this.$bus = bus;
      },
    };

    Vue.mixin(busMixin);

    Vue.mixin({
      methods: {
        $cloneFilters() {
          this.requestCache = JSON.parse(JSON.stringify(this.request));
        },
        $clearFilters() {
          this.request = JSON.parse(JSON.stringify(this.requestCache));

          const e = new CustomEvent("filterReset", {
            bubbles: true,
            cancelable: true,
            composed: false,
          });
          window.dispatchEvent(e);
          this.$applyFilter();

          this.filtered = false;
        },
        $applyFilter() {
          const e = new CustomEvent("filterChange", {
            detail: {
              request: this.request,
              config: this.config,
            },
            bubbles: true,
            cancelable: true,
            composed: false,
          });
          window.dispatchEvent(e);
        },
        $updateFilter(field) {
          const e = new CustomEvent("filterUpdate", {
            detail: {
              field,
            },
            bubbles: true,
            cancelable: true,
            composed: false,
          });
          window.dispatchEvent(e);
        },
        $syncRequest() {
          if (window.requestFromUrl) {
            this.request = window.requestFromUrl;
          }
        },
        $events() {
          this.$bus.$on("toggleDropdown", (dropdown) => {
            this.config.dropdowns[dropdown].open =
              !this.config.dropdowns[dropdown].open;

            for (const [key] of Object.entries(this.config.dropdowns)) {
              if (dropdown !== key) {
                this.$set(this.config.dropdowns[key], "open", false);
              }
            }
          });

          this.$bus.$on("searchFocus", (focus) => {
            this.config.location.focus = focus;
          });

          // Set values
          this.$bus.$on("setDropdown", (active, dropdown) => {
            this.$syncRequest();

            this.config.dropdowns[active].selected = dropdown.value;
            this.config.dropdowns[active].open = false;

            if (active === "sort") {
              switch (dropdown.value) {
                case "date_asc":
                  this.request.sort.order = "asc";
                  this.request.sort.orderBy = "date";
                  break;
                case "date_desc":
                  this.request.sort.order = "desc";
                  this.request.sort.orderBy = "date";
                  break;
                case "price_asc":
                  this.request.sort.order = "asc";
                  this.request.sort.orderBy = "price";
                  break;
                case "price_desc":
                  this.request.sort.order = "desc";
                  this.request.sort.orderBy = "price";
                  break;
                default:
                  this.request.sort.order = "desc";
                  this.request.sort.orderBy = "relevance";
                  break;
              }

              this.$updateFilter({
                name: active,
                type: "sort",
                value: {
                  order: this.request.sort.order,
                  orderBy: this.request.sort.orderBy,
                },
              });
            } else {
              this.request.filters[active] = dropdown.value;

              let type = "string";

              if (typeof dropdown.value === "object") {
                type = "array";
              }

              this.$updateFilter({
                name: active,
                type,
                value: dropdown.value,
              });
            }
            this.$applyFilter();
          });

          this.$bus.$on("setRange", (group, item, value) => {
            this.$syncRequest();

            this.request.filters[item] = {
              min: value[0],
              max: value[1],
            };
            this.$updateFilter({
              name: item,
              type: "range",
              value: value,
            });
            this.$applyFilter();
          });

          this.$bus.$on("setLocation", (location) => {
            this.$syncRequest();

            if (location) {
              this.request.filters["location"] = location;
            } else {
              this.request.filters["location"].lat = false;
              this.request.filters["location"].lng = false;
            }

            this.$updateFilter({
              name: "location",
              type: "location",
              value: location,
            });
            this.$applyFilter();
          });

          this.$bus.$on("setLocations", (locations) => {
            this.$syncRequest();

            if (locations) {
              this.request.filters["locations"] = locations;
            } else {
              this.request.filters["locations"] = [];
            }

            this.$updateFilter({
              name: "locations",
              type: "locations",
              value: locations,
            });
            this.$applyFilter();
          });
        },
      },
    });
  },
};
