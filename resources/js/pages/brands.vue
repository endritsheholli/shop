<template>
  <div class="featured-section row brands my-4">
    <div class="container">
      <h2 class="carousel-title">Brands</h2>
      <div class="featured-cat p-relative">
        <carousel
          v-if="categories.length"
          :items="4"
          :nav="false"
          class="w-100"
        >
          <template slot="prev"
            ><span class="prev"> <img src="../icons/chevron-right.svg" /></span
          ></template>
          <template slot="next" v-if="categories.length > 4"
            ><span class="next"> <img src="../icons/chevron-right.svg" /></span
          ></template>

          <div
            v-for="item in categories"
            :key="item.id"
            class="all-brands"
          >
            <div class="brand-content">
              {{ item.name }}
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
  name: "brands",
  components: {
    carousel,
  },
  data() {
    return {
      categories: [],
    };
  },
  created() {
    this.getCategorie();
  },
  methods: {
    getCategorie() {
      let self = this;
      self.axios.get("/api/categories/getParents").then((res) => {
        self.categories = res.data.response;
      });
    },
  },
};
</script>
