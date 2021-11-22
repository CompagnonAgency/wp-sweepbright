<template>
  <quill-editor
    ref="editor"
    :content="inputData(field, col)"
    :options="options"
    @blur="onEditorBlur($event)"
    @change="onEditorChange($event)"
  />
</template>

<script>
import { quillEditor } from "vue-quill-editor";

export default {
  props: ["field", "col", "lang"],
  components: {
    quillEditor,
  },
  computed: {},
  data() {
    return {
      options: {
        placeholder: "",
        modules: {
          toolbar: [
            [{ header: [1, 2, 3, 4, 5, false] }],
            [
              "bold",
              "italic",
              "underline",
              "blockquote",
              "link",
              { list: "bullet" },
              { align: [] },
            ],
            ["clean"],
          ],
        },
      },
    };
  },
  methods: {
    inputData(field, activeCol) {
      let data = this.col.data[field.sync ? "default" : this.lang][field.id];

      if (!data && !this.field.allow_empty) {
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
    onEditorChange({ quill, html, text }) {
      const data = html;

      if (!this.field.sync) {
        this.$set(this.col.data[this.lang], this.field.id, data);
      } else {
        this.$set(this.col.data.default, this.field.id, data);
      }
    },
    onEditorBlur({ quill, html, text }) {
      if (!html && !this.field.allow_empty) {
        this.$refs.editor.quill.root.innerHTML = this.inputData(
          this.field,
          this.col
        );
      }
    },
  },
};
</script>
