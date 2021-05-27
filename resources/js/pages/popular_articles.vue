<template>
  <div class="featured-section row popular-articles">
    <div class="container">
      <h2 class="carousel-title">Popular Products</h2>
      <div class="featured-products p-relative">
        <carousel
          v-if="last_products.length > 0"
          :items="3"
          :dots="false"
          :nav="false"
          class="w-100"
        >
          <template slot="prev"
            ><span class="prev"> <img src="../icons/chevron-right.svg" /></span
          ></template>
          <template slot="next" v-if="last_products.length > 4"
            ><span class="next"> <img src="../icons/chevron-right.svg" /></span
          ></template>

          <div v-for="item in last_products" :key="item.id" class="all-products m-3">
            <ProductCardWide :item="item"></ProductCardWide>
          </div>
        </carousel>
      </div>
      <div class="col-12 text-center mt-3">
        <button class="btn btn-primary">View All</button>
      </div>
    </div>
  </div>
</template>

<script>
import carousel from "vue-owl-carousel";
import ProductCardWide from "../components/ProductCardWide";

export default {
  name: "NewArriwals",
  components: {
    carousel,
    ProductCardWide,
  },
  data() {
    return {
      last_products: [],
    };
  },
  created() {
    this.getLastProducts();
  },
  methods: {
    getLastProducts() {
      let self = this;
      self.axios.get("/api/products/popularProducts").then((res) => {
        self.last_products = res.data.response;
      });
    },
  },
};
</script>
