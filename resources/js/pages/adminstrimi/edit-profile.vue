<template>
  <div>
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="container">
      <div class="row">
        <div class="col-12 p-0">
          <div class="mob-sec-header">
            <div class="container">
              <button v-on:click="triggerSidebar()" id="triside">
                <i class="fa fa-bars"></i> Paneli
              </button>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-10 offset-md-1">
          <div class="mb-6" />
          <div class="col-12">
            <h2 class="step-title">
              Te dhenat e perdoruesit
              <div class="pull-right d-flex">
                <button
                  class="btn link-btn red-text float-right mr-5"
                  @click="openDeactivate()"
                >
                  Deaktivizo llogarinë
                </button>
                <router-link
                  v-if="profile.role === 'shop'"
                  class="btn link-btn"
                  :to="{
                    name: 'profile',
                    params: {
                      name: profile.shop_name ? profile.shop_name.slug : '',
                    },
                  }"
                >
                  <!-- <i class="icon-home" /> -->
                  Shiko Marketin tënd
                </router-link>
              </div>
            </h2>
          </div>
          <div class="mb-3" />
          <form
            id="step-1"
            class="row profile-info custom-form"
            @submit.prevent="updateProfile"
            @keydown="form.onKeydown($event)"
          >
            <div class="col-12 col-md-6">
              <div class="input-group mb-3 col-12 col-md-12">
                <!-- {{url1}} -->
                <picture-input
                  id="file"
                  ref="pictureInput"
                  type="file"
                  :prefill="url1"
                  margin="15"
                  accept="image/*"
                  size="10"
                  button-class="btn"
                  :custom-strings="{
                    upload: '<h1>Bummer!</h1>',
                    drag: 'Shto foto te profilit',
                  }"
                  style="z-index: 100"
                />
                <!-- :prefillOptions="url1" -->
                <has-error :form="form" field="picture" />
              </div>
            </div>
            <div class="col-12 col-md-6 p-0">
              <div class="input-group mb-3 col-12 col-md-12">
                <label for="name">Emri</label>
                <input
                  v-model="form.name"
                  required
                  :class="{ 'is-invalid': form.errors.has('name') }"
                  class="form-control inputs custom-input"
                  type="text"
                  name="name"
                  placeholder="Emri"
                />
                <has-error :form="form" field="name" />
              </div>
              <div class="input-group mb-3 col-12 col-md-12">
                <label for="last_name">Mbiemri</label>
                <input
                  v-model="form.last_name"
                  :class="{ 'is-invalid': form.errors.has('last_name') }"
                  class="form-control inputs custom-input"
                  type="text"
                  name="last_name"
                  placeholder="Mbiemri"
                />
                <has-error :form="form" field="last_name" />
              </div>
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="email">Email</label>
              <input
                v-model="form.email"
                required
                :class="{ 'is-invalid': form.errors.has('email') }"
                class="form-control inputs custom-input"
                type="email"
                name="email"
                placeholder="E-Mail"
              />
              <has-error :form="form" field="email" />
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="phone">Numri i Telefonit</label>
              <input
                v-model="form.phone"
                required
                :class="{ 'is-invalid': form.errors.has('phone') }"
                class="form-control inputs custom-input"
                type="text"
                name="phone"
                placeholder="Numri i telefonit"
              />
              <has-error :form="form" field="phone" />
            </div>
            <!-- Pjesa per shop -->
            <div class="input-group col-12 col-md-6">
              <label for="city">Qyteti:</label>
              <b-form-select
                v-model="form.city"
                class="form-control inputs custom-input mb-3"
              >
                <option :value="0" :selected="true">Qyteti</option>
                <option v-for="city in cities" :key="city.id" :value="city.id">
                  {{ city.name }}
                </option>
              </b-form-select>
            </div>
            <div class="input-group col-12 col-md-6">
              <label for="address">Adresa:</label>
              <input
                v-model="form.address"
                :class="{ 'is-invalid': form.errors.has('address') }"
                class="form-control inputs custom-input mb-3"
                name="address"
                placeholder="Adresa"
                rows="false"
              />
              <has-error :form="form" field="address" />
            </div>
            <div
              v-if="profile && profile.role === 'shop'"
              class="input-group col-12"
            >
              <div class="row">
                <div class="col-12 col-md-6">
                  <label for="shop_name">Emri i Marketit:</label>
                  <input
                    v-model="form.shop_name"
                    :class="{ 'is-invalid': form.errors.has('shop_name') }"
                    class="form-control inputs custom-input mb-3"
                    type="text"
                    name="shop_name"
                    placeholder="Emri i shopit"
                  />
                  <has-error :form="form" field="shop_name" />
                </div>
                <div class="col-12 col-md-6">
                  <label for="description">Rreth Marketit:</label>
                  <textarea
                    v-model="form.description"
                    :class="{ 'is-invalid': form.errors.has('description') }"
                    class="form-control inputs custom-input mb-3"
                    name="description"
                    placeholder="Rreth Marketit"
                    rows="4"
                    cols="50"
                  ></textarea>
                  <has-error :form="form" field="description" />
                </div>
              </div>
            </div>

            <!-- @change="onChangePicture(1)" -->

            <!-- Perfundimi i pjeses se shopit -->
            <div class="col-md-6 col-12">
              <div class="custom-control custom-checkbox" style="margin: 5px 0">
                <input
                  id="terms"
                  v-model="form.terms"
                  type="checkbox"
                  :class="{ 'is-invalid': form.errors.has('address') }"
                  class="checkbox custom-control-input custom-input"
                  name="terms"
                  rows="false"
                />
                <has-error :form="form" field="address" />
              </div>
            </div>
            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-primary float-right">
                Ndrysho
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <Password />
    <div class="mb-6" />
  </div>
</template>
<script>
import Form from "vform";
import FormError from "~/components/FormError.vue";
import PictureInput from "vue-picture-input";
import Password from "./change-password.vue";
import swal from "sweetalert2";
import store from "~/store";
import LoginWithFacebook from '~/components/LoginWithFacebook'
import Unsplash from "unsplash-js";

export default {
  middleware: "auth",
  // layout: "nosidebar",

  components: {
    LoginWithFacebook,
    FormError,
    PictureInput,
    Unsplash,
    Password,
  },

  metaInfo() {
    return {
      title: "edit",
    };
  },
  data: () => ({
    form: new Form({
      name: "",
      last_name: "",
      email: "",
      password: "",
      password_confirmation: "",
      phone: "",
      shop_name: "",
      address: "",
      description: "",
      interest_id: "",
      city: "",
    }),
    cities: [],
    profile: [],
    errors: {},
    categories: [],
    url1: "",
    loadingall: true,
    serach_image: "shop",
  }),
  created() {
    this.myProfile();
    this.getMainCategories();
    window.Swal = swal;
  },
  mounted: function () {
    this.getCities();
  },
  methods: {
    openDeactivate() {
      Swal.fire({
        title: "A jeni i sigurt qe deshironi t'a deaktivizoni llogarine tuaj?",
        text:
          " Sqarim! Me deaktivizimin e llogarise te gjitha te dhenat tuaja do te jene te pa qasshme, te pakten deri ne nje veprim tjeter nga ana juaj.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e02b27",
        cancelButtonColor: "#65b891",
        confirmButtonText: "Po, jam i sigurt",
      }).then((result) => {
        if (result.value) {
          this.deactivateAccount();
        }
      });
    },
    deactivateAccount() {
      this.axios.post("/api/account/deactivate").then((result) => {
        if (result.data.status === "success") {
          Swal.fire("Ju deaktivizuat llogarin me sukses!", "success");
          this.logout();
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
    triggerSidebar() {
      let $ = require("jquery");
      $(document).ready(function () {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    getCities() {
      this.axios.get("/api/get/cities").then((response) => {
        this.cities = response.data.response;
      });
    },
    myProfile() {
      this.axios.get("/api/user").then((res) => {
        this.profile = res.data;
        this.url1 = this.profile.img_path ? this.profile.img_path : "";

        this.form.name = this.profile.name;
        this.form.last_name = this.profile.last_name;
        (this.form.email = this.profile.email),
          (this.form.phone = this.profile.phone),
          (this.form.shop_name = this.profile.shop_name.name),
          (this.form.address = this.profile.address),
          (this.form.description = this.profile.description),
          (this.form.interest_id = this.profile.interest_id),
          (this.form.city = this.profile.city);
      });
      this.loadingall = false;
    },
    getMainCategories() {
      this.axios.get("/api/categories/getParents").then((res) => {
        const { response } = res.data;
        this.categories = response;
      });
    },
    updateProfile() {
      // if (this.$refs.pictureInput.file !== undefined) {
      //   this.files.push(this.$refs.pictureInput.file);
      // }
      this.loadingall = true;
      let formData = new FormData();
      for (let i = 0; i < Object.keys(this.form).length; i++) {
        formData.append(Object.keys(this.form)[i], Object.values(this.form)[i]);
      }
      formData.append("images", this.$refs.pictureInput.file);
      var token = "";
      if (this.profile.role === "shop") {
        token = store.getters["auth/token_posta"];
        formData.append("token", token);
      }
      this.axios
        .post(`/api/update/profile`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((result) => {
          if (result.data.status === "success") {
            // window.location.reload();
          }
          this.loadingall = false;
        });
    },
  },
};
</script>
