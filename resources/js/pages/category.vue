<template>
  <main class="main">
    <div class="container">
      <div class="row">
        <div class="col-lg-9">
          <nav class="toolbox">
            <div class="toolbox-left">
              <div class="toolbox-item toolbox-sort">
                <div class="select-custom">
                  <select name="orderby" class="form-control">
                    <option value="0" selected="selected">
                      Rendit ne baz te dates me te re
                    </option>
                    <option value="3">
                      Rendit ne baz te dates me te vjeter
                    </option>
                    <option value="2">
                      Rendit ne baze te cmimit (nga me i uleti)
                    </option>
                    <option value="1">
                      Rendit ne baze te cmimit (nga me i larti)
                    </option>
                  </select>
                </div>
                <!-- End .select-custom -->
                <a href="#" class="sorter-btn" title="Set Ascending Direction">
                  <span class="sr-only">Set Ascending Direction</span>
                </a>
              </div>
              <!-- End .toolbox-item -->
            </div>
            <!-- End .toolbox-left -->
            <div class="toolbox-item toolbox-show" />
          </nav>
          <div class="row row-sm">
            <div v-for="product in products" :key="product.id" class="col-6 col-md-4">
              <div class="product all-products">
                <figure class="product-image-container">
                  <a :href="'/product/' + product.slug" class="product-image">
                     <img
                      v-if="product.image_urls.length !== 0"
                    
                      :src="product.image_urls[0]"
                    />
                    <img v-if="product.image_urls.length == 0" src="../../img/noimg.jpg" />
                  </a>
                </figure>
                <div class="product-details">
                  <h2 class="product-title">
                    <a :href="'/product/' + product.slug">{{ product.title }}</a>
                  </h2>
                  <div class="price-box">
                    <span v-if="product.previous_price" class="product-price prev-price">
                      {{ product.price }} &euro;
                      <strike>{{ product.previous_price }} &euro;</strike>
                    </span>
                    <span v-else class="product-price">{{ product.price }} &euro;</span>
                  </div>
                  <div class="product-action">
                    <Shopping :id="product.id" :type="'button'" :class="'btn-bag'">
                      <i class="fa fa-cart-plus" /> Shto në shportë
                    </Shopping>
                    <Wishlist :id="product.id" :likebool="product.is_like" />
                  </div>
                </div>
              </div>
            </div>
            <!-- End .col-md-4 -->
          </div>
          <!-- End .row -->
          <nav class="toolbox toolbox-pagination">
            <paginate
              :page-count="totalPage"
              :click-handler="paginate"
              :prev-text="'Prev'"
              :next-text="'Next'"
              :container-class="'pagination'"
            />
          </nav>
        </div>
      </div>
    </div>
    <div class="mb-5" />
  </main>
</template>

<script src="https://unpkg.com/vuejs-paginate@0.9.0"></script>
<script>
import Paginate from "vuejs-paginate";

export default {
  path: "/category",
  name: "category",
  layout: "nosidebar",

  components: {
    Paginate,
    VueSlider,
  },

  metaInfo() {
    return { title: this.$t("category") };
  },

  data() {
    return {
      brands: [],
      products: [],
      totalPage: 1,
      actualPage: 1,
      value: [100, 900],
      options: {
        dotSize: 14,
        width: "auto",
        height: 4,
        direction: "ltr",
        min: 0,
        max: 1000,
        interval: 1,
        disabled: false,
        clickable: true
      }
    };
  },
  mounted: function() {
    this.getBrands(1);
    this.getAllProducts();
  },
  methods: {
    getBrands(id) {
      let self = this;
      self.axios.get("/api/parameters/" + id).then(res => {
        const { data } = res;
        self.brands = data.response;
      });
    },
    paginate(page) {
      this.actualPage = page;
      this.getAllProducts();
    },

    getAllProducts() {
      let self = this;
      self.axios
        .get(
          "/api/products/getAllProductsWithPagination?page=" + self.actualPage
        )
        .then(res => {
          const { data } = res;
          self.products = data.response.data;
          self.totalPage = data.response.last_page;
        });
    }
  }
};
</script>
