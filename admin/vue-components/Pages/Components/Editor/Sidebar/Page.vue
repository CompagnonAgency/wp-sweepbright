<template>
  <div>
    <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
      Page
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Status
      </p>
      <p class="flex items-center text-base text-gray-700">
        <template v-if="pageDefault.settings.locked">
          <i class="w-6 mr-1 text-lg text-gray-500 far fa-lock-alt"></i>
          Locked
        </template>
        <template v-else>
          <i class="w-6 mr-1 text-lg text-gray-500 far fa-edit"></i>
          Editable
        </template>
      </p>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Template
      </p>
      <p class="text-base text-gray-700">
        <template v-if="pageDefault.settings.template === 'estate'"
          >Estate</template
        >
        <template v-if="pageDefault.settings.template === 'default'"
          >Default</template
        >
        <template v-if="pageDefault.settings.template === 'base'"
          >Base</template
        >
      </p>
    </div>
    <template v-if="navigation">
      <div class="p-5 border-b border-gray-400 last:border-0">
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          Navigation
        </p>
        <p class="text-base text-gray-700">
          {{ components.find((obj) => obj.id === navigation).name }}
        </p>
      </div>
    </template>
    <template v-if="pageDefault.settings.template === 'default'">
      <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
        Information
      </div>
      <div class="p-5 border-b border-gray-400 last:border-0">
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          Slug
        </p>
        <input type="text" @change="setSlug" v-model="pageDefault.slug" />
      </div>
      <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
        SEO
      </div>
      <div class="p-5 border-b border-gray-400 last:border-0">
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          Title
        </p>
        <input
          type="text"
          @change="setSettings"
          v-model="pageDefault.settings.seo.title[lang]"
        />
      </div>
      <div class="p-5 border-b border-gray-400 last:border-0">
        <p
          class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase"
        >
          Description
        </p>
        <input
          type="text"
          @change="setSettings"
          v-model="pageDefault.settings.seo.description[lang]"
        />
      </div>
    </template>
  </div>
</template>

<script>
import bus from "../../../../../js/pages/bus.js";

export default {
  props: ["page", "components", "lang", "navigation"],
  components: {},
  computed: {},
  data() {
    return {
      pageDefault: this.page,
    };
  },
  methods: {
    setSlug() {
      bus.$emit("setSlug", this.pageDefault.slug);
    },
    setSettings() {
      bus.$emit("setSettings", this.pageDefault.settings);
    },
  },
  mounted() {},
};
</script>
