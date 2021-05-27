<template>
  <div class="product-slider">
    <div class="product all-products">
      <div class="bg-overlay-white"></div>
      <div class="product-action">
        <Shopping :id="item.id" :type="'button'" class="prod-icon">
          <img src="../icons/bag-white.svg" />
        </Shopping>
        <Wishlist :id="item.id" :likebool="item.is_like" class="prod-icon" />
        <router-link
          :to="{ name: 'product', params: { id: item.slug } }"
          class="remove-btn-style prod-icon"
        >
          <img src="../icons/visibility-white.svg" />
        </router-link>
      </div>
      <figure class="product-image-container">
        <router-link
          :to="{ name: 'product', params: { id: item.slug } }"
          class="product-image"
        >
          <img
            v-if="item.image_urls.length !== 0"
            :style="{ height: '23px;', width: '20px;' }"
            :src="item.image_urls[0]"
          />
          <img v-if="item.image_urls.length == 0" src="../../img/noimg.jpg" />
        </router-link>
      </figure>
      <div class="product-details">
        <h2 class="product-title">
          <a :href="'/product/' + item.slug">{{ item.title }}</a>
        </h2>
        <div class="price-box">
          <span v-if="item.price" class="product-price"
            >{{ item.price }} &euro;</span
          >
          <span v-if="item.previous_price" class="product-price prev-price">
            <strike>{{ item.previous_price }} &euro;</strike>
          </span>
        </div>
        <div class="rating-product">
          <img src="../icons/half-star.svg" /> <span>4.5</span>
        </div>
      </div>
    </div>
    <div class="px-3">
      <div class="mx-5 slider">
        <VueSlickCarousel ref="carousel" v-bind="settings">
          <div class="product-slide-img">
            <img src="../../img/iphone.jpg" alt="product image" />
          </div>
          <div class="product-slide-img">
            <img src="../../img/iphone2.jpg" alt="product image" />
          </div>
          <div class="product-slide-img">
            <img src="../../img/iphone.jpg" alt="product image" />
          </div>
          <div class="product-slide-img">
            <img src="../../img/iphone2.jpg" alt="product image" />
          </div>
        </VueSlickCarousel>
        <button class="slider-arrow arrow-prev" @click="showPrev()">
          <img src="../icons/chevron-right.svg" />
        </button>
        <button class="slider-arrow arrow-next" @click="showNext()">
          <img src="../icons/chevron-right.svg" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Button from "./Button.vue";
import VueSlickCarousel from "vue-slick-carousel";
import "vue-slick-carousel/dist/vue-slick-carousel.css";
// optional style for arrows & dots
import "vue-slick-carousel/dist/vue-slick-carousel-theme.css";

export default {
  components: { VueSlickCarousel },
  props: {
    item: {
      default: "",
    },
  },
  data() {
    return {
      settings: {
        arrows: false,
        dots: false,
        dotsClass: "slick-dots custom-dot-class",
        infinite: true,
        speed: 500,
        slidesToShow: 2,
        slidesToScroll: 1,
        swipeToSlide: true,
        rows: 1,
        focusOnSelect: false,
      },
    };
  },
  methods: {
    showNext() {
      this.$refs.carousel.next();
    },
    showPrev() {
      this.$refs.carousel.prev();
    },
  },
};
</script>