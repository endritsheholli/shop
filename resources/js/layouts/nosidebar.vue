<template>
  <div class="main-layout">
    <AppHeader />
    <div class="app-body">
      <main class="main">
        <div class="container-fluid">
          <router-view />
        </div>
      </main>
    </div>
    <Footer />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import AppHeader from '~/components/Header/AppHeader'
import Footer from '~/components/Footer'

export default {
  name: 'NoSidebarLayout',

  components: {
    AppHeader,
    Footer
  },
  data: () => ({
    appName: window.config.appName
  }),
  computed: mapGetters({
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched
    },
    user: 'auth/user'
  })
}
</script>
