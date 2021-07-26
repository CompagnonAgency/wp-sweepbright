<template>
  <div>
    <div
      class="border-gray-400 last:border-0"
      :class="[
        conditionalField(field) && field.type !== 'locale'
          ? 'p-5 border-b'
          : 'border-0 p-0',
        isGroup ? 'px-0' : '',
      ]"
      v-for="(field, index) in fields"
      :key="index"
    >
      <template v-if="conditionalField(field)">
        <template v-if="field.type !== 'locale'">
          <p
            class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase "
          >
            {{ field.name }}
            <template
              v-if="
                !field.sync &&
                field.type !== 'upload_single' &&
                field.type !== 'upload_multiple' &&
                field.type !== 'page_select' &&
                field.type !== 'page_select_multiple' &&
                field.type !== 'group'
              "
            >
              <i class="ml-1 text-sm fad fa-language"></i>
            </template>
            <template
              v-if="
                !field.allow_empty &&
                (field.type === 'text' || field.type === 'wysiwyg')
              "
            >
              <span class="text-red-500">*</span>
            </template>
          </p>

          <template v-if="field.type === 'page_select'">
            <FieldPage
              :col="col"
              :pageSelect="pageSelect"
              :field="field"
              :pages="pages"
            ></FieldPage>
          </template>

          <template v-else-if="field.type === 'page_select_multiple'">
            <FieldPageMultiple
              :col="col"
              :pageSelect="pageSelect"
              :field="field"
              :pages="pages"
            ></FieldPageMultiple>
          </template>

          <template v-else-if="field.type === 'select'">
            <FieldSelect :field="field" :col="col" :lang="lang"></FieldSelect>
          </template>

          <template v-else-if="field.type === 'range'">
            <FieldRange :field="field" :col="col" :lang="lang"></FieldRange>
          </template>

          <template v-else-if="field.type === 'number'">
            <FieldNumber :field="field" :col="col" :lang="lang"></FieldNumber>
          </template>

          <template v-else-if="field.type === 'upload_single'">
            <FieldUploadSingle
              :field="field"
              :col="col"
              :lang="lang"
            ></FieldUploadSingle>
          </template>

          <template v-else-if="field.type === 'upload_multiple'">
            <FieldUploadMultiple
              :field="field"
              :col="col"
              :lang="lang"
            ></FieldUploadMultiple>
          </template>

          <template v-else-if="field.type === 'wysiwyg'">
            <FieldWysiwyg :field="field" :col="col" :lang="lang"></FieldWysiwyg>
          </template>

          <template v-else-if="field.type === 'group'">
            <FieldGroup
              :components="components"
              :pageSelect="pageSelect"
              :pages="pages"
              :field="field"
              :col="col"
              :lang="lang"
            >
            </FieldGroup>
          </template>

          <template v-else>
            <FieldText :field="field" :col="col" :lang="lang"></FieldText>
          </template>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
import FieldGroup from "./FieldGroup";
import FieldText from "./FieldText";
import FieldWysiwyg from "./FieldWysiwyg";
import FieldNumber from "./FieldNumber";
import FieldRange from "./FieldRange";
import FieldSelect from "./FieldSelect";
import FieldPage from "./FieldPage";
import FieldPageMultiple from "./FieldPageMultiple";
import FieldUploadSingle from "./FieldUploadSingle";
import FieldUploadMultiple from "./FieldUploadMultiple";

export default {
  props: [
    "components",
    "col",
    "lang",
    "pages",
    "isGroup",
    "pageSelect",
    "fields",
  ],
  components: {
    FieldGroup,
    FieldText,
    FieldWysiwyg,
    FieldNumber,
    FieldRange,
    FieldSelect,
    FieldPage,
    FieldPageMultiple,
    FieldUploadSingle,
    FieldUploadMultiple,
  },
  methods: {
    conditionalField(field) {
      let output = true;
      if (field.condition) {
        let conditions = [];
        Object.entries(field.condition).forEach(([key, value]) => {
          const conditionField = this.fields.find((obj) => obj.id === key);
          let data = "";

          if (conditionField.sync) {
            data = this.col.data["default"][key];
          } else {
            data = this.col.data[this.lang][key];
          }

          if (value.includes(data)) {
            conditions.push({ isTrue: true });
          } else {
            conditions.push({ isTrue: false });
          }
        });

        if (conditions.every((data) => data.isTrue)) {
          output = true;
        } else {
          output = false;
        }
      }

      return output;
    },
  },
  mounted() {},
};
</script>
