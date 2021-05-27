<template>
  <div class="main-layout">
    <SubHeader />
    <AppHeader />
    <div class="app-body container mt-3 account-settings">
      <h4 class="page-title">Account Settings</h4>
      <main class="main">
        <div class="mb-4" />
        <div class="container-fluid">
          <NavbarPanel :nav-items="nav" class="mx-3 mb-3" />
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import nav from "../_nav";
import AppHeader from "~/components/Header/AppHeader";
import SubHeader from "~/components/Header/SubHeader";
import Sidebar from "~/components/Sidebar";
import NavbarPanel from "~/components/NavbarPanel";
import Footer from "~/components/Footer";

export default {
  name: "MainLayout",

  components: {
    AppHeader,
    SubHeader,
    Sidebar,
    NavbarPanel,
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
