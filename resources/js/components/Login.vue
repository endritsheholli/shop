<template>
  <b-modal id="login_modal" ref="login_modal" hide-footer>
    <Login />
  </b-modal>
</template>

<script>
import Form from 'vform'
import Login from '~/pages/auth/login.vue'
export default {
  middleware: 'guest',
  layout: 'basic',

  components: {
    Login
  },

  metaInfo () {
    return {
      title: this.$t('login')
    }
  },

  data: () => ({}),

  methods: {

  }
}
</script>
