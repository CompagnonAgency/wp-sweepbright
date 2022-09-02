<template>
  <div>
    <div class="divide-gray-200 divide-y-1 mb-5">
      <draggable
        v-if="col.data.default[field.id]"
        v-model="col.data.default[field.id]"
        @change="endDrag($event, col.data.default[field.id])"
      >
        <div
          class="cursor-pointer flex p-3 items-center hover:bg-gray-200"
          v-for="slide in col.data.default[field.id]"
          :key="slide.id"
        >
          <i class="cursor-move mr-3 text-lg text-gray-500 far fa-bars"></i>

          <div class="flex-1 relative" v-if="slide.url">
            <div class="aspect-ratio-16/9"></div>
            <img
              class="border-none rounded h-full object-contain object-center w-full top-0 left-0 absolute"
              :src="slide.url"
            />
          </div>

          <i
            v-tooltip="{ content: 'Remove slide' }"
            @click="deleteImage(field, slide.id)"
            class="text-lg ml-3 text-red-500 far fa-trash"
          ></i>
        </div>
      </draggable>
    </div>
    <button class="w-full btn btn-primary" @click="addImage(col, field)">
      Add Image
    </button>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["field", "col", "lang"],
  components: {
    draggable,
  },
  computed: {},
  data() {
    return {
      frame: false,
    };
  },
  methods: {
    inputData() {
      let data = this.col.data.default[this.field.id];
      if (!data) {
        data = this.field.default;
        this.col.data.default[this.field.id] = data;
      }
      this.$forceUpdate();
    },
    addImage() {
      this.frame.open();
    },
    deleteImage(field, id) {
      bus.$emit("deleteItem", {
        field,
        id,
        col: this.col,
      });
      this.$forceUpdate();
    },
    endDrag($event, data) {
      bus.$emit("endDrag", {
        $event,
        data,
      });
      this.col.data["default"][this.field.id] = data;
      this.$forceUpdate();
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
                  .url;
                bus.$emit("addImage", {
                  id: attachment.attributes.id,
                  url: path,
                  col: self.col,
                  field: self.field,
                });
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
    this.inputData();
    this.mediaLibrary();
  },
};
</script>
