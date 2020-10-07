// wp-sweepbright-public.js
import axios from 'axios';
import $ from 'jquery';
import Vue from 'vue';
import Paginate from 'vuejs-paginate';

export default {
  install(Vue) {
    Vue.component('paginate', Paginate);

    Vue.mixin({
      computed: {
        activePage: {
          get() {
            return this.currentPage + 1;
          },
          set() {
          },
        },
      },
      data() {
        return {
          isLoading: false,
          estates: [],
          estatesFiltered: [],
          currentPage: 0,
          totalPages: 0,
          postsPerPage: window.sweepbrightPostsPerPage,
          geoDistance: window.sweepbrightGeoDistance,
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
        setTotalPages() {
          this.totalPages = Math.ceil(this.estates.length / this.postsPerPage);
        },
        filterQuery() {
          this.estates = this.estatesFiltered;

          // @TODO: Filters & sorting | use params e.g. { filter: [ date: true, ... ] }
          this.setTotalPages();
        },
        navigate() {
          this.isLoading = true;
          this.filterQuery();

          const paged = this.estates.slice(
            this.currentPage * this.postsPerPage,
            (this.currentPage * this.postsPerPage) + this.postsPerPage,
          );

          this.estates = this.generateUid(paged);

          $('html, body').animate({
            scrollTop: 0,
          }, 200, () => {
            this.isLoading = false;
          });
        },
        checkHash() {
          if (window.location.hash) {
            this.currentPage = parseInt(window.location.hash.substr(1), 10) - 1;
          } else {
            this.currentPage = 0;
          }
          this.navigate();
        },
        $sweepBrightPaginate(page) {
          this.currentPage = page - 1;
          window.location.hash = page;
        },
        $sweepBrightList() {
          this.isLoading = true;
          axios.get('/wp-json/v1/sweepbright/list').then((response) => {
            this.estatesFiltered = this.generateUid(response.data.data);
            this.estates = this.generateUid(response.data.data);
            this.setTotalPages();
            this.checkHash();
            const event = new Event('loadedEstates');
            window.dispatchEvent(event);
            this.isLoading = false;
          });
          window.addEventListener('hashchange', this.checkHash, false);
        },
      },
    });
  },
};
