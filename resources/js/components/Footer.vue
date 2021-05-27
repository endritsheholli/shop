<template>
  <footer class="footer">
    <div class="container">
      <div class="subscription">
        <div class="container">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="subscribe-text">
                <h6>Subscribe</h6>
                <p>Sed ut perspiciatis unde omnis iste natus.</p>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="subscribe-form">
                <form class="d-flex w-100">
                  <input
                    type="email"
                    v-model="subscribe_email"
                    name="email"
                    placeholder="Enter E-Mail Address"
                  />
                  <button
                    type="submit"
                    class="btn btn-white"
                    @click.prevent="subscribe()"
                  >
                    Subscribe
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-4 mb-3">
          <div class="footer-logo">
            <img src="../../img/logo-white.svg" alt="eblejta" />
          </div>
          <div class="pay-info">
            <h6>Payment</h6>
            <div class="payment-img">
              <img src="../../img/assets/payments.png" alt="payment" />
            </div>
          </div>
          <div class="pay-info">
            <h6>Shippings</h6>
            <div class="payment-img">
              <img src="../../img/assets/shippings.png" alt="shipping" />
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4 center-cont-f mb-3">
          <div class="category-footer">
            <h6 class="footer-title">Menu</h6>
            <ul class="menu sf-arrows footer-menu">
              <li>
                <router-link :to="{ name: 'welcome' }">Home</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">Products</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }"
                  >New Arriwals</router-link
                >
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">News/Blog</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">About</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">Contact</router-link>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-12 col-md-4 center-cont-f mb-3">
          <div class="category-footer">
            <h6 class="footer-title">Category</h6>
            <ul class="menu sf-arrows footer-menu">
              <li>
                <router-link :to="{ name: 'welcome' }">Clothes</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">Technology</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }"
                  >Home & Kitchen</router-link
                >
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">Garden</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }"
                  >Beauty, Health</router-link
                >
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">Gaming</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">Sports</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'welcome' }">Office</router-link>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row footer-footer">
        <div class="col-md-4 col-12">
          <p class="footer-copyright">
            Copyright &copy; 2020 eBlejta. All rights reserved.
          </p>
        </div>
        <div class="col-md-8 col-12">
          <div class="pull-right d-flex align-items-center">
            <div class="mr-3">
              <a href="#" class="footer-rights-links" target="_blank">
                FAQ's
              </a>
              <a href="#" class="footer-rights-links" target="_blank">
                Terms of use
              </a>
              <a href="#" class="footer-rights-links" target="_blank">
                Privacy
              </a>
            </div>
            <div class="footer-social-icons">
              <a
                href="https://www.facebook.com/"
                class="social-icons"
                target="_blank"
              >
                <i class="icon-facebook" />
              </a>
              <a
                href="https://www.instagram.com/"
                class="social-icons"
                target="_blank"
              >
                <i class="icon-instagram" />
              </a>
              <a
                href="https://twitter.com/"
                class="social-icons"
                target="_blank"
              >
                <i class="icon-twitter" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
<script>
import swal from "sweetalert2";

export default {
  name: "Footer2",
  data() {
    return {
      subscribe_email: [],
    };
  },
  methods: {
    subscribe() {
      let data = {
        email: this.subscribe_email,
      };
      this.axios.post(`/api/subscription/create`, data).then((result) => {
        if (result.data.status === "success") {
          swal({
            type: "success",
            title: "You have subscribed succesfully",
            confirmButtonText: "ok",
          });
          this.subscribe_email = "";
        } else {
          if (result.data.status === "failed") {
            console.log('error',result)
            swal({
              type: "error",
              title: 'Error',
              text: result.data.message.email[0],
              reverseButtons: true,
              confirmButtonText: 'ok',
            });
          }
        }
      });
    },
  },
};
</script>
