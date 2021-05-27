<template>
  <main class="main">
    <Loader v-if="!loaded" :position="'fixed'" />
    <div class="container">
      <div class="row mt-3">
        <div class="col-lg-9">
          <div class="product-single-container product-single-default">
            <div class="row">
              <div class="col-lg-7 col-md-6 product-single-gallery">
                <div
                  id="carousel-thumb"
                  class="carousel slide carousel-fade carousel-thumbnails product-carousel"
                  data-ride="carousel"
                >
                  <div class="carousel-inner" role="listbox">
                    <div
                      :class="index == 0 ? 'carousel-item active': 'carousel-item'"
                      v-for="(image,index) in product.image_urls"
                      :key="index"
                    >
                      <v-zoom
                        v-if="product.image_urls.length !== 0"
                        :img="image"
                        width="100%"
                        class="product-single-image d-block w-100"
                        :src="product.image_urls[0]"
                      />

                      <v-zoom
                        v-else
                        src="../../img/noimg.jpg"
                        :img="image"
                        width="100%"
                        class="product-single-image d-block w-100"
                      />
                    </div>
                  </div>
                  <!-- <a
                    class="carousel-control-prev"
                    href="#carousel-thumb"
                    role="button"
                    data-slide="prev"
                  >
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a
                    class="carousel-control-next"
                    href="#carousel-thumb"
                    role="button"
                    data-slide="next"
                  >
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>-->
                  <ol class="carousel-indicators">
                    <li
                      v-for="(image,index) in product.image_urls"
                      :key="index"
                      data-target="#carousel-thumb"
                      :data-slide-to="index"
                      class="active"
                    >
                      <img
                        v-if="product.image_urls.length !== 0"
                        width="100"
                        :src="product.image_urls[index]"
                      />
                      <img v-if="product.image_urls.length == 0" src="../../img/noimg.jpg" />
                    </li>
                  </ol>
                </div>
              </div>
              <div class="col-lg-5 col-md-6">
                <div class="product-single-details">
                  <h1 class="product-title">{{ product.title }}</h1>
                  <div class="price-box">
                    <span class="product-price">
                      {{ product.price }} &euro;
                      <span
                        v-if="product.previous_price"
                        class="old-price"
                      >{{ product.previous_price }} &euro;</span>
                    </span>
                  </div>
                  <div>
                    <p
                      class="highlight"
                    >{{ product.posta_free == 1 ? 'Në çmim përfshihet posta' : 'Posta nuk përfshihet në çmim' }}</p>
                  </div>
                  <!-- End .price-box -->
                  <div class="product-desc">
                    <p>{{ product.description }}</p>
                  </div>
                  <label for="comment">Mesazhi per shitesin:</label>
                  <textarea
                    name="comment"
                    id="comment"
                    v-model="comment"
                    maxlength="250"
                    style="width: 100%;min-height: 90px;"
                    placeholder="Komenti juaj"
                  ></textarea>
                  <div class="d-flex">
                    <button class="btn count-btn" @click="removeQuantity()">-</button>
                    <p class="count-input" style="text-align: center;">{{ quantity }}</p>
                    <button class="btn count-btn" @click="addQuantity()">+</button>
                  </div>
                  <div v-if="product.id > 0" class="product-action product-all-icons">
                    <Shopping
                      :id="product.id"
                      :quantity="quantity"
                      :comment="comment"
                      :type="'button'"
                      :class="'btn-bag'"
                    >
                      <i class="fa fa-cart-plus" /> Shto në shportë
                    </Shopping>
                    <Wishlist :id="product.id" :likebool="product.is_like" />
                  </div>
                  <!-- End .product-action -->
                  <div class="product-single-share">
                    <label>Shperndaje:</label>
                    <!-- www.addthis.com share plugin-->
                    <social-sharing
                      :url="this.$route.query.page"
                      :title="product.title"
                      :hashtags="product.slug"
                      inline-template
                    >
                      <div class="addthis_inline_share_toolbox">
                        <network network="facebook" class="facebook">
                          <i class="fa fa-facebook" />
                        </network>
                        <network network="twitter" class="twitter">
                          <i class="fa fa-twitter" />
                        </network>
                        <network network="whatsapp" class="whatsapp">
                          <i class="fa fa-whatsapp" />
                        </network>
                        <network network="email" class="email">
                          <i class="fa fa-envelope" />
                        </network>
                      </div>
                    </social-sharing>
                  </div>
                  <!-- End .product single-share -->
                </div>
                <!-- End .product-single-details -->
              </div>
              <!-- End .col-lg-5 -->
            </div>
            <!-- End .row -->
          </div>
        </div>
        <!-- End .col-lg-9 -->
        <div class="sidebar-overlay" />
        <!-- <div class="sidebar-toggle">
          <i class="icon-sliders" />
        </div>-->
        <aside class="sidebar-product col-lg-3 padding-left-lg mobile-sidebar">
          <div class="sidebar-wrapper">
            <div class="widget widget-brand profile-page">
              <router-link :to="{ name: 'profile', params: {name: product.shop_name.slug} }">
                <!-- <div class="profile-img">
                  <img src="../../img/270x/person.png" class="rounded-circle" alt="Person-photo" />
                </div>-->
                <div class="profile-img">
                  <img v-if="product.img_path " :src="product.img_path" />
                  <img v-if="!product.img_path" src="../../img/noimg.jpg" />
                </div>
                <span>{{ product.shop_name.name }}</span>
              </router-link>
            </div>
            <!-- <div class="widget widget-banner">
              <div class="banner banner-image">
                <a href="#">
                  <img src="../../img/banners/banner-sidebar.jpg" alt="Banner Desc" />
                </a>
              </div>
            </div>-->
          </div>
        </aside>
      </div>
    </div>
    <RelatedArticles v-if="loaded" :id="product_id" :cat-id="category_id" />
  </main>
</template>
<script>
import RelatedArticles from "./related_articles.vue";
import carousel from "vue-owl-carousel";
import Loader from "~/components/Loader";
import vZoom from "vue-zoom";

export default {
  layout: "basic",

  components: {
    carousel,
    RelatedArticles,
    Loader,
    vZoom
  },
  metaInfo() {
    return {
      title: this.$t("Product")
    };
  },
  data() {
    return {
      showByvalue: null,
      sliding: false,
      loaded: false,
      i: 1,
      img: "photo.jpg",
      width: 800,
      counter: 1,
      quantity: 1,
      slug: this.$route.params.id,
      product: [],
      product_id: 0,
      category_id: 0,
      fullscreen: false,
      posta_free: true,
      comment: ""
    };
  },
  mounted: function() {
    this.getProductBySlug();
  },
  updated() {
    if (this.slug != this.$route.params.id) {
      this.slug = this.$route.params.id;
      this.getProductBySlug();
    }
  },
  methods: {
    addQuantity() {
      if (this.quantity < this.product.quantity) {
        this.quantity = this.quantity + 1;
      }
      if (this.quantity === this.product.quantity) {
        alert("Nuk ka me produkte ne stock!");
      }
    },
    removeQuantity() {
      if (this.quantity > 1) {
        this.quantity = this.quantity - 1;
      }
    },
    onSlideStart(slide) {
      this.sliding = true;
    },
    onSlideEnd(slide) {
      this.sliding = false;
    },
    getProductBySlug() {
      let self = this;
      let url = "/api/products/getBySlug/" + self.slug;
      self.axios.get(url).then(res => {
        self.product = res.data.response;
        self.product.shop_name = JSON.parse(self.product.shop_name);
        self.product_id = self.product.id;
        self.category_id = self.product.category_id;
        this.loaded = true;
      });
    }
  }
};
</script>
