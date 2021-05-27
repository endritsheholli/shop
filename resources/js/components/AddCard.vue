<template>
  <button
    :id="id"
    :quantity="quantity"
    :comment="comment"
    :type="nativeType"
    :disabled="loading"
    class="remove-btn-style"
    @click="addShop()"
  >
    <!-- :class="{
      [`btn-${type}`]: true,
      'btn-block': block,
      'btn-lg': large,
      'btn-loading': loading
    }" -->
    <slot />
    <snackbar baseSize="1rem" ref="snackbar" :holdTime="3000" :position="position" />
  </button>
</template>

<script>
import { mapGetters } from "vuex";
import Snackbar from "vuejs-snackbar";

export default {
  name: "Shopping",
  components: {
    Snackbar
  },
  props: {
    type: {
      type: String,
      default: "primary"
    },

    nativeType: {
      type: String,
      default: "submit"
    },

    loading: {
      type: Boolean,
      default: false
    },

    block: {
      type: Boolean,
      default: false
    },

    large: {
      type: Boolean,
      default: false
    },
    id: {
      type: Number,
      default: 0
    },
    quantity: {
      type: Number,
      default: 1
    },
    comment: {
      type: String,
      default: ""
    }
  },
  computed: mapGetters({
    user: "auth/user"
  }),
  data() {
    return {
      render: false,
      position: "bottom-left"
    };
  },
  computed: mapGetters({
    user: "auth/user"
  }),
  methods: {
    removeShop(id) {
      if (!this.user) {
        this.$bvModal.show("login_modal");
        return;
      }
      this.axios.post(`/api/shoppingCard/remove/${id}`).then(result => {
        if (result.data.status === "success") {
          this.$store.dispatch("shoping/fetchItems");
          alert("removed");
        }
      });
    },
    addShop() {
      if (!this.user) {
        this.$bvModal.show("login_modal");
        return;
      }
      let data = {
        product_id: this.id,
        quantity: this.quantity,
        comment: this.comment
      };
      this.axios.post(`/api/shoppingCard/create`, data).then(result => {
        if (result.data.status === "success") {
          this.$store.dispatch("shoping/fetchItems");
          this.$refs.snackbar.open("U shtua ne shport");
        } else {
          // this.removeShop(this.id);
          // alert(result.data.message);
          this.$refs.snackbar.open(result.data.message);
        }
      });
    }
  }
};
</script>
