<template>
  <div
    class="inline-flex flex-col w-full transition-all duration-300 wp-banner form-input lg:max-w-2xl lg:flex-row lg:items-center"
    :class="`${theme.rounded} lg:${theme.rounded_lg} ${
      theme.form_style !== 'line' && theme.form_style !== 'filled'
        ? 'shadow-md'
        : 'border-white border-opacity-40'
    } ${ theme.form_style === 'line' ? 'is-line' : 'is-filled' }`"
  >
    <div class="relative z-20 flex-1">
      <tags-input
        ref="tagsinput"
        wrapper-class="tags-input-wrapper-default"
        input-id="tag-input"
        :only-existing-tags="true"
        :placeholder="data.search_placeholder"
        :typeahead-activation-threshold="3"
        :limit="1"
        :hide-input-on-limit="true"
        :typeahead="true"
        typeahead-style="dropdown"
        :typeahead-callback="search_tags"
        :typeahead-hide-discard="true"
        v-model="store.selectedTags"
      >
      </tags-input>
    </div>

    <div
      class="relative z-10 justify-center flex-shrink-0 py-3 mb-5 border-t border-b lg:mr-5 lg:mb-0 lg:border-0 lg:py-0 lg:justify-start"
      v-if="data.dropdown_filter === 'negotiation'"
    >
      <div
        class="flex items-center justify-center w-full h-full font-medium text-gray-700 cursor-pointer select-none "
        @click="config.negotiation.open = !config.negotiation.open;"
      >
        <p
          class="flex-shrink-0"
          :class="
            theme.form_style === 'filled' || theme.form_style === 'line'
              ? 'text-white'
              : ''
          "
        >
          <template v-if="store.negotiation">
            {{
              config.negotiation.dropdown.find(
                (obj) => obj.value === store.negotiation
              ).label
            }}
          </template>
          <template v-else>
            {{ data.search_dropdown }}
          </template>
        </p>
        <i class="mt-1 ml-2 text-base text-gray-400 far fa-chevron-down"></i>
      </div>

      <div
        class="absolute top-0 left-0 w-full overflow-hidden bg-white border border-gray-200 shadow-md lg:w-32 mt-9"
        :class="`${theme.rounded}`"
        v-if="config.negotiation.open"
      >
        <ul class="font-medium text-left divide-y divide-gray-100">
          <li
            v-for="(dropdown, index) in config.negotiation.dropdown"
            :key="index"
            @click="
              store.negotiation = dropdown.value;
              config.negotiation.open = false;
            "
            class="px-3 py-2 transition duration-200 cursor-pointer hover:bg-gray-100"
          >
            {{ dropdown.label }}
          </li>
        </ul>
      </div>
    </div>

    <button class="flex-shrink-0 btn btn-primary" @click="search">
      <i class="mr-1 fal fa-search"></i>
      {{ data.search_button }}
    </button>
  </div>
</template>

<script>
import axios from 'axios'
import VoerroTagsInput from '@voerro/vue-tagsinput'

export default {
  props: ["component"],
  components: {
    'tags-input': VoerroTagsInput,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
      data: window[this.component],
      config: {
        negotiation: {
          open: false,
          dropdown: [],
        },
      },
      store: {
        selectedTags: [],
        negotiation: "",
      },
    };
  },
  methods: {
    search_tags(query) {
      return new Promise((resolve) => {
        axios
          .get(`/wp-json/v1/sweepbright/locations?search=${query}&country=${this.data.search_country}`)
          .then((res) => {
            resolve(res.data)
            const searchParam = query.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            this.$refs.tagsinput.doSearch(searchParam)
          });
      });
    },
    search() {
      let destination = this.data.destination_page.url;

      if (this.store.selectedTags.length > 0 || this.store.negotiation) {
        destination += "?";
      }

      if (this.store.selectedTags.length > 0) {
        destination += `region=${this.store.selectedTags[0].value}&lat=${this.store.selectedTags[0].latLng.lat}&lng=${this.store.selectedTags[0].latLng.lng}&`;
      }

      if (this.store.negotiation) {
        destination += `negotiation=${this.store.negotiation}&`;
      }

      if (this.store.search || this.store.negotiation) {
        destination = destination.slice(0, -1);
      }
      window.location.href = destination;
    },
    setDropdown() {
      this.config.negotiation.dropdown = [
        {
          label: this.data.locale[this.lang].buy,
          value: "sale",
        },
        {
          label: this.data.locale[this.lang].rent,
          value: "let",
        },
      ];
    },
  },
  mounted() {
    this.setDropdown();
  },
};
</script>
