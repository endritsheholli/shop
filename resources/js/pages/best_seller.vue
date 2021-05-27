<template>
  <div>
    <div v-if="best_selling || best_selling.length > 0" class="">
      <div class="container">
        <h2 class="carousel-title">Best Seller</h2>
        <div class="featured-products">
          <div class="row">
            <div
              v-for="item in best_selling"
              :key="item.id"
              class="col-6 col-md-4 px-5"
            >
              <ProductCardSlider :item="item"></ProductCardSlider>
            </div>
          </div>
          <div class="col-12">
            <div class="text-center">
              <button class="btn btn-primary">View more</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProductCardSlider from "../components/ProductCardSlider.vue";

export default {
  name: "BestSeller",
  components: {
    ProductCardSlider,
  },
  data() {
    return {
      best_selling: [],
    };
  },
  created() {
    this.getLastProducts();
  },
  methods: {
    getLastProducts() {
      let self = this;
      self.axios.get("/api/products/bestSellings").then((res) => {
        self.best_selling = res.data;
      });
    },
  },
};
</script>
