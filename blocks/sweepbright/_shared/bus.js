const bus = new Vue();

const mixin = {
  beforeCreate() {
    this.$bus = bus;
  },
};

export default mixin;
