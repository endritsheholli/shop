<template>
  <div class="featured-section row">
    <div class="container">
      <h2 class="carousel-title">Artikujt relevant</h2>
      <div class="featured-products">
        <carousel v-if="product.length" :items="6" :nav="false">
          <div v-for="item in product" :key="item.id" class="all-products m-1">
            <figure class="product-image-container">
              <router-link
                class="product-image"
                :to="{ name: 'product', params: { id: item.slug } }"
              >
                <img
                  v-if="item.image_urls.length > 0"
                  :style="{ height: '23px;', width:'20px;' }"
                  :src="item.image_urls[0]"
                />
                <img v-if="!item.image_urls.length > 0" src="../../img/noimg.jpg" />
              </router-link>
            </figure>
            <div class="product-details">
              <h4 class="product-title">
                <router-link
                  class="product-image"
                  :to="{ name: 'product', params: { id: item.slug } }"
                >{{ item.title }}</router-link>
              </h4>
              <div class="price-box">
                <span class="product-price">{{ item.price }} &euro;</span>
              </div>
              <div class="product-action">
                <Shopping :id="item.id" :type="'button'" :class="'btn-bag btn-bag-sm'">
                  <i class="fa fa-cart-plus" />
                   <span class="shto-nshport">Shto në shportë</span>
                </Shopping>
                <Wishlist :id="item.id" :likebool="item.is_like" />
              </div>
            </div>
          </div>
        </carousel>
      </div>
    </div>
  </div>
</template>

<script>
import carousel from "vue-owl-carousel";

export default {
  name: "RelatedArticles",
  components: { carousel },

  props: {
    id: {
      type: Number,
      default: 0
    },
    catId: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      product: []
    };
  },
  mounted: function() {
    this.getrelated();
  },
  methods: {
    getrelated() {
      this.axios
        .get("/api/products/relatedProducts/" + this.catId + "/" + this.id)
        .then(res => {
          this.product = res.data.response;
        });
    }
  }
};
</script>
