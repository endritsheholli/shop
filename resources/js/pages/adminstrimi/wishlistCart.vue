<template>
  <main class="main">
    <div class="container">
      <!-- <div v-if="products.length == 0" class="product-not-like">
        <strong>Nuk keni produkte te pëlqyera</strong>
      </div> -->
      <div class="row row-sm">
        <div
          v-for="(item, index) in products"
          :key="item.id"
          class="col-6 col-md-4 col-lg-3 mb-1"
        >
          <div class="product all-products">
            <figure class="product-image-container">
              <a :href="'/product/' + item.product.slug" class="product-image">
                <img
                  v-if="item.product.image_urls.length !== 0"
                  alt="product"
                  :src="item.product.image_urls[0]"
                />
                <img
                  v-if="item.product.image_urls.length == 0"
                  src="../../../img/noimg.jpg"
                />
              </a>
            </figure>
            <div class="product-details wishlist-product">
              <h2 class="product-title">
                <a :href="'/product/' + item.product.slug">{{
                  item.product.title
                }}</a>
              </h2>
              <div class="price-box">
                <span class="product-price"
                  >{{ item.product.price }} &euro;</span
                >
              </div>
              <div class="product-action">
                <Shopping
                  :id="item.product.id"
                  :type="'button'"
                  :class="'btn-bag-sm'"
                >
                  <i class="fa fa-cart-plus" />
                  <span class="shto-nshport">Shto në shportë</span>
                </Shopping>
                <!-- <button
                  title="Fshijë nga të pëlqyerat"
                  class="paction fa fa-heart heart-wishlist"
                  @click="removeWishlist(item.product.id, index)"
                /> -->
                <a
                  title="Remove Product"
                  @click.prevent="removeWishlist(item.product.id, index)"
                >
                  <img src="../../icons/like-fill.svg" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
<script>
import vZoom from "vue-zoom";
import axios from "axios";
import swal from "sweetalert2";

export default {
  layout: "nosidebar",
  components: { vZoom },
  data() {
    return {
      products: [],
    };
  },
  created() {
    this.getWishlist();
  },
  methods: {
    getWishlist() {
      let vm = this;
      axios.get(`/api/likedproducts/get-my-product`).then((result) => {
        if (result.data.status === "success") {
          vm.products = result.data.response;
          console.log("wishlist", vm.products);
        } else {
          swal({
            type: "error",
            title: i18n.t("error_alert_title"),
            text: result.data.message,
            reverseButtons: true,
            confirmButtonText: i18n.t("ok"),
          });
          this.loadingall = false;
        }
      });
    },
    removeWishlist(id, index) {
      this.axios.post(`/api/likedproducts/remove/${id}`).then((result) => {
        if (result.data.status === "success") {
          this.products.splice(index, 1);
        }
      });
    },
  },
};
</script>
