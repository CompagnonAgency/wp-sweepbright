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
          <i class="w-6 text-lg text-gray-500 fad fa-lock-alt"></i>
          Locked
        </template>
        <template v-else>
          <i class="w-6 text-lg text-gray-500 fad fa-edit"></i>
          Editable
        </template>
      </p>
    </div>
    <div class="p-5 border-b border-gray-400 last:border-0">
      <p class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase">
        Template
      </p>
      <p class="text-base text-gray-700">
        <i class="w-5 text-lg text-gray-500 fad fa-file"></i>
        <template v-if="pageDefault.settings.template === 'estate'"
          >Estate</template
        >
        <template v-if="pageDefault.settings.template === 'default'"
          >Default</template
        >
        <template v-if="pageDefault.settings.template === 'blog'"
          >Blog</template
        >
        <template v-if="pageDefault.settings.template === 'base'"
          >Base</template
        >
        <template v-if="pageDefault.settings.template === '404'">404</template>
      </p>
    </div>
    <template v-if="pageDefault.settings.template === 'default'">
      <template v-if="user_roles.includes('administrator') && isEditable()">
        <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
          Information
        </div>
        <div class="p-5 border-b border-gray-400 last:border-0">
          <p
            class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase "
          >
            Slug
          </p>
          <input type="text" @change="setSlug" v-model="pageDefault.slug" />
        </div>
      </template>

      <template v-if="user_roles.includes('administrator')">
        <div class="p-5 py-3 font-medium bg-gray-200 border-b border-gray-400">
          SEO
        </div>
        <div class="p-5 border-b border-gray-400 last:border-0">
          <p
            class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase "
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
            class="mb-3 text-xs font-medium tracking-wide text-gray-600 uppercase "
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
      user_roles: window.wp_user_roles,
      pageDefault: this.page,
    };
  },
  methods: {
    isEditable() {
      return !(
        this.pageDefault.title[this.lang] === "Home" &&
        this.pageDefault.slug === "home"
      );
    },
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
