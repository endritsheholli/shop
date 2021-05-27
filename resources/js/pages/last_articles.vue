<template>
  <div class="featured-section row last-articles">
    <div class="container">
      <h2 class="carousel-title">New Arrivals</h2>
      <div class="featured-products p-relative">
        <carousel v-if="last_article.length > 0" :items="4" :dots="false" :nav="false"  class="w-100">
          <template slot="prev"
            ><span class="prev"> <img src="../icons/chevron-right.svg" /></span
          ></template>
          <template slot="next" v-if="last_article.length > 4"
            ><span class="next"> <img src="../icons/chevron-right.svg" /></span
          ></template>

          <div
            v-for="item in last_article"
            :key="item.id"
            class="all-products"
          >
            <ProductCard :item="item"></ProductCard>
          </div>
        </carousel>
      </div>
      <div class="col-12 text-center mt-3">
        <button class="btn btn-primary">
          View All
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import carousel from "vue-owl-carousel";
import ProductCard from "../components/ProductCard";

export default {
  name: "NewArriwals",
  components: {
    carousel,
    ProductCard,
  },
  data() {
    return {
      last_article: [],
    };
  },
  created() {
    this.getLastProducts();
  },
  methods: {
    getLastProducts() {
      let self = this;
      self.axios.get("/api/products/lastProduct").then((res) => {
        self.last_article = res.data.response;
      });
    },
  },
};
</script>
