<template>
  <div v-click-outside="hideDropdowns" class="relative z-10 flex-shrink-0">
    <div
      class="flex items-center justify-between h-full px-4 border-2 cursor-pointer select-none lg:w-48"
      :class="`${theme.rounded_lg} ${
        dropdown.open ? 'border-primary' : 'border-gray-200'
      }`"
      @click.stop="toggleDropdown"
    >
      <p class="flex-shrink-0 font-semibold">
        <template v-if="dropdown.selected">
          {{
            dropdown.dropdown.find((obj) => obj.value === dropdown.selected)
              .label
          }}
        </template>
        <template v-else>
          {{ dropdown.placeholder }}
        </template>
      </p>
      <i class="mt-1 ml-2 text-base text-gray-400 far fa-chevron-down"></i>
    </div>

    <div
      class="absolute top-0 left-0 w-full overflow-hidden bg-white border-2 border-gray-200 mt-14"
      :class="`${theme.rounded}`"
      v-if="dropdown.open"
    >
      <ul class="font-medium divide-y divide-gray-100">
        <li
          v-for="(item, index) in dropdown.dropdown"
          :key="index"
          @click="setDropdown(item)"
          class="px-3 py-2 transition duration-200 cursor-pointer hover:bg-gray-100"
        >
          {{ item.label }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import ClickOutside from "vue-click-outside";

export default {
  props: ["dropdown", "id"],
  components: {},
  directives: {
    ClickOutside,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
    };
  },
  methods: {
    hideDropdowns() {
      this.$bus.$emit("hideDropdowns", this.id);
    },
    toggleDropdown() {
      this.$bus.$emit("toggleDropdown", this.id);
    },
    setDropdown(dropdown) {
      this.$bus.$emit("setDropdown", this.id, dropdown);
    },
  },
};
</script>
