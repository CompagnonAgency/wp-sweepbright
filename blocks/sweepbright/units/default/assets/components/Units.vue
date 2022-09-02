<template>
  <div>
    <div class="mb-8 post">
      <h2>{{ data.locale[lang].heading }}</h2>
    </div>
    <vue-good-table
      @on-row-click="openUnit"
      styleClass="vgt-table"
      :columns="columns"
      :rows="rows"
      :row-style-class="disableRow"
      :sort-options="{
        enabled: true,
        initialSortBy: [{ field: 'title', type: 'asc' }],
      }"
      :pagination-options="{
        enabled: true,
        mode: 'pages',
        perPage: parseInt(posts_per_page),
        position: 'bottom',
        dropdownAllowAll: false,
        setCurrentPage: 1,
        nextLabel: data.locale[lang].pagination.nextLabel,
        prevLabel: data.locale[lang].pagination.prevLabel,
        rowsPerPageLabel: data.locale[lang].pagination.rowsPerPageLabel,
        ofLabel: data.locale[lang].pagination.ofLabel,
        pageLabel: data.locale[lang].pagination.pageLabel,
        allLabel: data.locale[lang].pagination.allLabel,
      }"
    >
      <template slot="table-row" slot-scope="props">
        <template v-if="props.column.field == 'living_area.size'">
          <template
            v-if="
              props.row.living_area.size && props.row.living_area.size !== 0
            "
          >
            {{ parseFloat(props.row.living_area.size) | numeral("0,0[.]00") }}
            {{ formatUnit(props.row.living_area.unit) }}
          </template>
          <template v-else> - </template>
        </template>

        <template v-else-if="props.column.field == 'plot_area.size'">
          <template
            v-if="props.row.plot_area.size && props.row.plot_area.size !== 0"
          >
            {{ parseFloat(props.row.plot_area.size) | numeral("0,0[.]00") }}
            {{ formatUnit(props.row.plot_area.unit) }}
          </template>
          <template v-else> - </template>
        </template>

        <template v-else-if="props.column.field == 'terrace_area.size'">
          <template
            v-if="
              props.row.terrace_area.size && props.row.terrace_area.size !== 0
            "
          >
            {{ parseFloat(props.row.terrace_area.size) | numeral("0,0[.]00") }}
            {{ formatUnit(props.row.terrace_area.unit) }}
          </template>
          <template v-else> - </template>
        </template>

        <template v-else-if="props.column.field == 'price.amount'">
          <template
            v-if="
              !props.row.price_hidden &&
              props.row.status !== 'sold' &&
              props.row.price.amount > 0
            "
          >
            {{ formatCurrency(props.row.price.currency) }}
            {{ parseFloat(props.row.price.amount) | numeral("0,0[.]00") }}
          </template>
          <template v-else>-</template>
        </template>

        <template v-else-if="props.column.field == 'status'">
          <div
            v-if="props.row.status === 'available'"
            class="inline-block px-3 py-1 text-xs font-medium leading-loose tracking-widest text-white uppercase bg-primary"
          >
            <p v-if="props.row.negotiation === 'sale'">
              {{ data.locale[lang].negotiation.sale }}
            </p>
            <p v-else>
              {{ data.locale[lang].negotiation.let }}
            </p>
          </div>
          <div
            v-else-if="props.row.status === 'option'"
            class="inline-block px-3 py-1 text-xs font-medium leading-loose tracking-widest uppercase border border-current"
          >
            {{ data.locale[lang].negotiation.option }}
          </div>
          <div
            v-else-if="props.row.status === 'sold'"
            class="inline-block px-3 py-1 text-xs font-medium leading-loose tracking-widest uppercase"
          >
            {{ data.locale[lang].negotiation.sold }}
          </div>
          <div
            v-else-if="props.row.status === 'rented'"
            class="inline-block px-3 py-1 text-xs font-medium leading-loose tracking-widest uppercase"
          >
            {{ data.locale[lang].negotiation.rented }}
          </div>
        </template>
      </template>
    </vue-good-table>
  </div>
</template>

<script>
import axios from "axios";
import { VueGoodTable } from "vue-good-table";
import "vue-good-table/dist/vue-good-table.css";

export default {
  props: ["component"],
  components: {
    VueGoodTable,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],

      id: window[this.component].project_id,
      unit_id: window[this.component].unit_id,
      project_type: window[this.component].project_type,
      posts_per_page: window[this.component].posts_per_page,

      columns: [
        {
          label: window[this.component].locale[window.lang].labels.price,
          field: "price.amount",
          type: "number",
        },
        {
          label: window[this.component].locale[window.lang].labels.status,
          field: "status",
          sortable: false,
        },
        {
          label: "Permalink",
          field: "permalink",
          hidden: true,
        },
        {
          label: "Price hidden",
          field: "price_hidden",
          hidden: true,
        },
        {
          label: "Negotiation",
          field: "negotiation",
          hidden: true,
        },
      ],
      rows: [],
    };
  },
  methods: {
    formatCurrency(currency) {
      let output = "€ ";
      if (currency === "GBP") {
        output = "£";
      }
      return output;
    },
    formatUnit(unit) {
      let output = "m²";
      if (unit === "sq_ft") {
        output = "ft²";
      }
      return output;
    },
    disableRow(row) {
      return row.status !== "available" ? "is-disabled" : "";
    },
    openUnit(params) {
      if (params.row.status !== "sold" && params.row.status !== "option") {
        window.location = params.row.permalink;
      }
    },
    addRows(data) {
      data.forEach((estate) => {
        if (this.unit_id !== estate.meta.estate.id) {
          let plotAreaSize = estate.meta.sizes.plot_area.size;
          let plotAreaUnit = estate.meta.sizes.plot_area.unit;
          if (estate.meta.sizes.plot_area.unit === "are") {
            plotAreaSize = estate.meta.sizes.plot_area.size * 100;
            plotAreaUnit = "sq_m";
          }
          const column = {
            permalink: estate.permalink,
            title: estate.meta.estate.title.nl,
            bedrooms: estate.meta.facilities.bedrooms,
            living_area: {
              size: parseFloat(estate.meta.sizes.liveable_area.size),
              unit: estate.meta.sizes.liveable_area.unit,
            },
            plot_area: {
              size: parseFloat(plotAreaSize),
              unit: plotAreaUnit,
            },
            price: {
              amount: parseFloat(estate.meta.price.amount),
              currency: estate.meta.price.currency,
            },
            price_hidden: estate.meta.price.hidden,
            status: estate.meta.estate.status,
            negotiation: estate.meta.features.negotiation,
          };

          if (estate.meta.rooms && estate.meta.rooms.room.length > 0) {
            estate.meta.rooms.room.forEach((room) => {
              if (room.type === "terrace") {
                column.terrace_area = {
                  size: parseFloat(room.size),
                  unit: room.unit,
                };
              }
            });
          } else {
            column.terrace_area = 0;
          }

          this.rows.push(column);
        }
      });
    },
    generateRows() {
      if (this.project_type === "land") {
        this.columns.splice(0, 0, {
          label: window[this.component].locale[window.lang].labels.unit,
          field: "title",
        });
      }

      if (this.project_type === "house") {
        this.columns.splice(0, 0, {
          label: window[this.component].locale[window.lang].labels.unit,
          field: "title",
        });

        this.columns.splice(1, 0, {
          label: window[this.component].locale[window.lang].labels.living_area,
          field: "living_area.size",
          type: "number",
        });

        this.columns.splice(2, 0, {
          label: window[this.component].locale[window.lang].labels.plot_area,
          field: "plot_area.size",
          type: "number",
        });
      }

      if (this.project_type === "apartment") {
        this.columns.splice(0, 0, {
          label: window[this.component].locale[window.lang].labels.apartment,
          field: "title",
        });

        this.columns.splice(1, 0, {
          label: window[this.component].locale[window.lang].labels.bedrooms,
          field: "bedrooms",
          type: "number",
        });

        this.columns.splice(2, 0, {
          label: window[this.component].locale[window.lang].labels.living_area,
          field: "living_area.size",
          type: "number",
        });

        this.columns.splice(3, 0, {
          label: window[this.component].locale[window.lang].labels.terrace_area,
          field: "terrace_area.size",
          type: "number",
        });
      }
    },
    getUnits() {
      axios
        .get(`/wp-json/v1/sweepbright/property/${this.id}/units`)
        .then((response) => {
          this.addRows(response.data.meta);
        });
    },
  },
  mounted() {
    if (this.id) {
      this.getUnits();
    }
    this.generateRows();
  },
};
</script>

<style>
:root {
  --highlightColor: #000;
}

.vgt-inner-wrap {
  @apply border-none shadow-none;
}

.vgt-table {
  @apply border-none bg-transparent !important;
}

.vgt-wrap__footer .footer__navigation > button:focus {
  outline: none !important;
}

.vgt-wrap__footer .footer__navigation__info,
.vgt-wrap__footer .footer__navigation__page-info {
  @apply mx-0;
}

.vgt-wrap__footer .footer__navigation__page-info {
  @apply hidden;
}

.vgt-wrap__footer .footer__navigation__page-info span {
  @apply ml-0;
}

.vgt-table thead th {
  background: none !important;
  @apply text-black uppercase tracking-widest text-xs font-medium leading-loose !important;
}

.vgt-table th.sortable:before,
.vgt-table th.sortable:after {
  @apply left-0 right-auto !important;
}

.vgt-table th.sortable span {
  @apply pl-3 !important;
}

.vgt-table th.sortable button:before {
  border-top: 5px solid rgba(0, 0, 0, 0.2) !important;
}

.vgt-table th.sortable button:after {
  border-bottom: 5px solid rgba(0, 0, 0, 0.2) !important;
}

.vgt-table thead th.sorting-asc:after,
.vgt-table thead th.sorting-asc button:after {
  border-bottom: 5px solid var(--highlightColor) !important;
}

.vgt-table thead th.sorting-desc:before,
.vgt-table thead th.sorting-desc button:before {
  border-top: 5px solid var(--highlightColor) !important;
}

table.vgt-table td {
  @apply text-black align-middle font-medium !important;
}

table.vgt-table tr:last-child td {
  @apply whitespace-no-wrap !important;
}

table.vgt-table tr:nth-last-child(2) td,
table.vgt-table tr:last-child td {
  @apply whitespace-no-wrap !important;
}

table.vgt-table tr.is-disabled td {
  @apply text-gray-500 cursor-default font-normal !important;
}

table.vgt-table tr:last-child td {
  @apply border-none !important;
}

.vgt-right-align {
  @apply text-left !important;
}

.vgt-table th:last-child.vgt-left-align {
  @apply text-right !important;
}

.vgt-table td:last-child.vgt-left-align {
  @apply text-right !important;
}

.vgt-wrap__footer {
  background: none !important;
  @apply text-black p-0 border-none mt-4 !important;
}

.footer__row-count.vgt-pull-left {
  @apply hidden !important;
}

.vgt-wrap__footer .footer__navigation {
  @apply float-none !important;
}

.vgt-wrap__footer .footer__navigation,
.vgt-wrap__footer .footer__navigation__page-btn span {
  @apply text-base !important;
}

.vgt-wrap__footer .footer__navigation__page-btn {
  @apply font-medium -mt-1 text-black !important;
}

.vgt-wrap__footer .footer__navigation__page-btn .chevron.right::after {
  border-left: 6px solid var(--highlightColor) !important;
}

.vgt-wrap__footer .footer__navigation__page-btn .chevron.left::after {
  border-right: 6px solid var(--highlightColor) !important;
}

.vgt-wrap__footer .footer__navigation__info,
.vgt-wrap__footer .footer__navigation__page-info {
  line-height: 1;
}

.vgt-wrap__footer .footer__navigation__page-info__current-entry {
  @apply font-semibold bg-gray-200 !important;
}

table.vgt-table tr.clickable:hover {
  @apply bg-gray-200 !important;
}

table.vgt-table tr.clickable.is-disabled:hover {
  @apply bg-transparent !important;
}

.vgt-wrap__footer .footer__navigation__page-btn {
  @apply ml-0 !important;
}

/* Large (lg) */
@screen lg {
  .vgt-wrap__footer .footer__navigation__page-info {
    @apply inline-block mr-5;
  }
}
</style>
