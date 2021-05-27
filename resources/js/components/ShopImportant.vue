<template>
  <div class="featured-section row">
    <div v-if="shop.length" class="container">
      <h2 class="carousel-title">
        Dyçanet
        <router-link
          :to="{ name: 'all-shops'}"
          style="float: right;font-weight: 400;font-size: 1.3rem;"
        >Shiko të gjitha</router-link>
      </h2>
      <div class="row">
        <div v-for="item in shop" :key="item.id" class="col-6 col-md-2">
          <div class="shop">
            <div class="shop-img-parent">
              <router-link :to="{ name: 'profile', params: {name: item.shop_name.slug} }">
                <img v-if="item.img_path" :src="item.img_path" />
                <img v-if="!item.img_path" alt src="../../img/noimg.jpg" />
              </router-link>
            </div>
            <div class="shop-title shop-home">
              <router-link
                :to="{ name: 'profile', params: {name: item.shop_name.slug} }"
              >{{ item.shop_name.name }}</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import carousel from "vue-owl-carousel";

export default {
  name: "ShopImportant",
  components: {
    carousel
  },
  data() {
    return {
      shop: []
    };
  },
  created() {
    this.getImportantShop();
  },
  methods: {
    getImportantShop() {
      let self = this;
      self.axios.get("/api/shop/important").then(res => {
        let dataShop = res.data.response;
        self.shop = dataShop.slice(0, 6);
      });
    }
  }
};
</script>
