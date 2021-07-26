<template>
  <select @change="updateData($event, col, field)">
    <option
      v-for="(option, index) in field.options"
      :key="index"
      :value="index"
      :selected="index === inputData(field, col)"
    >
      {{ option }}
    </option>
  </select>
</template>

<script>
export default {
  props: ["field", "col", "lang"],
  components: {},
  computed: {},
  data() {
    return {};
  },
  methods: {
    inputData(field, activeCol) {
      let data = this.col.data[field.sync ? "default" : this.lang][field.id];

      if (!data) {
        data = field.default;

        if (field.sync) {
          activeCol.data["default"][field.id] = data;
        } else {
          activeCol.data["en"][field.id] = data;
          activeCol.data["nl"][field.id] = data;
          activeCol.data["fr"][field.id] = data;
        }
      }
      return data;
    },
    updateData(e, col, field) {
      const data = e.target.value;
      if (!field.sync) {
        col.data[this.lang][field.id] = data;
      } else {
        col.data["default"][field.id] = data;
      }
      this.$forceUpdate();
    },
  },
  mounted() {},
};
</script>
