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
          isLoading: false,
          estates: [],
          totalPages: 0,
          request: {
            page: 1,
            sort: {
              order: 'desc', // desc, asc
              orderBy: 'relevance', // relevance, price, date
            },
            recent: false,
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
            }
            this.totalPages = response.data.totalPages;
            const event = new Event('loadedEstates');
            window.dispatchEvent(event);
            this.isLoading = false;

            if (params && params.scrollTop) {
              $('html, body').animate({
                scrollTop: $('[data-sweepbright-list]').offset().top - 100,
              }, 200);
            }
          });
        },
        $sweepBrightPaginate(page) {
          this.request.page = page;
          window.location.hash = page;
          this.getEstates({
            scrollTop: true,
          });
        },
        $sweepBrightInit(params) {
          if (window.location.hash) {
            this.request.page = parseInt(window.location.hash.substr(1), 10);
          }
          if (params && params.recent) {
            this.request.recent = params.recent;
          }
          if (params && params.category) {
            this.request.filters.category = params.category;
          }
          this.getEstates();
        },
      },
    });
  },
};
