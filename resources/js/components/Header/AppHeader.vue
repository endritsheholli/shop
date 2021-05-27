<template>
  <header class="header">
    <Login />
    <Register />
    <div class="header-middle" :class="!this.panelcheck ? 'mh-140' : 'mh-100'">
      <div class="container-fluid">
        <div class="row header-search">
          <div class="col-12 col-md-3">
            <router-link class="logo" :to="{ name: 'ballina' }">
              <img
                class="d-xm-none"
                src="../../../img/logo.svg"
                alt="eblejta"
              />
              <img
                class="d-xm-block"
                style="width: 70px"
                src="../../../img/dypo-logo-f.png"
                alt="DY-PO"
              />
            </router-link>
          </div>
          <div class="col-12 col-md-6">
            <form method="get" @submit.prevent="search" class="header-form">
              <div class="header-search-wrapper">
                <!-- <div class="select-custom">
                  <select
                    id="category_id"
                    v-model="category_id"
                    class="form-control"
                  >
                    <option :value="0">
                      Të gjitha kategorite
                    </option>
                    <option
                      v-for="item in categories"
                      :key="item.id"
                      :value="item.id"
                    >
                      {{ item.name }}
                    </option>
                  </select>
                </div> -->
                <div class="category-menu">
                  <b-dropdown
                    id="dropdown-1"
                    text="Category"
                    class="main-categroy-select"
                  >
                    <template slot="button-content" class="donii">
                      <img src="../../icons/list-item.svg" width="20" alt="" />
                      Category
                    </template>
                    <!-- <b-dropdown-item>{{ item.name }}</b-dropdown-item>
                  <b-dropdown-item>Second Action 1</b-dropdown-item> -->
                    <b-dropdown
                      v-for="item in links"
                      :key="item.id"
                      id="dropdown-2"
                      :text="item.name"
                      class="m-md-2 dropright"
                    >
                      <template slot="button-content">
                        <img
                          src="../../../img/cat-icon.png"
                          width="20"
                          class="mr-2"
                          alt=""
                        />
                        {{ item.name }}
                      </template>
                      <!-- right -->
                      <!-- <b-dropdown-item>ss{{ item.name }}</b-dropdown-item> -->
                      <b-dropdown-item
                        v-for="subitem in item.subnav"
                        :key="subitem.name"
                        >{{ subitem.name }}</b-dropdown-item
                      >
                    </b-dropdown>
                  </b-dropdown>
                </div>

                <!-- end -->
                <input
                  id="q"
                  v-model="title"
                  type="search"
                  class="form-control"
                  name="q"
                  placeholder="Kërko..."
                />
                <!-- End .select-custom -->
                <button class="btn search-icon-btn" type="submit">
                  <span class="input-group-addon">
                    <i class="fa fa-search" />
                  </span>
                </button>
              </div>
            </form>
          </div>
          <div class="col-12 col-md-3">
            <div class="d-flex align-items-center justify-content-end">
              <div class="user-btn">
                <div class="shop-btn">
                  <div v-if="user"  class="add-products-btn">
                    <router-link
                      :to="{ name: 'add-product' }"
                      class="add-products-link"
                    >
                      <div class="img-add-prod">
                        <img src="../../icons/plus.svg" />
                      </div>
                      <h6>Add Products</h6>
                    </router-link>
                  </div>
                  <!-- // favorite btn -->
                  <div class="btn-group">
                    <button
                      type="button"
                      class="btn dropdown-toggle favorite-header purple-brown-bg"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <img
                        src="../../icons/like.svg"
                        width="16px"
                        height="16px"
                      />
                      <span class="like-count">{{ wishlist.length }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="dropdownmenu-wrapper">
                        <div class="market-card">
                          <h2 class="sub-title">Wishlist</h2>
                          <div class="dropdown-cart-products">
                            <div class="market-dropdown">
                              <div
                                v-for="(item, index) in wishlist"
                                :key="item.id"
                                class="product"
                              >
                                <figure class="product-image-container">
                                  <a
                                    :href="'/product/' + item.product.slug"
                                    class="product-image"
                                  >
                                    <!-- <p>{{ item.product.image_urls}}</p> -->
                                    <img
                                      v-if="item.product.image_urls.length > 0"
                                      :src="item.product.image_urls[0]"
                                      alt="product"
                                    />
                                    <img
                                      v-if="!item.product.image_urls.length > 0"
                                      src="../../../img/noimg.jpg"
                                      alt="product"
                                    />
                                  </a>
                                </figure>
                                <div class="liked-items">
                                      <!-- removeWishlist(item.product.id, index) -->
                                  <a
                                    class="btn-remove"
                                    title="Remove Product"
                                    @click.prevent="
                                      removeWishlist(item.product.id, index)
                                    "
                                  >
                                    <!-- <i class="icon-cancel" /> -->
                                    <img src="../../icons/like.svg" />
                                  </a>
                                </div>
                                <div class="product-details">
                                  <h4 class="product-title">
                                    <a
                                      :href="'/product/' + item.product.slug"
                                      >{{ item.product.title }}</a
                                    >
                                  </h4>
                                  <span class="cart-product-info">
                                    <span class="market-card-price"
                                      >{{
                                        item.product ? item.product.price : ""
                                      }}
                                      &euro;</span
                                    >
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="dropdown-cart-action">
                          <router-link
                            class="btn btn-primary ml-auto"
                            :to="{ name: 'wishlist-cart' }"
                            >View All</router-link
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- // bag icon -->
                  <div class="btn-group">
                    <button
                      type="button"
                      class="btn dropdown-toggle favorite-header purple-brown-bg"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <img
                        src="../../icons/bag.svg"
                        width="16px"
                        height="16px"
                      />
                      <span class="like-count">{{ length_shoping }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="dropdownmenu-wrapper">
                        <div class="market-card">
                          <!-- <div class="dropdown-cart-header">
                            <span>{{ length_shoping }} Artikuj</span>
                          </div> -->
                          <h2 class="sub-title">Shopping Cart</h2>
                          <div class="dropdown-cart-products">
                            <div class="market-dropdown">
                              <div
                                v-for="(item, index) in shopping"
                                :key="item.id"
                                class="product"
                              >
                                <figure class="product-image-container">
                                  <a
                                    :href="'/product/' + item.product.slug"
                                    class="product-image"
                                  >
                                    <!-- <p>{{ item.product.image_urls}}</p> -->
                                    <img
                                      v-if="item.product.image_urls.length > 0"
                                      :src="item.product.image_urls[0]"
                                      alt="product"
                                    />
                                    <img
                                      v-if="!item.product.image_urls.length > 0"
                                      src="../../../img/noimg.jpg"
                                      alt="product"
                                    />
                                  </a>
                                </figure>
                                <div class="remove-items">
                                  <span class="cart-product-qty">{{
                                    item.quantity > 0
                                      ? item.quantity + " x"
                                      : "Nuk ka ne stock"
                                  }}</span>
                                  <a
                                    class="btn-remove"
                                    title="Remove Product"
                                    @click="
                                      removeShopping(
                                        item.id,
                                        index,
                                        item.product.price
                                      )
                                    "
                                  >
                                    <!-- <i class="icon-cancel" /> -->
                                    <img src="../../icons/times-black.svg" />
                                  </a>
                                </div>
                                <div class="product-details">
                                  <h4 class="product-title">
                                    <a
                                      :href="'/product/' + item.product.slug"
                                      >{{ item.product.title }}</a
                                    >
                                  </h4>
                                  <span class="cart-product-info">
                                    <span class="market-card-price"
                                      >{{
                                        item.product ? item.product.price : ""
                                      }}
                                      &euro;</span
                                    >
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="dropdown-cart-action">
                          <router-link
                            class="btn btn-black"
                            :to="{ name: 'shopping-cart' }"
                            >View Cart</router-link
                          >
                          <router-link
                            class="btn btn-primary"
                            :to="{ name: 'shopping-cart' }"
                            >Checkout</router-link
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-if="user" class="dropdown-profile">
                    <div class="btn-group">
                      <button
                        type="button"
                        class="btn dropdown-toggle profile-dropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        data-offset="5,10"
                        aria-expanded="false"
                      >
                        <div class="profile-icon">
                          <img
                            class="user-icon"
                            v-if="user && user.photo_url"
                            :src="user.photo_url"
                            alt="profile image"
                          />
                          <img
                            v-else
                            class="user-icon"
                            src="../../icons/profile-img.svg"
                          />
                          <img
                            class="arrow-icon"
                            src="../../icons/chevron-down-black.svg"
                          />
                        </div>

                        <span class="user-name">{{ user.name }}</span>
                        <!-- <span class="user-name">Account Name</span> -->
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                        <router-link :to="{ name: 'wishlist-cart' }">
                          <button class="dropdown-item" type="button">
                            <span> Favoritet </span>
                            <!-- <i class="fa fa-heart" />  -->
                          </button>
                        </router-link>
                        <router-link
                          v-if="user && user.role === 'shop'"
                          :to="{ name: 'orders' }"
                        >
                          <button class="dropdown-item" type="button">
                            <span> Paneli </span>
                          </button>
                        </router-link>
                        <router-link
                          v-if="user && user.role === 'admin'"
                          :to="{ name: 'product-manage' }"
                        >
                          <button class="dropdown-item" type="button">
                            <span> Paneli </span>
                          </button>
                        </router-link>
                        <router-link :to="{ name: 'edit-profile' }">
                          <button class="dropdown-item" type="button">
                            <span> Profili im </span>
                          </button>
                        </router-link>
                        <a
                          style="cursor: pointer"
                          class="dropdown-btn"
                          @click.prevent="logout"
                        >
                          <button class="dropdown-item" type="button">
                            <!-- <span><i class="fa fa-power-off" /> Log out</span> -->
                            <span
                              class="d-flex align-items-center justify-content-around"
                              ><img src="../../icons/log-out.svg" /> Log
                              out</span
                            >
                          </button>
                        </a>
                      </div>
                    </div>
                  </div>
                  <a
                    v-else
                    class="user-profile ml-3"
                    @click="showLogin()"
                    ><span class="user-name">
                      <img src="../../icons/person.svg" color="red" /> </span
                  ></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid">
        <div class="header-center">
          <div class="header-search no-mb-v" v-if="!this.panelcheck">
            <div class="mob-sec-header">
              <button v-on:click="triggerCatMenu()" id="catMenu">
                <span class="catmenuspan">
                  <i class="fa fa-th-list"></i>
                </span>
              </button>
            </div>
            <a href="#" class="search-toggle d-xm-none" role="button">
              <i class="icon-magnifier" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import { mapGetters } from "vuex";
import HeaderDropdownAccnt from "../HeaderDropdownAccnt.vue";
import HeaderDropdownNotif from "../HeaderDropdownNotif.vue";
import LocaleDropdown from "../LocaleDropdown.vue";
import Login from "../Login.vue";
import Register from "../Register.vue";
import axios from "axios";

export default {
  name: "AppHeader",
  components: {
    HeaderDropdownAccnt,
    HeaderDropdownNotif,
    LocaleDropdown,
    Login,
    Register,
  },

  data() {
    return {
      links: [
        {
          name: "Cats",
          subnav: [
            { name: "Tabby" },
            { name: "Maine Coon" },
            { name: "Hairless Rex" },
            { name: "Occicat" },
          ],
        },
        {
          name: "Dogs",
          subnav: [
            { name: "German Shepard" },
            { name: "Poodle" },
            { name: "Retriever" },
            { name: "Pug" },
          ],
        },
        { name: "Single Link" },
      ],

      appName: window.config.appName,
      categories: [],
      wishlist: [],
      panel: [],
      panelcheck: false,
      category_id: this.$route.params.id ? this.$route.params.id : 0,
      title: this.$route.params.name ? this.$route.params.name : "",
      products: [],

      // total: 0
    };
  },
  created() {
    this.showCategories();
    if (this.user) {
      this.getShopping();
      this.getWishlist();
    }
  },
  beforeDestroy() {
    document.removeEventListener("click", this.onClick);
  },
  mounted: function () {
    this.$root.$on("bv::dropdown::show", (bvEvent) => {
      if (bvEvent.componentId === "dropdown-2") {
        this.isDropdown2Visible = true;
      }
    });
    this.$root.$on("bv::dropdown::hide", (bvEvent) => {
      if (bvEvent.componentId === "dropdown-2") {
        this.isDropdown2Visible = false;
      }
      if (this.isDropdown2Visible) {
        bvEvent.preventDefault();
      }
    });
  },
  updated() {
    if (this.$route.path != this.panel[1]) {
      this.panel[1] = this.$route.path[1];
    }
  },
  computed: mapGetters({
    user: "auth/user",
    shopping: "shoping/usershopping",
    total: "shoping/total",
    length_shoping: "shoping/length_shoping",
  }),
  methods: {
    onClick() {
      let $ = require("jquery");
      if (event.target.matches(".form-control")) {
        // duhet me gjet zgjidhje ma te mir
        $(document).ready(function () {
          $(".header-bottom").removeClass("d-flex");
        });
      }
    },
    triggerCatMenu() {
      let $ = require("jquery");

      $(document).ready(function () {
        $(".header-bottom").toggleClass("d-flex");
      });
    },
    catOnlySearch(id, cid) {
      let title = 0;
      let category = id;
      let subcategory = cid;
      this.$router.push({
        name: "search",
        query: {
          id: category,
          name: title,
          subcategory: subcategory,
        },
      });
    },
    search() {
      let category = this.$route.params.id ? this.$route.params.id : 0;
      let title = this.$route.params.name ? this.$route.params.name : 0;
      if (this.category_id) {
        category = this.category_id;
      }
      if (this.title) {
        let vm = this;
        title = vm.title;
      }
      if (title !== this.$router.history.current.params.name) {
        this.$router.push({
          name: "search",
          query: { id: category, name: title },
        });
      } else if (title === this.$router.history.current.params.name) {
        alert("Ju tashme keni kerkuar me emrin e njejt");
      } else {
        title = 0;
        this.$router.push({
          name: "search",
          query: { id: category, name: title },
        });
      }
    },
    sidebarToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-hidden");
    },
    sidebarMinimize(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-minimized");
    },
    mobileSidebarToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-mobile-show");
    },
    showCategories() {
      let vm = this;
      fetch(`/api/categories/getParents`, {
        method: "get",
      })
        .then((result) => result.json())
        .then((result) => {
          vm.categories = result.response;
        })
        .catch((err) =>
          swal({
            type: "error",
            title: i18n.t("error_alert_title"),
            text: err,
            reverseButtons: true,
            confirmButtonText: i18n.t("ok"),
          })
        );
    },
    searchProducts() {
      let vm = this;
      fetch(`/api/products/search/` + vm.title + `/` + vm.category_id, {
        method: "get",
      })
        .then((result) => result.json())
        .then((result) => {
          vm.products = result.response;
        })
        .catch((err) =>
          swal({
            type: "error",
            title: i18n.t("error_alert_title"),
            text: err,
            reverseButtons: true,
            confirmButtonText: i18n.t("ok"),
          })
        );
    },
    async getShopping() {
      await this.$store.dispatch("shoping/fetchItems");
    },
    getWishlist() {
      let vm = this;
      axios.get(`/api/likedproducts/get-my-product`).then((result) => {
        if (result.data.status === "success") {
          vm.wishlist = result.data.response;
        } else {
          swal({
            type: "error",
            title: i18n.t("error_alert_title"),
            text: result.data.message,
            reverseButtons: true,
            confirmButtonText: i18n.t("ok"),
          });
          this.loadingall = false;
        }
      });
    },
    removeWishlist(id, index) {
      this.axios.post(`/api/likedproducts/remove/${id}`).then((result) => {
        if (result.data.status === "success") {
          this.wishlist.splice(index, 1);
        }
      });
    },
    removeShopping(id, index, price) {
      axios.post(`/api/shoppingCard/remove/${id}`).then((result) => {
        if (result.data.status === "success") {
          this.$store.dispatch("shoping/fetchItems");
        }
      });
    },
    async logout() {
      await this.$store.dispatch("auth/logout");
      if (this.$route.path === "/") {
        location.reload();
      } else {
        this.$router.push({
          name: "ballina",
        });
      }
    },
    showLogin(){
       this.$bvModal.show("login_modal");
    }
  },
};
</script>
<style>
#triside {
  border: 0;
  background: #65b891;
  margin: 0;
  min-height: 30px;
  font-size: 16px;
  color: white;
  font-weight: 600;
}

#triside .fa-bars {
  color: #ffffff;
  margin-right: 5px;
}
#catMenu {
  background: #21293c;
  height: 100%;
  width: 40px;
  border: 0;
  margin: 0;
  color: white;
  z-index: 41;
  margin-top: 1px;
}
#catMenu span {
  color: #ffffff;
  font-size: 20px;
}
</style>
