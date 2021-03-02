<template>
  <div class="bg-white pages-sidebar" v-if="isLoaded">
    <div v-if="activeCol">
      <Column
        :components="components"
        :activeCol="activeCol"
        :fields="fields"
        :categories="categories"
        :pages="pages"
        :lang="lang"
        :navigation="navigation"
      ></Column>
    </div>

    <div v-if="activeRow">
      <Row :activeRow="activeRow"></Row>
    </div>

    <div v-if="!activeCol && !activeRow">
      <Page
        :page="page"
        :components="components"
        :lang="lang"
        :navigation="navigation"
      ></Page>
    </div>
  </div>
</template>

<script>
import Column from "./Sidebar/Column";
import Row from "./Sidebar/Row";
import Page from "./Sidebar/Page";

export default {
  props: [
    "isLoaded",
    "page",
    "components",
    "categories",
    "activeRow",
    "activeCol",
    "fields",
    "lang",
    "navigation",
    "pages",
  ],
  components: {
    Column,
    Row,
    Page,
  },
  computed: {},
  data() {
    return {};
  },
  methods: {},
  mounted() {},
};
</script>
