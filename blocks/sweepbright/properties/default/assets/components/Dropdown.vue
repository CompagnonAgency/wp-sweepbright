<template>
  <div
    v-click-outside="hideDropdowns"
    class="relative z-10 inline-block w-full"
  >
    <div
      class="
        inline-flex
        items-center
        justify-between
        w-full
        h-full
        cursor-pointer
        select-none
        form-input
        lg:w-48
      "
      :class="`${theme.rounded_lg} ${dropdown.open ? 'border-primary' : ''} ${
        theme.form_style !== 'line' ? 'px-4' : ''
      }`"
      @click.stop="toggleDropdown"
    >
      <p class="flex-shrink-0">
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
      <i class="ml-2 text-base text-gray-400 far fa-chevron-down"></i>
    </div>

    <div
      class="
        absolute
        top-0
        left-0
        w-full
        max-w-xs
        overflow-hidden
        bg-white
        border border-gray-200
        mt-14
      "
      :class="`${theme.rounded}`"
      v-if="dropdown.open"
    >
      <ul class="font-medium text-left divide-y divide-gray-100">
        <li
          v-for="(item, index) in dropdown.dropdown"
          :key="index"
          @click="setDropdown(item)"
          class="
            px-3
            py-2
            transition
            duration-200
            cursor-pointer
            hover:bg-gray-100
          "
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
      this.dropdown.open = false;
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
