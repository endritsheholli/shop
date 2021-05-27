<template>
  <div>
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="home-top-container">
      <div class>
        <div class="row">
          <div class="col-12 p-0">
            <div class="home-slider">
              <carousel v-if="sliderproducts.length" :items="1" :nav="false">
                <template slot="prev"
                  ><span class="prev slider-prev">
                    <img src="../icons/chevron-right.svg" /></span
                ></template>
                <div
                  v-for="product in sliderproducts"
                  :key="product.id"
                  class="home-slide"
                >
                  <div class="slide-img">
                    <!-- <img
                      v-if="product.image_urls.length !== 0"
                    
                      :src="product.image_urls[0]"
                    />
                    <img v-if="product.image_urls.length == 0" src="../../img/noimg.jpg" /> -->
                    <img src="../../img/slider.png" />
                  </div>
                  <div class="home-slide-content">
                    <div class="container px-5">
                      <h3 class="slider-title">
                        {{ product.title }}
                      </h3>
                      <p class="slider-description">
                        {{ product.description }}
                      </p>
                      <router-link
                        class="btn btn-primary"
                        :to="{ name: 'product', params: { id: product.slug } }"
                      >
                        Shop Now
                      </router-link>
                      <!-- <div v-if="product.price">
                        <span class="product-price">{{ product.price }} &euro;</span>
                        <span
                          v-if="product.previous_price"
                          class="old-price"
                        >{{ product.previous_price }} &euro;</span>
                      </div> -->
                      <!-- <p v-if="product.size" class="d-xm-none">
                        <i class="fas fa-arrows-alt-v"></i>
                        {{ product.size }}
                      </p> -->
                      <!-- <div class="carousel-info my-3">
                        <router-link
                          class="d-xm-none mr-2"
                          :to="{ name: 'search', params: { id: product.cat_id, name: 0 } }"
                        >
                          <i class="icon-tag-line" />
                          {{ product.cat_name }}
                        </router-link>
                        <router-link
                          class="d-xm-none"
                          :to="{ name: 'profile', params: {name: returnSlag(product.shop_name).slug} }"
                        >
                          <i class="icon-bag-2" />
                          {{ product.shop_name ? returnSlag(product.shop_name).name : ""}}
                        </router-link>
                      </div> -->
                    </div>
                    <div class="product-action d-xm-none mt-3">
                      <Shopping
                        :id="product.id"
                        :type="'button'"
                        :class="'btn-bag-sm'"
                        ref="prodBag"
                      >
                        <i class="fa fa-cart-plus" /> Shto në shportë
                      </Shopping>
                      <Wishlist
                        :id="product.id"
                        style="height: 44px"
                        :likebool="product.is_like"
                        title="Shto tek te pelqyerat"
                      />
                    </div>
                  </div>
                </div>
                <template slot="next"
                  ><span class="next slider-next">
                    <img src="../icons/chevron-right.svg" /></span
                ></template>
              </carousel>
            </div>
            <snackbar
              baseSize="1rem"
              ref="snackbar"
              :holdTime="3000"
              :position="position"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mb-3">
        <div class="col-12 col-md-7">
          <div class="product-card">
            <!-- style="background-image: url('../img/shoes.png')" -->
            <h2 class="product-card-title">Adidas shoes</h2>
            <p class="product-card-price">
              <Strong>89.00 &euro;</Strong>
              <span><strike>149.00 &euro;</strike></span>
            </p>
          </div>
        </div>
        <div class="col-12 col-md-5">
          <div class="product-card">
            <!-- style="background-image: url('../../img/shoes.png')" -->
            <h2 class="product-card-title">Nike Spray</h2>
            <a href="#" class="btn btn-primary"> View More </a>
          </div>
        </div>
      </div>
      <div class="row">
        <CategoryListWelcome />
      </div>
      <!-- <div class="row">
        <div v-for="item in products" :key="item.id" class="col-6 col-md-2 p-1">
          <ProductCard :item="item"></ProductCard>
        </div>
      </div> -->
      <!-- <nav class="toolbox toolbox-pagination">
        <paginate
          :page-count="totalPage"
          :click-handler="paginate"
          :prev-text="'Prev'"
          :next-text="'Next'"
          :container-class="'pagination'"
        />
      </nav>-->
      <div class="row">
        <BestSeller />
      </div>
    </div>

    <NewWelcomeCategory />
    <div v-if="prodBag" class="alert alert-danger" role="alert">
      Product already in the bag!
    </div>
    <!-- <ShopImportant /> -->
    <div class="container">
      <NewArriwals />
    </div>
    <div class="container-fluid p-0 mb-5">
      <Brands />
    </div>
    <div class="container mb-5">
      <PopularProducts />
    </div>
  </div>
</template>

<script src="https://unpkg.com/vuejs-paginate@0.9.0"></script>
<script>
import Brands from "./brands.vue";
import BestSeller from "./best_seller.vue";
import NewArriwals from "./last_articles.vue";
import PopularProducts from "./popular_articles.vue";
import NewWelcomeCategory from "../components/NewWelcomeCategory";
import CategoryListWelcome from "./category_list_welcome.vue";
import CategoryList from "../components/ListCaegories.vue";
import ShopImportant from "../components/ShopImportant.vue";
import ProductCard from "../components/ProductCard.vue";
import carousel from "vue-owl-carousel";
import Paginate from "vuejs-paginate";
import Snackbar from "vuejs-snackbar";
import Loader from "~/components/Loader";

export default {
  layout: "basic",
  components: {
    ShopImportant,
    Brands,
    BestSeller,
    CategoryList,
    CategoryListWelcome,
    Paginate,
    carousel,
    Snackbar,
    NewWelcomeCategory,
    Loader,
    NewArriwals,
    PopularProducts,
    ProductCard,
  },
  metaInfo() {
    return {
      title: this.$t("home"),
    };
  },
  data() {
    return {
      showByvalue: null,
      loadingall: true,
      totalPage: 1,
      actualPage: 1,
      changed: false,
      updated: false,
      products: [],
      sliderproducts: [],
      prodBag: false,
      position: "bottom-left",
    };
  },
  mounted: function () {
    this.getSliderProducts();
    this.searchProducts();
  },

  methods: {
    imgUrlAlt(event) {
      event.target.src = "../../img/market.jpg";
    },
    returnSlag(item) {
      let slug = JSON.parse(item);
      return slug;
    },
    paginate(page) {
      this.actualPage = page;
      this.searchProducts();
    },
    openscn() {
      this.$refs.snackbar.open("Snackbar test");
    },
    searchProducts() {
      let vm = this;
      vm.loadingall = true;
      if (name === "") {
        name = 0;
      }

      let searchO = {
        title: name ? name : 0,
        category_id: 0,
        from_price: 0,
        to_price: 100,
        subcategory: 0,
        order_status: vm.order_status,
      };
      this.axios
        .post(
          `/api/products/searchProductPaginate?page=` +
            this.actualPage +
            `&order_status=` +
            5
        )
        .then((result) => {
          vm.products = result.data.response.result.data;
          vm.totalPage = result.data.response.result.last_page;
          vm.loadingall = false;
        });
    },
    getSliderProducts() {
      let self = this;
      self.axios.get("/api/products/importantProducts").then((res) => {
        self.sliderproducts = res.data.response;
        self.loadingall = false;
      });
    },
    addShop(id) {
      let data = {
        product_id: id,
      };
      this.axios.post(`/api/shoppingCard/create`, data).then((result) => {
        if (result.data.status === "success") {
        }
      });
    },
  },
};
</script>
