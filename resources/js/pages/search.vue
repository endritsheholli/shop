<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="mb-5" />
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-12">
          <aside class="sidebar-shop">
            <div class="sidebar-wrapper">
              <form @submit.prevent="printForm">
                <div class="widget">
                  <h3 class="widget-title">
                    <a
                      data-toggle="collapse"
                      href="#widget-body-1"
                      role="button"
                      aria-expanded="true"
                      aria-controls="widget-body-1"
                      >Kategorite</a
                    >
                  </h3>
                  <div
                    id="widget-body-1"
                    class="collapse show side-custom-menu"
                  >
                    <div class="widget-body side-menu-body">
                      <CategoryList :urlcatid="category_id" ref="cat_list" />
                    </div>
                  </div>
                </div>
                <div class="widget">
                  <h3 class="widget-title">
                    <a
                      data-toggle="collapse"
                      href="#widget-body-2"
                      role="button"
                      aria-expanded="true"
                      aria-controls="widget-body-2"
                      >Çmimi</a
                    >
                  </h3>

                  <div id="widget-body-2" class="collapse show">
                    <div class="position-r">
                      <button @click="clearPrice()" class="clear-btn">
                        Reseto Çmimin
                      </button>
                    </div>
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
                        <div class="filter-price-action">
                          <div class="filter-price-text">
                            <span id="filter-price-range" />
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <div class="widget">
                  <h3 class="widget-title">
                    <a
                      data-toggle="collapse"
                      href="#widget-body-4"
                      role="button"
                      aria-expanded="true"
                      aria-controls="widget-body-4"
                      >Parametrat</a
                    >
                  </h3>
                  <div
                    id="widget-body-1"
                    class="collapse show side-custom-menu"
                  >
                    <div class="widget-body side-menu-body">
                      <BrandList ref="brand_list" :cateId="this.category_id" />
                    </div>
                  </div>
                </div>
                <div class="widget">
                  <input
                    type="submit"
                    value="Kerko"
                    class="btn btn-primary btn-block"
                  />
                </div>
              </form>
            </div>
          </aside>
        </div>
        <div class="col-md-9 col-12">
          <nav class="row toolbox">
            <div class="toolbox-left">
              <div class="toolbox-item toolbox-sort">
                <div class="select-custom">
                  <select
                    name="orderby"
                    class="form-control"
                    @change="orderChange($event)"
                  >
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
                <a href="#" class="sorter-btn" title="Set Ascending Direction">
                  <span class="sr-only">Set Ascending Direction</span>
                </a>
              </div>
            </div>
          </nav>
          <div class="row">
            <div class v-if="$route.params.name !== 0">
              <p>Kerkimet e juaja per: {{ $route.params.name }}</p>
            </div>
          </div>
          <div class="row" v-if="no_result">
            <div
              v-for="item in products"
              :key="item.id"
              class="col-6 col-md-4"
            >
              <!-- mb-3 -->
              <ProductCard
                :item="item"
                @update-slug="updateProductSlug"
              ></ProductCard>
            </div>
          </div>
          <div class="no-res" v-else>
            <div class="searchpage">
              <img src="/img/rocket.png" />
              <h4 class="text-center">Nuk u gjet asnje rezultat</h4>
            </div>
          </div>
          <ProductModal
            :openModal="openModal"
            :slug="product_slug"
          ></ProductModal>

          <!-- End .row -->
          <nav class="toolbox toolbox-pagination" v-show="no_result">
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
      </div>
      <span style="opacity: 0">{{ this.$route.path }}</span>
    </div>
    <div class="mb-5" />
  </main>
</template>

<script src="https://unpkg.com/vuejs-paginate@0.9.0"></script>
<script>
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/default.css";
import Paginate from "vuejs-paginate";
import CategoryList from "../components/ListCaegories.vue";
import ProductCard from "../components/ProductCard.vue";
import ProductModal from "../components/ProductModal.vue";
import BrandList from "../components/BrandList.vue";
import Loader from "~/components/Loader";

export default {
  path: "/category",
  name: "category",
  layout: "nosidebar",

  components: {
    Paginate,
    CategoryList,
    ProductCard,
    ProductModal,
    BrandList,
    VueSlider,
    Loader,
  },
  validate({ params }) {
    return /^\d+$/.test(params.name);
  },
  metaInfo() {
    return { title: this.$t("category") };
  },

  props: {
    id: {
      type: [Number, String],
      default: 0,
    },
    name: {
      type: [Number, String],
      default: 0,
    },
    subcategory: {
      type: [Number, String],
      default: 0,
    },
    from_price: {
      type: [Number, String],
      default: 0,
    },
    to_price: {
      type: [Number, String],
      default: 0,
    },
    parameter: {
      type: [Number, String],
      default: 0,
    },
  },
  // created() {
  //   this.urlData();
  // },
  updated() {
    let query = this.$router.history.current.query;
    if (this.title_name != query.name || this.category_id != query.id) {
      this.title_name = query.name;
      this.category_id = query.id ? parseInt(query.id) : 0;
      this.urlData();

      this.value[0] = 0;
      this.value[1] = 0;
      this.$refs.cat_list.showCategories();
      this.$refs.brand_list.showParameters();
    }
  },
  mounted() {
    this.urlData();
  },
  data() {
    return {
      value: [0, 0],
      totalPage: 1,
      actualPage: 1,
      products: [],
      multiselectCh: [],
      allparameters: [],
      loadingall: true,
      brands: [],
      product_slug: 0,
      order_status: 0,
      openModal: false,
      options: {
        dotSize: 14,
        width: "auto",
        height: 4,
        direction: "ltr",
        min: 0,
        max: 100,
        interval: 1,
        disabled: false,
        clickable: true,
      },
      category_id: 0,
      no_result: true,
    };
  },
  methods: {
    updateProductSlug(e) {
      this.product_slug = e;
      this.openModal = true;
    },
    urlData() {
      let query = this.$router.history.current.query;
      let cat2 = [];
      let cat = query.subcategory ? query.subcategory + "" : "";
      if (cat && cat != 0) {
        cat2 = cat.split(",");
        for (let i = 0; i < cat2.length; i++) {
          cat2[i] = parseInt(cat2[i]);
        }
      }
      let param = [];
      let par = query.params ? query.params + "" : "";
      if (par && par != 0) {
        param = par.split(",");
        for (let y = 0; y < param.length; y++) {
          param[y] = parseInt(param[y]);
        }
      }
      this.title_name = query.name;
      this.category_id = query.id ? parseInt(query.id) : 0;
      this.allparameters = param;
      this.multiselectCh = cat2;
      this.value[0] = query.fromprice ? query.fromprice : 0;
      this.value[1] = query.toprice ? query.toprice : 0;

      this.searchProducts();
    },
    clearPrice() {
      this.value = [0, 0];
    },
    getCatId() {
      this.multiselectCh = this.$refs.cat_list.getMultiselect();
    },
    printForm(e) {
      this.multiselectCh = this.$refs.cat_list.getMultiselect();
      this.allparameters = this.$refs.brand_list.getMultiselect();
      this.searchProducts();
    },
    orderChange($event) {
      this.order_status = $event.target.value;
      this.searchProducts();
    },
    paginate(page) {
      this.actualPage = page;
      this.searchProducts();
    },
    clearSearch() {
      this.multiselectCh = [];
      this.allparameters = [];
    },

    searchProducts() {
      let vm = this;
      vm.loadingall = true;

      let searchO = {
        title: vm.title_name ? vm.title_name : 0,
        category_id: parseInt(this.category_id),
        from_price: this.value[0],
        to_price: this.value[1],
        subcategory: vm.multiselectCh,
        parameter: vm.allparameters,
        order_status: vm.order_status,
      };
      this.$router.push({
        name: "search",
        query: {
          name: searchO.title,
          id: searchO.category_id,
          fromprice: searchO.from_price,
          toprice: searchO.to_price,
          subcategory:
            searchO.subcategory.length > 0 ? searchO.subcategory.join(",") : 0,
          params:
            searchO.parameter.length > 0 ? searchO.parameter.join(",") : 0,
        },
      });
      this.axios
        .post(
          `/api/products/searchProductPaginate?page=` + this.actualPage,
          searchO
        )
        .then((result) => {
          vm.products = result.data.response.result.data;
          vm.totalPage = result.data.response.result.last_page;

          // maxPrice
          vm.options.max = result.data.response.max_price;
          vm.value[1] = result.data.response.max_price;

          if (this.products.length === 0) {
            this.no_result = false;
          } else {
            this.no_result = true;
          }
          vm.loadingall = false;
        });
    },
  },
};
</script>
