<template>
  <div class="main-layout">
    <SubHeader />
    <AppHeader />
    <div class="app-body">
      <main class="main">
        <div class="container-fluid">
          <router-view />
        </div>
      </main>
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
import SubHeader from "~/components/Header/SubHeader";
import AppHeader from "~/components/Header/AppHeader";
import Sidebar from "~/components/Sidebar";
import Footer from "~/components/Footer";

export default {
  name: "BasicLayout",

  components: {
    AppHeader,
    SubHeader,
    Footer,
  },
  data: () => ({
    appName: window.config.appName,
    nav: nav.items,
  }),
  computed: {
    name() {
      return this.$route.name;
    },
    mapGetters() {
      user: "auth/user";
    },
  },
};
</script>
