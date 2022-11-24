<template>
  <div
    v-click-outside="hideDropdowns"
    class="relative flex-shrink-0"
    :class="'z-' + zIndex"
  >
    <div
      class="flex items-center justify-between h-full py-2 cursor-pointer select-none  form-input lg:py-auto"
      :class="`${theme.rounded_lg} ${range.open ? 'border-primary' : ''} ${
        theme.form_style !== 'line' ? 'px-4' : ''
      }`"
      @click.stop="toggleDropdown"
    >
      <p class="flex-shrink-0">
        {{ range.placeholder }}
      </p>
      <i class="ml-2 text-base text-gray-400 far fa-chevron-down"></i>
    </div>

    <div
      class="absolute top-0 left-0 z-20 w-full p-5 overflow-x-hidden bg-white border border-gray-200  lg:w-56 mt-14"
      :class="`${theme.rounded}`"
      v-if="range.open"
      @click.stop="range.open = true"
    >
      <div
        v-for="(item, index) in range.range"
        :key="index"
        class="mb-7 last:mb-4"
      >
        <p class="mb-1 font-semibold">{{ item.label }} {{ item.symbol }}</p>
        <vue-slider
          :min="item.min"
          :max="item.max"
          v-model="range.selected[item.id]"
          :interval="item.interval"
          :marks="getMarks(item)"
          :enable-cross="false"
          @drag-end="getResults(item)"
        >
          <template v-slot:tooltip="{ value }">
            <div
              class="p-1 px-2 text-base font-medium text-white bg-black rounded-lg shadow-md  py whitespace-nowrap"
            >
              {{ formatMark(value, item) }}
            </div>
          </template>
        </vue-slider>
      </div>
    </div>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
  props: ['range', 'id', 'zIndex'],
  components: {
    VueSlider,
  },
  directives: {
    ClickOutside,
  },
  data() {
    return {
      lang: window.lang,
      theme: window.theme,
    }
  },
  methods: {
    formatMark(value, item) {
      let str = ''
      value = this.$formatNumber(value, this.$getNumberFormat(this.lang))
      switch (item.symbol_position) {
        case 'before':
          str = `${item.symbol} ${value}`
          break
        case 'after':
          str = `${value} ${item.symbol} `
          break
        default:
          str = value
          break
      }
      return str
    },
    getMarks(item) {
      const obj = {}
      obj[item.min] = this.formatMark(item.min, item)
      obj[item.max] = `${this.formatMark(item.max, item)} +`
      return obj
    },
    hideDropdowns() {
      this.range.open = false
    },
    toggleDropdown() {
      this.$bus.$emit('toggleDropdown', this.id)
    },
    getResults(item) {
      if (this.range.selected[item.id][1] === item.max) {
        this.range.selected[item.id][1] = false
      }

      this.$bus.$emit(
        'setRange',
        this.id,
        item.id,
        this.range.selected[item.id]
      )

      if (!this.range.selected[item.id][1]) {
        this.range.selected[item.id][1] = item.max
      }
    },
  },
}
</script>

<style>
.vue-slider-ltr .vue-slider-mark-label {
  font-size: 16px !important;
  font-weight: 600 !important;
  left: auto !important;
  margin-top: 5px !important;
  transform: translateX(0) !important;
  color: rgba(0, 0, 0, 0.5);
}

.vue-slider-mark:last-child {
  left: auto !important;
  right: 0 !important;
  transform: translateX(0) translateY(-50%) !important;
}

.vue-slider-mark:last-child .vue-slider-mark-label {
  right: 0 !important;
}

.vue-slider-process {
  background-color: #000;
}

.vue-slider-dot-handle {
  background-color: #000;
  box-shadow: none;
}

.vue-slider-dot-handle-focus {
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}

.vue-slider-dot {
  width: 20px !important;
  height: 20px !important;
}

@media (min-width: 1024px) {
  .vue-slider-dot {
    width: 15px !important;
    height: 15px !important;
  }
}
</style>
