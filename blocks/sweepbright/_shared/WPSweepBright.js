import EventBus from './bus.js';

export default {
  install(Vue) {
    Vue.mixin(EventBus)

    Vue.mixin({
      methods: {
        $cloneFilters() {
          this.requestCache = JSON.parse(JSON.stringify(this.request));
        },
        $clearFilters() {
          this.request = JSON.parse(JSON.stringify(this.requestCache));
          this.config = JSON.parse(JSON.stringify(this.configCache));

          const e = new CustomEvent('filterReset', {
            bubbles: true,
            cancelable: true,
            composed: false,
          });
          window.dispatchEvent(e);
          this.$applyFilter();

          this.filtered = false;
        },
        $applyFilter() {
          const e = new CustomEvent('filterChange', {
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
        $events() {          
          this.$bus.$on("toggleDropdown", (dropdown) => {
            this.config.dropdowns[dropdown].open = !this.config.dropdowns[dropdown]
              .open;

            for (const [key] of Object.entries(this.config.dropdowns)) {
              if (dropdown !== key) {
                this.$set(this.config.dropdowns[key], 'open', false);
              }
            }
          });

          // Set values
          this.$bus.$on("setDropdown", (active, dropdown) => {
            this.config.dropdowns[active].selected = dropdown.value;
            this.config.dropdowns[active].open = false;

            if (active === 'sort') {
              switch (dropdown.value) {
                case 'date_asc':
                  this.request.sort.order = 'asc';
                  this.request.sort.orderBy = 'date';
                  break;
                case 'date_desc':
                  this.request.sort.order = 'desc';
                  this.request.sort.orderBy = 'date';
                  break;
                case 'price_asc':
                  this.request.sort.order = 'asc';
                  this.request.sort.orderBy = 'price';
                  break;
                case 'price_desc':
                  this.request.sort.order = 'desc';
                  this.request.sort.orderBy = 'price';
                  break;
                default:
                  this.request.sort.order = 'desc';
                  this.request.sort.orderBy = 'relevance';
                  break;
              }
            } else {
              this.request.filters[active] = dropdown.value;
            }
            this.$applyFilter();
          });

          this.$bus.$on("setRange", (group, item, value) => {
            this.request.filters[item] = {
              min: value[0],
              max: value[1],
            };
            this.$applyFilter();
          });

          this.$bus.$on("setLocation", (location) => {
            if (location) {
              this.request.filters['location'] = location;
            } else {
              this.request.filters['location'].lat = false;
              this.request.filters['location'].lng = false;
            }
            this.$applyFilter();
          });

          this.$bus.$on("setLocations", (locations) => {
            if (locations) {
              this.request.filters['locations'] = locations;
            } else {
              this.request.filters['locations'] = [];
            }
            this.$applyFilter();
          });

          this.$bus.$on("searchFocus", (focus) => {
            this.config.location.focus = focus;
          });
        }
      },
    });
  },
};
