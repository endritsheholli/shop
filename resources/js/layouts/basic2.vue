<template>
  <div class="main-layout">
    <SubHeader />
    <AppHeader />
    <div class="app-body">
      <router-view />
    </div>
    <Footer />
    <a id="scroll-top" href="#top" title="Top" role="button">
      <i class="icon-angle-up" />
    </a>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import nav from "../_nav";
import AppHeader from "~/components/Header/AppHeader";
import SubHeader from "~/components/Header/SubHeader";
import Sidebar from "~/components/Sidebar";
import Footer from "~/components/Footer";

export default {
  name: "Basic2Layout",

  components: {
    AppHeader,
    SubHeader,
    Footer
  },
  data: () => ({
    appName: window.config.appName,
    nav: nav.items
  }),
  computed: {
    name() {
      return this.$route.name;
    },
    mapGetters() {
      user: "auth/user";
    }
  }
};
</script>
