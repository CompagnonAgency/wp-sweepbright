<template>
  <input
    type="number"
    :value="inputData(field, col)"
    @change="updateData($event, col, field)"
  />
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

      if (!this.col.data.default[field.id]) {
        this.col.data.default = {};
      }

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
