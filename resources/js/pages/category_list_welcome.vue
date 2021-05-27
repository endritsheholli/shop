<template>
  <div class="featured-section row last-articles">
    <div class="container">
      <h2 class="carousel-title">Shop by category</h2>
      <div class="featured-cat">
        <!-- <carousel v-if="categories.length" :items="6" :nav="false">
          <div v-for="item in categories" :key="item.id" class="all-category m-1">
            <figure class="product-image-container">
              <router-link
                class="product-image"
                :to="{ name: 'search', params: { id: item.id, name: 0 } }"
              >
                <img
                  :src="'/'+item.image_urls"
                  onerror="this.onerror=null;this.src='http://placehold.jp/800x600.jpg';"
                />
              </router-link>
            </figure>
            <div class="category-details">
              <h4 class="product-title">
                <router-link
                  class="product-image"
                  :to="{ name: 'search', params: { id: item.id, name: 0 } }"
                >{{ item.name }}</router-link>
              </h4>
            </div>
          </div>
        </carousel>-->
        <div v-for="item in categories" :key="item.id" class="cat-content">
          <router-link
            class="product-image"
            :to="{ name: 'search', query: { id: item.id, name: 0 } }"
          >
            <div class="img-parent">
              <img v-if="item.image_urls" :src="item.image_urls" />
              <img
                style="width: 20px"
                v-if="!item.image_urls"
                src="../../img/cat-icon.png"
                alt
              />
            </div>
            <!-- onerror="this.onerror=null;this.src='http://placehold.jp/800x600.jpg';" -->
            <h4 class="product-title">{{ item.name }}</h4>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import carousel from "vue-owl-carousel";

export default {
  name: "LastArticles",
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
