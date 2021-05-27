<template>
  <div>
    <div class="product all-products m-0">
      <router-link
        :to="{ name: 'product', params: { id: item.slug } }"
        class="remove-btn-style"
      >
        <div class="bg-overlay-white"></div>
      </router-link>
      <div class="product-action">
        <Shopping :id="item.id" :type="'button'" class="prod-icon">
          <img src="../icons/bag-white.svg" />
        </Shopping>
        <Wishlist :id="item.id" :likebool="item.is_like" class="prod-icon" />
        <!-- data-toggle="modal"
          data-target="#exampleModal" -->
        <a @click="openModal(item.slug)" class="remove-btn-style prod-icon">
          <img src="../icons/visibility-white.svg" />
        </a>
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
    <ProductModal :slug="item.slug"></ProductModal>
  </div>
</template>

<script>
import Button from "./Button.vue";
import ProductModal from "./ProductModal.vue";

export default {
  props: {
    item: {
      default: "",
    },
  },
  components: {
    ProductModal,
  },
  methods: {
    openModal(slug) {
      this.$emit("update-slug", slug);
    },
  },
};
</script>
