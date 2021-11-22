<template>
  <div
    v-if="isLoaded"
    class="fixed z-20 flex justify-between bg-white shadow pages-navigation"
  >
    <div class="flex flex-1">
      <div class="p-1">
        <button
          class="flex items-center btn btn-default"
          @click.prevent="$router.push({ name: 'home' })"
        >
          <i class="mr-2 text-base text-gray-500 far fa-angle-left"></i>
          Back
        </button>
      </div>

      <div
        class="relative flex items-center flex-1 max-w-sm p-1 border-r border-gray-200 "
      >
        <i class="absolute ml-3 text-base text-gray-500 fad fa-file-alt"></i>
        <input
          v-if="page.settings.template === 'default'"
          type="text"
          placeholder="Title"
          v-model="page.title[lang]"
          class="w-full h-full font-medium input-heading"
          :disabled="
            user_roles.includes('administrator') && isEditable() ? false : true
          "
        />
        <input
          v-else
          type="text"
          placeholder="Title"
          v-model="page.title"
          class="w-full h-full font-medium input-heading"
          disabled
        />
      </div>
    </div>

    <div class="flex items-center justify-end w-1/2">
      <div class="flex-shrink-0 p-1 mr-2" v-if="page.updated">
        <p class="text-sm text-gray-500">
          <i class="fad fa-clock"></i>
          Updated {{ moment(page.updated).fromNow() }}
        </p>
      </div>
      <div class="flex-shrink-0 p-1 mr-2" v-if="settings.multilanguage">
        <select v-model="lang" @change="setLang">
          <option value="nl" v-if="settings.enabled_nl">NL</option>
          <option value="en" v-if="settings.enabled_en">EN</option>
          <option value="fr" v-if="settings.enabled_fr">FR</option>
        </select>
      </div>
      <div
        class="p-1"
        v-if="page.id !== 'create' && page.settings.template === 'default'"
      >
        <button class="flex items-center btn btn-default" @click="preview">
          <i class="mr-2 text-base text-gray-500 fad fa-eye"></i>
          Preview
        </button>
      </div>
      <div
        class="p-1"
        v-if="
          (page.title[lang] &&
            page.settings.template === 'default' &&
            page.slug &&
            page.settings.template === 'default') ||
          page.settings.template !== 'default'
        "
      >
        <button class="flex items-center btn btn-primary" @click="savePage">
          <i
            class="mr-2 text-base text-white text-opacity-50  far fa-cloud-upload-alt"
          ></i>
          <template v-if="page.id !== 'create'">Save</template>
          <template v-else>Publish</template>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import bus from "../../../../js/pages/bus.js";

export default {
  props: ["isLoaded", "page", "defaultLang", "settings"],
  components: {},
  computed: {},
  data() {
    return {
      user_roles: window.wp_user_roles,
      moment,
      lang: "",
    };
  },
  methods: {
    isEditable() {
      return !(
        this.page.title[this.lang] === "Home" && this.page.slug === "home"
      );
    },
    preview() {
      bus.$emit("preview");
    },
    savePage() {
      bus.$emit("savePage");
    },
    setLang() {
      bus.$emit("setLang", this.lang);
    },
  },
  mounted() {
    this.lang = this.defaultLang;
  },
};
</script>
