<template>
  <div>
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
      <div class="container">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.html">
              <i class="fa fa-home" />
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Na Kontaktoni</li>
        </ol>
      </div>
      <!-- End .container -->
    </nav>

    <div class="container">
      <div id="map" />
      <!-- End #map -->
      <div class="row">
        <div class="col-md-8">
          <h2 class="light-title">
            Na
            <strong>Shkruani</strong>
          </h2>

          <form action="#">
            <div class="form-group required-field">
              <label for="contact-name">Emri</label>
              <input
                id="contact-name"
                type="text"
                class="form-control"
                name="contact-name"
                required
              />
            </div>
            <!-- End .form-group -->
            <div class="form-group required-field">
              <label for="contact-email">E-mail</label>
              <input
                id="contact-email"
                type="email"
                class="form-control"
                name="contact-email"
                required
              />
            </div>
            <!-- End .form-group -->
            <div class="form-group">
              <label for="contact-phone">Numri i telefonit</label>
              <input id="contact-phone" type="tel" class="form-control" name="contact-phone" />
            </div>
            <!-- End .form-group -->
            <div class="form-group required-field">
              <label for="contact-message">Mesazhi</label>
              <textarea
                id="contact-message"
                cols="30"
                rows="1"
                class="form-control"
                name="contact-message"
                required
              />
            </div>
            <!-- End .form-group -->
            <div class="form-footer">
              <button type="submit" class="btn btn-primary">Dergo</button>
            </div>
            <!-- End .form-footer -->
          </form>
        </div>
        <!-- End .col-md-8 -->
        <div class="col-md-4">
          <h2 class="light-title">
            Na
            <strong>Kontaktoni</strong>
          </h2>

          <div class="contact-info">
            <div>
              <i class="fa fa-fax" />
              <p>
                <a href="tel:">0201 203 2032</a>
              </p>
              <p>
                <a href="tel:">0201 203 2032</a>
              </p>
            </div>
            <div>
              <i class="icon-mobile" />
              <p>
                <a href="tel:">201-123-3922</a>
              </p>
              <p>
                <a href="tel:">302-123-3928</a>
              </p>
            </div>
            <div>
              <i class="icon-mail-alt" />
              <p>
                <a href="mailto:#">dy-po@gmail.com</a>
              </p>
              <p>
                <a href="mailto:#">dy-po@gmail.com</a>
              </p>
            </div>
            <div>
              <i class="icon-skype" />
              <p>dy-po</p>
              <p>dy-po</p>
            </div>
          </div>
          <!-- End .contact-info -->
        </div>
        <!-- End .col-md-4 -->
      </div>
      <!-- End .row -->
    </div>
    <!-- End .container -->
    <div class="mb-8" />
    <!-- margin -->
  </div>
</template>

<script>
import LastArticles from "./last_articles.vue";
import CategoryList from "../components/ListCaegories.vue";

import carousel from "vue-owl-carousel";

export default {
  layout: "basic",
  components: {},
  metaInfo() {
    return {
      title: this.$t("home")
    };
  },
  data() {
    return {
      showByvalue: null,
      changed: false,
      updated: false
    };
  },
  methods: {}
};
</script>
