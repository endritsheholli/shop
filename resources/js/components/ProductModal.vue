<template>
  <div>
    <b-modal
      ref="product_modal"
      :modal-class="`product-modal`"
      size="lg"
      hide-footer
      hide-header
    >
      <div class="">
        <!-- <div v-if="product.length == 0" style="min-height: 350px">
          loading data
        </div> -->
        <button class="remove-modal" @click="exitModal">
          <img src="../icons/times.svg" width="40" alt="" />
        </button>
        <div>
          <div class="row">
            <div class="col-5 product-single-gallery">
              <div
                id="carousel-thumb"
                class="carousel slide carousel-fade carousel-thumbnails product-carousel"
                data-ride="carousel"
              >
                <div class="carousel-inner" role="listbox">
                  <div
                    :class="
                      index == 0 ? 'carousel-item active' : 'carousel-item'
                    "
                    v-for="(image, index) in product.image_urls"
                    :key="index"
                  >
                    <v-zoom
                      v-if="product.image_urls.length !== 0"
                      :img="image"
                      width="100%"
                      class="product-single-image d-block w-100"
                      :src="product.image_urls[0]"
                    />

                    <v-zoom
                      v-else
                      src="../../img/noimg.jpg"
                      :img="image"
                      width="100%"
                      class="product-single-image d-block w-100"
                    />
                  </div>
                </div>
                <ol class="carousel-indicators">
                  <li
                    v-for="(image, index) in product.image_urls"
                    :key="index"
                    data-target="#carousel-thumb"
                    :data-slide-to="index"
                    class="active"
                  >
                    <img
                      v-if="product.image_urls.length !== 0"
                      width="100"
                      :src="product.image_urls[index]"
                    />
                    <img
                      v-if="product.image_urls.length == 0"
                      src="../../img/noimg.jpg"
                    />
                  </li>
                </ol>
              </div>
            </div>
            <div class="col-7">
              <div class="modal-product-info">
                <p class="pm-title">
                  {{ product.title }}
                </p>
                <Rating :ratingNumber="4.5" style="margin: 0.5rem 0" />
                <p class="pm-price">{{ product.price }} &euro;</p>
                <p class="pm-desc">
                  {{ product.description }}
                </p>
                <p class="pm-seller">
                  Added by:
                  <span>{{
                    seller ? (seller.name ? seller.name : "--") : "--"
                  }}</span>
                </p>
                <hr class="hr-line" />
                <div class="row">
                  <div class="col-6">
                    <div class="select-custom">
                      <p class="label-title">Color</p>
                      <select
                        name="orderby"
                        class="form-control"
                        v-model="color"
                        @change="changeColor($event)"
                      >
                        <option value="0">Black</option>
                        <option value="1">Green</option>
                        <option value="2">Yellow</option>
                        <option value="3">Red</option>
                        <option value="4">Blue</option>
                      </select>
                    </div>
                    <div class="d-flex quantity-content">
                      <p class="label-title">Qty</p>
                      <p class="count-input">
                        {{ quantity }}

                        <button
                          class="btn count-btn plus"
                          @click="addQuantity()"
                        >
                          +
                        </button>
                        <button
                          class="btn count-btn minus"
                          @click="removeQuantity()"
                        >
                          -
                        </button>
                      </p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="action-button-content">
                      <Shopping
                        :id="product.id"
                        :type="'button'"
                        class="prod-icon bag-text"
                      >
                        <img src="../icons/bag-white.svg" />
                        <span>Add to bag</span>
                      </Shopping>
                      <Wishlist
                        :id="product.id"
                        :likebool="product.is_like"
                        :dark="dark"
                        class="prod-icon white-bg"
                      />
                    </div>
                    <div class="d-flex p-3">
                      <button class="btn btn-primary w-100">Buyout</button>
                    </div>
                  </div>
                </div>
                <hr class="hr-line" />
                <div class="d-flex justify-content-between align-item-center">
                  <div>
                    <router-link
                      :to="{ name: 'product', params: { id: product.slug } }"
                      class="remove-btn-style"
                    >
                      View specifications
                    </router-link>
                  </div>
                  <div class="d-flex">
                    <span class="mr-2">Share</span>
                    <div>
                      <i class="fa fa-facebook" />
                      <i class="fa fa-instagram" />
                      <i class="fa fa-twitter" />
                      <i class="fa fa-whatsapp" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import Rating from "./Rating.vue";
import carousel from "vue-owl-carousel";
import Loader from "~/components/Loader";
import vZoom from "vue-zoom";

export default {
  props: {
    slug: {
      default: "",
    },
    openModal: {
      default: false,
    },
  },
  components: {
    Rating,
    carousel,
    Loader,
    vZoom,
  },
  data() {
    return {
      product: [],
      seller: "",
      color: 0,
      quantity: 1,
      dark: true,
    };
  },
  mounted: function () {},
  watch: {
    slug: {
      handler: function (val1, oldVal1) {
        console.log("new slug value" + val1 + "old " + oldVal1);
        this.getProductBySlug(val1);
      },
    },
  },
  methods: {
    exitModal() {
      this.slug = "";
      this.$refs.product_modal.hide();
    },
    changeColor($event) {
      this.color = $event.target.value;
      this.searchProducts();
    },
    addQuantity() {
      if (this.quantity < this.product.quantity) {
        this.quantity = this.quantity + 1;
      }
      if (this.quantity === this.product.quantity) {
        alert("Nuk ka me produkte ne stock!");
      }
    },
    removeQuantity() {
      if (this.quantity > 1) {
        this.quantity = this.quantity - 1;
      }
    },
    getProductBySlug(id) {
      if (id !== "") {
        let self = this;
        self.product = [];
        self.$refs.product_modal.show();
        let url = "/api/products/getBySlug/" + id;
        self.axios.get(url).then((res) => {
          self.product = res.data.response;
          self.seller = JSON.parse(self.product.shop_name);
          console.log("product.shop_name", self.product.shop_name);
          console.log("seller", self.seller);
          console.log("product => ", self.product, self.slug);
        });
      } else {
        console.log("getProductBySlug", id);
      }
    },
  },
};
</script>
