<template>
  <main class="main">
    <div class="bg-parent">
      <div class="bg-image" :style="'background-image: url(' + imgUrl + ');'"></div>
      <div v-if="imgUrl != ''" class="bg-overlay"></div>
    </div>
    <div class="mb-4" />
    <div class="container">
      <div>
        <div class="col-12" style="position: relative;width: 100%;height: 48px;">
          <div v-if="editProfile" class="edit-profile-action">
            <a class="btn btn-primary" data-toggle="modal" data-target="#bgAlert">Ndrysho prapavijën</a>
          </div>
        </div>
        <div class="row mb-3 d-flex p-0 profile-shop">
          <div class="profile-img-content col-12 col-md-2">
            <div class="profile-img" v-if="user.img_path">
              <img v-if="user.img_path.length > 0" :src="user.img_path" />
            </div>
            <div class="profile-img" v-if="!user.img_path">
              <img src="../../img/noimg.jpg" alt />
            </div>
          </div>
          <div
            class="shop-content col-12 col-md-3"
            :style="imgUrl ? 'color:white' : 'color: #5f5f5f!important'"
          >
            <p class="username">
              <i class="fa fa-shopping-bag"></i>
              {{ user.shop_name ? user.shop_name.name : user.shop_name }}
            </p>
            <p>
              <i class="fa fa-user"></i>
              {{ user.name }} {{ user.last_name }}
            </p>
            <p>
              <i class="fa fa-map" />
              {{ user.address }}
            </p>
            <p>
              <i class="fa fa-envelope"></i>
              {{ user.email }}
            </p>
            <p>
              <i class="fa fa-phone"></i>
              {{ user.phone }}
            </p>
          </div>
          <div
            class="shop-description col-12 col-md-6"
            :style="imgUrl ? 'color:white' : 'color: #5f5f5f!important'"
          >
            <p
              v-if="user.description != 'null'"
            >{{ user.description != 'null' ? user.description: "" }}</p>
          </div>
        </div>

        <div id="bgAlert" class="modal fade" role="dialog">
          <div class="modal-dialog mb-5">
            <div class="modal-content">
              <div class="modal-body">
                <span class="removeBgModal" @click="removeBgM()">&times;</span>
                <div class="row">
                  <div class="col-12">
                    <h4 class="step-title m-0 p-0">Ndrysho Prapavijën</h4>
                    <div class="mb-3">
                      <small>
                        Powered by
                        <strong>&copy; Unsplash</strong>
                      </small>
                    </div>
                  </div>
                </div>
                <small>Ju lutem bëni kërkimet në gjuhën angleze</small>
                <div class="row custom-form">
                  <div class="col-6">
                    <input
                      v-model="search_image"
                      class="form-control inputs custom-input"
                      type="text"
                      name="search"
                      style="width: 100%"
                      placeholder="Kërko foto.."
                    />
                  </div>
                  <div class="col-6">
                    <button class="btn btn-primary" @click="unsplashImage()">Kërko</button>
                    <button
                      class="btn link-btn pull-right"
                      @click="removeBg()"
                    >Largo foton nga prapavija</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3 col-6 my-2" v-for="(item, index) in imgs" :key="index">
                    <div class="unsplash-img">
                      <span v-if="checkedImg && chImgId == index">
                        <i class="fa fa-check" aria-hidden="true"></i>
                      </span>
                      <img
                        :class="checkedImg && chImgId == index ? 'checkedImage' : ''"
                        @click="changeBg(item.urls.regular), toggleActiveImg(index)"
                        :src="item.urls.thumb"
                        alt
                      />
                    </div>
                  </div>
                </div>
                <nav class="toolbox toolbox-pagination">
                  <paginate
                    :page-count="totalImg"
                    :click-handler="paginateImg"
                    v-model="actualImg"
                    :prev-text="'Prev'"
                    :next-text="'Next'"
                    :container-class="'pagination'"
                  />
                </nav>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Anulo</button>
                <button title="Ruaj komentin" class="btn btn-primary" @click="saveImgBg()">Ruaj</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="custom-head-post profile-search">
        <form @submit.prevent="searchProducts()" class="w-100 custom-form mb-1 mt-6">
          <div class="row w-100 m-0">
            <div class="col-md-3 col-12 col-sm-12 p-0 mr-2">
              <label :style="imgUrl ? 'color:white' : 'color: #5f5f5f!important'">Produkti</label>
              <input
                v-model="title"
                class="form-control custom-input"
                placeholder="Emri i produktit"
              />
            </div>
            <div class="col-md-3 col-12 col-sm-12 p-0 mr-2">
              <label :style="imgUrl ? 'color:white' : 'color: #5f5f5f!important'">Renditja</label>
              <select
                v-model="order_status"
                class="form-control custom-input"
                name="orderby"
                @change="orderChange($event)"
              >
                <option value="0" selected="selected">Rendit ne baz te dates me te re</option>
                <option value="3">Rendit ne baz te dates me te vjeter</option>
                <option value="2">Rendit ne baze te cmimit (nga me i uleti)</option>
                <option value="1">Rendit ne baze te cmimit (nga me i larti)</option>
              </select>
            </div>
            <div class="col-md-2 col-12 float-right p-0">
              <label for class="d-xm-none"></label>
              <button type="submit" class="btn btn-primary w-100">Kërko</button>
            </div>
          </div>
        </form>
      </div>
      <!-- <Shitjet/> -->
      <div class="row row-sm">
        <div v-for="product in products" :key="product.id" class="col-6 col-md-2 mb-2">
          <div class="product all-products">
            <figure class="product-image-container">
              <a :href="'/product/' + product.slug" class="product-image">
                <img
                  v-if="product.image_urls.length !== 0"
                  alt="product"
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
                <Shopping :id="product.id" :type="'button'" :class="'btn-bag btn-bag-sm'">
                  <i class="fa fa-cart-plus" />
                  <span class="shto-nshport">Shto në shportë</span>
                </Shopping>
                <Wishlist :id="product.id" :likebool="product.is_like" />
              </div>
            </div>
          </div>
        </div>
      </div>
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
  </main>
</template>

<script>
import Paginate from "vuejs-paginate";
import { mapGetters } from "vuex";
import Unsplash from "unsplash-js";
import swal from "sweetalert2";

export default {
  layout: "basic2",
  components: {
    Paginate,
    Unsplash
  },
  props: {
    name: {
      type: [String],
      default: ""
    }
  },
  data() {
    return {
      appName: window.config.appName,
      user: [],
      products: [],
      totalPage: 1,
      actualPage: 1,
      totalImg: 1,
      actualImg: 1,
      chImgId: "",
      title: "",
      order_status: 0,
      shopName: "",
      editProfile: false,
      imgUrl: "",
      search_image: "",
      imgs: [],
      checkedImg: false,
      last_search: ""
    };
  },
  computed: mapGetters({
    user_auth: "auth/user"
  }),

  created() {
    this.shopName = this.$props.name;
    this.searchProfile();
    this.searchProducts();
    this.unsplashImage();
  },
  methods: {
    changeBg(item) {
      this.imgUrl = item;
    },
    removeBg() {
      this.imgUrl = "";
    },
    toggleActiveImg(index) {
      this.chImgId = index;
      this.checkedImg = true;
    },
    saveImgBg() {
      let data = {
        img_background: this.imgUrl
      };
      this.axios.post("/api/user/image/background", data).then(result => {
        if (result.data.status === "success") {
          let $ = require("jquery");
          $("#bgAlert").modal("hide");
        }
      });
    },
    removeBgM() {
      let $ = require("jquery");
      $("#bgAlert").modal("hide");
    },
    unsplashImage() {
      this.checkedImg = false;
      const Unsplash = require("unsplash-js").default;
      const unsplash = new Unsplash({
        accessKey: "wjakoWOMTIIk76HJbEHAhsk73T2P0xav3lZS3xKdI7A"
      });
      if (this.search_image != this.last_search) {
        this.last_search = this.search_image;
        this.actualImg = 1;
      }
      var title = this.search_image ? this.search_image : "shop";
      var actualImg = this.actualImg;
      unsplash.search
        .photos(title, actualImg, 12)
        // .then(toJson)
        .then(result => result.json())
        .then(result => {
          this.imgs = result.results;
          this.totalImg = result.total_pages;
          // Your code
        })
        .catch(err => {
          this.removeBgM();
          swal({
            type: "warning",
            text:
              "Eshte kaluar limiti i kerkimit per foto. Ju lutem provoni me vone.",
            reverseButtons: true,
            confirmButtonText: "Ne rregull"
          });
        });
    },
    searchProfile() {
      let vm = this;
      fetch(`/api/user/DataByShopName/` + vm.shopName, {
        method: "get"
      })
        .then(result => result.json())
        .then(result => {
          vm.user = result.response;
          vm.imgUrl = vm.user.img_background;
          if (vm.user_auth && vm.user.id == vm.user_auth.id) {
            vm.editProfile = true;
          }
        });
    },
    searchProducts() {
      let vm = this;
      let titleName = vm.title ? vm.title : 0;
      fetch(
        `/api/products/getProductsByShopName?shop_name=` +
          vm.shopName +
          "&order_status=" +
          vm.order_status +
          "&title=" +
          titleName +
          "&page=" +
          vm.actualPage,
        {
          method: "get"
        }
      )
        .then(result => result.json())
        .then(result => {
          vm.products = result.response.data;
          vm.totalPage = result.response.last_page;
        });
    },
    orderChange($event) {
      this.order_status = $event.target.value;
    },
    paginate(page) {
      this.actualPage = page;
      this.searchProducts();
    },
    paginateImg(page) {
      this.actualImg = page;
      this.unsplashImage();
    }
  }
};
</script>