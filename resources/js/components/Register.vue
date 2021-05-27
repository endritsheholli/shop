<template>
  <b-modal id="register_modal" ref="register_modal"  hide-footer>
    <Register />
  </b-modal>
</template>

<script>
import Form from 'vform'
import Register from '~/pages/auth/register.vue'
export default {
  middleware: 'guest',
  layout: 'basic',

  components: {
    Register
  },

  metaInfo () {
    return {
      title: this.$t('Register')
    }
  },

  data: () => ({}),

  methods: {

  }
}
</script>
