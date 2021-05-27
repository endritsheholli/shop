<template>
  <!--
    :class="'paction' +like ?'fa fa-heart heart-wishlist':'add-wishlist'"

  -->
    <!-- :class="[like ? likedClass : unlike]" -->
  <button
    :id="id"
    :title="[like ? unlikedTitle : likedTitle]"
    class="remove-btn-style"
    @click="likeProd()">
    <img v-if="like" src="../icons/like-red.svg">
    <img v-else-if="dark" src="../icons/like.svg">
    <img v-else src="../icons/like-white.svg">
    </button>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Wishlist',
  props: {
    id: {
      type: Number,
      default: 0
    },
    likebool: {
      type: Boolean,
      default: false
    },
    dark: {
      type: Boolean,
      default: false
    }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  data () {
    return {
      like: false,
      likedClass: 'paction fa fa-heart heart-wishlist',
      unlike: 'paction add-wishlist',
      likedTitle: 'Shto te të pëlqyerat',
      unlikedTitle: 'Fshijë nga të pëlqyerat',
      errors: []
    }
  },
  mounted () {
    if (this.likebool) {
      this.like = true
    }
  },

  methods: {
    isLike () {
      this.axios.get(`/api/is/liked/product/` + this.id).then(result => {
        if (result.data.response == true) {
          this.like = true
        }
      })
    },
    likeProd () {
      if (!this.user) {
        this.$bvModal.show('login_modal')
        return
      }
      if (this.like) {
        this.removeProd()
      } else {
        this.addShop()
      }
    },
    addShop () {
      let data = {
        product_id: this.id
      }
      this.axios.post(`/api/likedproducts/create`, data).then(result => {
        if (result.data.status === 'success') {
          this.like = true
        } else {
          if (result.data.status === 'failed') {
            this.like = true
            this.errors = {
              like: result.data.message
            }
            alert(result.data.message)
          }
        }
      })
    },
    removeProd () {
      this.axios.post(`/api/likedproducts/remove/` + this.id).then(result => {
        if (result.data.status === 'success') {
          this.like = false
        }
      })
    }
  }
}
</script>
