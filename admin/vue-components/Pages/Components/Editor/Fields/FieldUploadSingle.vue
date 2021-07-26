<template>
  <div>
    <div class="relative mb-2" v-if="inputData(field, col)">
      <div class="aspect-ratio-16/9"></div>
      <img
        class="absolute top-0 left-0 object-contain object-center w-full h-full p-5 bg-gray-200 border-none rounded"
        :src="inputData(field, col)"
      />
    </div>
    <div class="flex mt-2 space-x-2">
      <button class="w-1/2 btn btn-primary" @click="openLibrary">
        Select image
      </button>
      <button class="w-1/2 btn btn-default" @click="removeImage">Remove</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["field", "col", "lang"],
  components: {},
  computed: {},
  data() {
    return {
      frame: false,
    };
  },
  methods: {
    inputData(field, activeCol) {
      let data = this.col.data["default"][field.id];
      if (!data) {
        data = field.default;
        activeCol.data["default"][field.id] = data;
      }
      return data;
    },
    removeImage() {
      this.col.data["default"][this.field.id] = "";
    },
    mediaLibrary() {
      this.frame = wp.media({
        title: "Media Library",
        button: {
          text: "Use this image",
        },
        library: {
          type: ["image"],
        },
        multiple: false,
      });

      const self = this;
      this.frame.on("select", function () {
        self.frame
          .state()
          .get("selection")
          .map(function (attachment) {
            if (attachment.attributes.type === "image") {
              if (attachment.toJSON().url) {
                const path = attachment
                  .toJSON()
                  .url.replace(/^(?:\/\/|[^/]+)*\//, "");
                self.col.data["default"][self.field.id] = `/${path}`;
                self.$forceUpdate();
              }
            } else {
              self.$dialog
                .confirm(
                  "Image filetype is not supported, please select a valid image."
                )
                .then((dialog) => {
                  self.frame.open();
                });
            }
          })
          .join();
      });
    },
    openLibrary() {
      this.frame.open();
    },
  },
  mounted() {
    this.mediaLibrary();
  },
};
</script>
