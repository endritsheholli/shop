<template>
  <div class="product-wide">
    <div class="product all-products m-0">
      <div class="product-main-info">
        <figure class="product-image-container">
          <router-link
            :to="{ name: 'product', params: { id: item.slug } }"
            class="product-image"
          >
            <!-- <img
              v-if="item.image_urls.length !== 0"
              :style="{ height: '23px;', width: '20px;' }"
              :src="item.image_urls[0]"
            />
            <img v-if="item.image_urls.length == 0" src="../../img/noimg.jpg" /> -->
            <img src="../../img/noimg.jpg" />
          </router-link>
        </figure>
        <div class="product-details">
          <h2 class="product-title">
            <a :href="'/product/' + item.slug">{{ item.title }}</a>
          </h2>
          <div class="price-box2">
            <span v-if="item.price" class="product-price"
              >{{ item.price }} &euro;</span
            >
            <span v-if="item.previous_price" class="product-price prev-price">
              <strike>{{ item.previous_price }} &euro;</strike>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="product-action">
      <div class="rating-product">
        <img src="../icons/half-star.svg" /> <span>4.5</span>
      </div>
      <Shopping :id="item.id" :type="'button'" class="prod-icon bag-btn">
        <img class="white-icon" src="../icons/bag-white.svg" />
        <img class="black-icon" src="../icons/bag.svg" />
        <span>Add to cart</span>
      </Shopping>
      <Wishlist
        :id="item.id"
        :likebool="item.is_like"
        @mouseover.native="darkLike = false"
        @mouseleave.native="darkLike = true"
        :dark="this.darkLike"
        class="prod-icon"
      />
      <router-link
        :to="{ name: 'product', params: { id: item.slug } }"
        class="remove-btn-style prod-icon"
      >
        <img class="white-icon" src="../icons/visibility-white.svg" />
        <img class="black-icon" src="../icons/visibility.svg" />
      </router-link>
    </div>
  </div>
</template>

<script>
import Button from "./Button.vue";

export default {
  props: {
    item: {
      default: "",
    },
  },
  data() {
    return {
      darkLike: true,
    };
  },
  methods: {},
};
</script>
