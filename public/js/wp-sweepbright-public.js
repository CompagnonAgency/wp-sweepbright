// wp-sweepbright-public.js
import axios from 'axios';
import $ from 'jquery';
import Paginate from 'vuejs-paginate';

export default {
  install(Vue) {
    Vue.component('paginate', Paginate);

    Vue.mixin({
      computed: {
        activePage: {
          get() {
            return this.request.page;
          },
          set() {
          },
        },
      },
      data() {
        return {
          count: 0,
          countListener: 0,
          isLoading: false,
          estates: [],
          totalPages: 0,
          requestDefault: {},
          request: {
            page: 1,
            sort: {
              order: 'desc', // desc, asc
              orderBy: 'relevance', // relevance, price, date
            },
            recent: false,
            showAll: false,
            filters: {
              negotiation: false, // sale, let
              category: [], // see API docs `type`
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
              location: {
                lat: false,
                lng: false,
              },
            },
          },
        };
      },
      methods: {
        generateUUID() {
          let d = new Date().getTime();
          if (
            typeof performance !== 'undefined'
            && typeof performance.now === 'function'
          ) {
            d += performance.now();
          }
          return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
            const r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c === 'x' ? r : (r & 0x3) | 0x8).toString(16);
          });
        },
        generateUid(estates) {
          estates.forEach((estate) => {
            estate.uid = this.generateUUID();
          });
          return estates;
        },
        getEstates(params) {
          this.isLoading = true;

          axios.post('/wp-json/v1/sweepbright/list', this.request).then((response) => {
            if (response.data.estates && response.data.estates.length > 0) {
              this.estates = this.generateUid(response.data.estates);
            } else {
              this.estates = [];
            }
            this.totalPages = response.data.totalPages;
            const event = new Event('loadedEstates');
            window.dispatchEvent(event);
            this.isLoading = false;

            if (params && params.callback) {
              params.callback();
            }

            if (document.querySelector('[data-sweepbright-list]')) {
              $('html, body').animate({
                scrollTop: $('[data-sweepbright-list]').offset().top - 100,
              }, 200);
            }
          });
        },
        $sweepBrightPaginate(page) {
          this.request.page = page;
          window.location.hash = page;
        },
        $sweepBrightReset() {
          // Nodig bij switchen tussen map / list view
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
          if (params && params.page) {
            this.request.page = params.page;
            window.location.hash = params.page;
          }
          if (params && params.recent) {
            this.request.recent = params.recent;
          }
          if (params && params.showAll) {
            this.request.showAll = params.showAll;
          }
          if (params && params.sort) {
            this.request.sort = params.sort;
          }
          if (params && params.category) {
            this.request.filters.category = params.category;
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
              this.getEstates();
            }
          }
        },
        $eventListener() {
          this.countListener += 1;
          if (this.countListener === 1) {
            window.addEventListener('hashchange', () => {
              this.$loadEstates({
                sort: this.request.sort,
                filters: this.request.filters,
              });
            }, false);
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
