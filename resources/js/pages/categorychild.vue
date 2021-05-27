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
            <!-- End .toolbox-item -->
            <!-- <div class="layout-modes">
              <a href="category.html" class="layout-btn btn-grid active" title="Grid">
                <i class="icon-mode-grid"></i>
              </a>
              <a href="category-list.html" class="layout-btn btn-list" title="List">
                <i class="icon-mode-list"></i>
              </a>
            </div>-->
            <!-- End .layout-modes -->
          </nav>
          <div class="row row-sm">
            <div v-for="product in products" :key="product.product_id" class="col-6 col-md-4">
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
                    <span v-if="product.previous_price" class="product-price">
                      {{ product.price }} &euro;
                      <strike>{{ product.previous_price }} &euro;</strike>
                    </span>
                    <span v-else class="product-price">{{ product.price }} &euro;</span>
                  </div>
                  <div class="product-action">
                    <Shopping :id="product.id" :type="'button'" :class="'btn-bag'">
                      <i class="fa fa-cart-plus" /> Shto në shportë
                    </Shopping>
                    <!-- <Wishlist
                      :type="'button'"
                      :class="'paction add-wishlist'"
                      :id="product.product_id"
                    >Shto te të pëlqyerat</Wishlist>-->

                    <Wishlist :id="product.product_id" :likebool="product.is_like" />
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
        <!-- End .col-lg-9 -->
        <aside class="sidebar-shop col-lg-3 order-lg-first">
          <div class="sidebar-wrapper">
            <div class="widget">
              <h3 class="widget-title">
                <a
                  data-toggle="collapse"
                  href="#widget-body-1"
                  role="button"
                  aria-expanded="true"
                  aria-controls="widget-body-1"
                >Kategorite</a>
              </h3>
              <div id="widget-body-1" class="collapse show side-custom-menu">
                <div class="widget-body side-menu-body">
                  <ul>
                    <li
                      v-for="item in subcategories ? subcategories.children : []"
                      :key="item.id"
                    >
                      <router-link
                        :to="{ name: 'category', params: {name: item.slug, id: item.id } }"
                      >
                        <i class="icon-tag-line" />
                        {{ item.name }}
                      </router-link>
                    </li>
                  </ul>
                </div>
                <!-- End .widget-body -->
              </div>
              <!-- End .collapse -->
            </div>
            <!-- End .widget -->
            <div class="widget">
              <h3 class="widget-title">
                <a
                  data-toggle="collapse"
                  href="#widget-body-2"
                  role="button"
                  aria-expanded="true"
                  aria-controls="widget-body-2"
                >Çmimi</a>
              </h3>

              <div id="widget-body-2" class="collapse show">
                <div class="widget-body">
                  <form action="#">
                    <div class="price-slider-wrapper">
                      <vue-slider
                        ref="slider"
                        v-model="value"
                        v-bind="options"
                        :enable-cross="false"
                      />
                    </div>
                    <!-- End .price-slider-wrapper -->
                    <div class="filter-price-action">
                      <div class="filter-price-text">
                        <span id="filter-price-range" />
                      </div>
                      <!-- End .filter-price-text -->
                    </div>
                    <!-- End .filter-price-action -->
                  </form>
                </div>
                <!-- End .widget-body -->
              </div>
              <!-- End .collapse -->
            </div>
            <!-- End .widget -->

            <div class="widget">
              <h3 class="widget-title">
                <a
                  data-toggle="collapse"
                  href="#widget-body-4"
                  role="button"
                  aria-expanded="true"
                  aria-controls="widget-body-4"
                >Brendet</a>
              </h3>

              <div id="widget-body-4" class="collapse show">
                <div class="widget-body">
                  <ul class="cat-list">
                    <li v-for="brand in brands.child" :key="brand.id">
                      <a href="#">
                        {{ brand.name }}
                        <span>{{ brands.length }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
                <!-- End .widget-body -->
              </div>
              <!-- End .collapse -->
            </div>
            <!-- End .widget -->
            <div class="widget">
              <h3 class="widget-title">
                <a
                  data-toggle="collapse"
                  href="#widget-body-6"
                  role="button"
                  aria-expanded="true"
                  aria-controls="widget-body-6"
                >Ngjyra</a>
              </h3>

              <div id="widget-body-6" class="collapse show">
                <div class="widget-body">
                  <ul class="config-swatch-list">
                    <li>
                      <a href="#" style="background-color: #4090d5;" />
                    </li>
                    <li class="active">
                      <a href="#" style="background-color: #f5494a;" />
                    </li>
                    <li>
                      <a href="#" style="background-color: #fca309;" />
                    </li>
                    <li>
                      <a href="#" style="background-color: #11426b;" />
                    </li>
                    <li>
                      <a href="#" style="background-color: #f0f0f0;" />
                    </li>
                    <li>
                      <a href="#" style="background-color: #3fd5c9;" />
                    </li>
                    <li>
                      <a href="#" style="background-color: #979c1c;" />
                    </li>
                    <li>
                      <a href="#" style="background-color: #7d5a3c;" />
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
    <div class="mb-5" />
  </main>
</template>

<script src="https://unpkg.com/vuejs-paginate@0.9.0"></script>
<script>
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/default.css";
import Paginate from "vuejs-paginate";

export default {
  path: "/category",
  name: "category",
  layout: "nosidebar",

  components: {
    Paginate,
    VueSlider
  },

  metaInfo() {
    return { title: this.$t("category") };
  },

  data() {
    return {
      mainCatSlug: this.$route.params.name,
      brands: [],
      totalPage: 1,
      actualPage: 1,
      subcategories: [],
      products: [],
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
    this.getAllProducts(this.mainCatSlug);
    this.getSubCategories(this.mainCatSlug);
  },
  methods: {
    paginate(page) {
      this.actualPage = page;
      this.getAllProducts(this.mainCatSlug);
    },
    getBrands(id) {
      let self = this;
      self.axios.get("/api/parameters/" + id).then(res => {
        const { data } = res;
        self.brands = data.response;
      });
    },
    getSubCategories(id) {
      let self = this;
      self.axios.get("/api/categories/getBySlug/" + id).then(res => {
        const { data } = res;
        self.subcategories = data.response;
      });
    },
    getAllProducts(slug) {
      let self = this;
      self.axios
        .get(
          "/api/products/getProductsByCategorySlug/" +
            slug +
            "?page=" +
            self.actualPage
        )
        .then(res => {
          const { data } = res;
          // self.products = data.response;
          self.products = data.response.data;
          self.totalPage = data.response.last_page;
        });
    }
  }
};
</script>
