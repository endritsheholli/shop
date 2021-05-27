<template>
  <header class="sub-header">
    <nav class="navbar navbar-expand-lg">
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">New Arriwals</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Discount</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Duaje tenden</a>
          </li>
        </ul>
        <div class="second-sub-nav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a href="" class="nav-link" disabled
                >Free Shipping For All Orders Of 50.00 €</a
              >
            </li>

            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                EUR
                <img src="../../icons/chevron-down.svg" />
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                {{ langsName[lang] }}
                <img src="../../icons/chevron-down.svg" />
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a
                  class="dropdown-item"
                  href="#"
                  v-for="(index, ln) in langs"
                  :key="index"
                  @click="changeLang(ln)"
                  >{{ langsName[ln] }}</a
                >
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "SubHeader",
  components: {},

  data() {
    return {
      appName: window.config.appName,
      langsName: {
        en: "English",
        sq: "Albanian",
        de: "Deutche",
      },
    };
  },
  created() {},
  mounted: function () {},
  computed: mapGetters({
    langs: "lang/locales",
    lang: "lang/locale",
  }),
  methods: {
   async changeLang(ln) {

      console.log(ln, 'po')
      await this.$store.dispatch("lang/setLocale", {locale: ln});
    },
  },
};
</script>
<style>
</style>
