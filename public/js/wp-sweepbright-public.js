// wp-sweepbright-public.js
import axios from "axios";
import $ from "jquery";
import Paginate from "vuejs-paginate";

export default {
  install(Vue) {
    Vue.component("paginate", Paginate);

    Vue.mixin({
      computed: {
        activePage: {
          get() {
            return this.request.page;
          },
          set() {},
        },
      },
      data() {
        return {
          count: 0,
          countListener: 0,
          isLoading: false,
          estates: [],
          markers: [],
          totalPages: -1,
          totalPosts: 0,
          requestDefault: {},
          request: {
            page: 1,
            sort: {
              order: "desc", // desc, asc
              orderBy: "relevance", // relevance, price, date
            },
            recent: false,
            showAll: false,
            filters: {
              status: false, // available, sold, option, under_contract, rented
              new_home: false, // new, used
              negotiation: false, // sale, let, projects, sale_non_projects
              category: [], // see API docs `type`
              subcategory: [], // see API docs `type`,
              agent: false,
              office: false,
              facilities: {
                bedrooms: {
                  min: false,
                  max: false,
                },
              },
              price: {
                min: false,
                max: false,
              },
              plot_area: {
                min: false,
                max: false,
              },
              liveable_area: {
                min: false,
                max: false,
              },
              locations: [],
              location: {
                region: "",
                lat: false,
                lng: false,
                distance: false, // false (= default setting) or <int> in kilometers e.g. 5, 10, 15, ...
              },
            },
          },
        };
      },
      methods: {
        $getNumberFormat(locale) {
          let format = "DOT";

          if (locale === "en") {
            format = "COMMA";
          }
          return format;
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
            default:
              break;
          }
          return price;
        },
        $urlParam() {
          let vars = {};
          let url = window.location.href;

          if (url.indexOf("#") > -1) {
            url = window.location.href.substr(
              0,
              window.location.href.lastIndexOf("#")
            );
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

          // Negotiation
          if (negotiation) {
            this.request.filters["negotiation"] = negotiation;
          }

          // Location
          if (lat && lng && region) {
            this.request.filters["location"]["region"] = region;
            this.request.filters["location"]["lat"] = lat;
            this.request.filters["location"]["lng"] = lng;
          }

          // Locations
          if (this.$urlParam().locations) {
            const locations = this.$urlParam().locations
              ? decodeURIComponent(
                  this.$urlParam().locations.replace(/\+/g, "%20")
                )
                  .split("[")
                  .slice(1)
              : false;

            if (locations) {
              locations.forEach((item) => {
                const location = item.slice(0, -1).split(",");

                if (location.length === 3) {
                  location[1] = parseFloat(location[1]);
                  location[2] = parseFloat(location[2]);
                } else {
                  location[1] = false;
                  location[2] = false;
                }

                const locationItem = {
                  value: location[0],
                };

                if (location[1] && location[2]) {
                  locationItem.latLng = {
                    lat: location[1],
                    lng: location[2],
                  };
                }

                this.request.filters["locations"].push(locationItem);
              });
            }
          }

          // Price
          const price = this.$urlParam().price
            ? decodeURIComponent(this.$urlParam().price).split(",")
            : false;
          if (this.$urlParam().price && price && price.length === 2) {
            price[1] = price[1] === "false" ? false : parseInt(price[1]);
            this.request.filters["price"] = {
              min: parseInt(price[0]),
              max: price[1],
            };
          }

          // Plot area
          const plot_area = this.$urlParam().plot_area
            ? decodeURIComponent(this.$urlParam().plot_area).split(",")
            : false;
          if (
            this.$urlParam().plot_area &&
            plot_area &&
            plot_area.length === 2
          ) {
            plot_area[1] =
              plot_area[1] === "false" ? false : parseFloat(plot_area[1]);
            this.request.filters["plot_area"] = {
              min: parseFloat(plot_area[0]),
              max: plot_area[1],
            };
          }

          // Liveable area
          const liveable_area = this.$urlParam().liveable_area
            ? decodeURIComponent(this.$urlParam().liveable_area).split(",")
            : false;
          if (
            this.$urlParam().liveable_area &&
            liveable_area &&
            liveable_area.length === 2
          ) {
            liveable_area[1] =
              liveable_area[1] === "false"
                ? false
                : parseFloat(liveable_area[1]);
            this.request.filters["liveable_area"] = {
              min: parseFloat(liveable_area[0]),
              max: liveable_area[1],
            };
          }

          // Sort
          if (this.$urlParam().sort) {
            const sort = this.$urlParam().sort
              ? decodeURIComponent(this.$urlParam().sort).split(",")
              : false;
            this.request.sort.order = sort[1];
            this.request.sort.orderBy = sort[0];
          }

          // Category
          if (this.$urlParam().category) {
            this.request.filters.category = [this.$urlParam().category];
          }

          // Subcategory
          if (this.$urlParam().subcategory) {
            this.request.filters.subcategory = [this.$urlParam().subcategory];
          }

          // Pagination
          let page = 1;
          if (window.location.hash) {
            page = parseInt(window.location.hash.substr(1), 10);
          }
          this.$search({
            page,
          });
          window.requestFromUrl = this.request;
          this.$forceUpdate();
        },
        $search(params) {
          let page = 1;

          if (params && params.page) {
            page = params.page;
          }

          let args = {
            page,
            sort: this.request.sort,
            filters: this.request.filters,
          };

          if (params && params.mapMode) {
            args.mapMode = true;
            this.request.mapMode = true;
          } else {
            this.request.mapMode = false;
          }
          this.$sweepBrightInit(args);
        },
        $list() {
          this.$urlQuery();
        },
        generateUUID() {
          let d = new Date().getTime();
          if (
            typeof performance !== "undefined" &&
            typeof performance.now === "function"
          ) {
            d += performance.now();
          }
          return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
            /[xy]/g,
            (c) => {
              const r = (d + Math.random() * 16) % 16 | 0;
              d = Math.floor(d / 16);
              return (c === "x" ? r : (r & 0x3) | 0x8).toString(16);
            }
          );
        },
        generateUid(estates) {
          estates.forEach((estate) => {
            estate.uid = this.generateUUID();
          });
          return estates;
        },
        storeEstates(params, response) {
          if (response.data.estates && response.data.estates.length > 0) {
            this.estates = this.generateUid(response.data.estates);
          } else {
            this.estates = [];
          }
          this.totalPages = response.data.totalPages;
          this.totalPosts = response.data.totalPosts;

          const event = new Event("loadedEstates");
          window.dispatchEvent(event);
          this.isLoading = false;

          if (params && params.callback) {
            params.callback();
          }
          if (window.location.hash) {
            if (document.querySelector("[data-sweepbright-list]")) {
              $("html, body").animate(
                {
                  scrollTop: $("[data-sweepbright-list]").offset().top - 100,
                },
                200
              );

              $("[data-sweepbright-list]").animate(
                {
                  scrollTop: 0,
                },
                200
              );
            }
          }
        },
        storeMarkers(response) {
          if (response.data.estates && response.data.estates.length > 0) {
            this.markers = this.generateUid(response.data.estates);
          } else {
            this.markers = [];
          }
          this.totalPages = response.data.totalPages;
          this.totalPosts = response.data.totalPosts;

          const event = new Event("loadedMap");
          window.dispatchEvent(event);
        },
        getEstates(params) {
          this.isLoading = true;
          axios
            .post("/wp-json/v1/sweepbright/list", this.request)
            .then((response) => {
              if (params && params.mapMode) {
                this.storeMarkers(response);
              } else {
                this.storeEstates(params, response);
              }
            });
        },
        $sweepBrightPaginate(page) {
          this.request.page = page;
          window.location.hash = page;
        },
        $sweepBrightReset() {
          this.count += 1;
          if (this.count === 1) {
            this.requestDefault = Object.assign({}, this.request);
          }
          this.request = Object.assign({}, this.requestDefault);
        },
        $loadEstates(params, callback) {
          this.$sweepBrightReset();
          if (window.location.hash) {
            this.request.page = parseInt(window.location.hash.substr(1), 10);
          }
          if (params && params.recent) {
            this.request.recent = params.recent;
          }
          if (params && params.showAll) {
            this.request.showAll = params.showAll;
          }
          if (params && params.mapMode) {
            this.request.mapMode = params.mapMode;
          }
          if (params && params.sort) {
            this.request.sort = params.sort;
          }
          if (params && params.category) {
            this.request.filters.category = params.category;
          }
          if (params && params.subcategory) {
            this.request.filters.subcategory = params.subcategory;
          }
          if (params && params.filters) {
            this.request.filters = params.filters;
          }

          if (Number.isInteger(this.request.page)) {
            if (callback) {
              this.getEstates({
                callback,
              });
            } else {
              this.getEstates(params);
            }
          }
        },
        $eventListener() {
          this.countListener += 1;
          if (this.countListener === 1) {
            window.addEventListener(
              "hashchange",
              () => {
                this.$loadEstates({
                  sort: this.request.sort,
                  filters: this.request.filters,
                });
              },
              false
            );
          }
        },
        $sweepBrightInit(params, callback) {
          this.$eventListener();
          this.$loadEstates(params, callback);
        },
      },
    });
  },
};
