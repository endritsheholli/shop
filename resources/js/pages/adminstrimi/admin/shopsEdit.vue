<template>
  <main class="main">
    <div class="mb-4"></div>
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="container">
      <div class="col-12">
        <h2 class="sub-title">Konfigurimi i Ballines</h2>
      </div>
      <SliderConfig class="config-home my-5" />
      <ShopByCategory class="config-home my-5" />
      <BestSeller class="config-home my-5" />
      <NewArriwals class="config-home my-5" />
      <Brands class="config-home my-5" />
      <PopularProducts class="config-home my-5" />
      <!-- // shops // -->
      <div class="config-home my-5">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="sub-title">Të gjitha dyçanet</h2>
                <div class="checkbox">
                  <input type="checkbox" checked value="" /><label
                    >Active</label
                  >
                </div>
              </div>
              <div class="row" v-if="this.shops">
                <div
                  v-for="(item, index) in shops"
                  :key="index"
                  class="col-6 col-md-2"
                >
                  <div class="shop">
                    <div class="shop-img-parent">
                      <router-link
                        :to="{
                          name: 'profile',
                          params: { name: item.shop_name.slug },
                        }"
                      >
                        <img v-if="item.img_path" :src="item.img_path" />
                        <img
                          v-if="!item.img_path"
                          alt
                          src="../../../../img/noimg.jpg"
                        />
                      </router-link>
                    </div>
                    <div class="shop-title">
                      <router-link
                        :to="{
                          name: 'profile',
                          params: { name: item.shop_name.slug },
                        }"
                        >{{ item.shop_name.name }}</router-link
                      >
                      {{ item.important_shop }}
                      <button
                        @click="toggleShop(item.id, !item.important_shop)"
                        class="btn-small"
                        style="backround-color: #a9a9a9"
                        :style="
                          item.important_shop
                            ? 'background-color:#c25151;'
                            : 'background-color:#65b891'
                        "
                      >
                        {{
                          item.important_shop
                            ? "Largoje nga Ballina"
                            : "Shfaqe ne ballin"
                        }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import Loader from "~/components/Loader";
import SliderConfig from "../../../components/HomeConfig/SliderConfig";
import ShopByCategory from "../../../components/HomeConfig/ShopByCategory";
import BestSeller from "../../../components/HomeConfig/BestSeller";
import Brands from "../../../components/HomeConfig/Brands";
import NewArriwals from "../../../components/HomeConfig/NewArriwals";
import PopularProducts from "../../../components/HomeConfig/PopularProducts";
import swal from "sweetalert2";
export default {
  components: {
    Loader,
    SliderConfig,
    ShopByCategory,
    BestSeller,
    Brands,
    NewArriwals,
    PopularProducts,
  },

  data() {
    return {
      appName: window.config.appName,
      shops: [],
      totalPage: 1,
      actualPage: 1,
      loadingall: false,
    };
  },
  mounted() {
    this.getShops();
  },
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function () {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    getShops() {
      let self = this;
      self.loading = true;
      self.axios.get("/api/shop?page=" + self.actualPage).then((res) => {
        self.shops = res.data.response.data;
        self.totalPage = res.data.response.last_page;
        self.loadingall = false;
      });
    },
    toggleShop(userid, status) {
      let self = this;
      let data = {
        id: userid,
        status: status,
      };
      self.loading = true;
      self.axios.post("/api/add/shop/important", data).then((res) => {
        swal({
          type: "success",
          text: "Statusi u ndryshua",
          reverseButtons: true,
          confirmButtonText: "ok",
        });
      });
      self.getShops();
      self.loading = false;
    },
  },
};
</script>
