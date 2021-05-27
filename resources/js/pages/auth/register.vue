<template>
  <div>
    <div class="container">
      <div class="row">
        <div id="errAlert" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <h3>{{ errMsg }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-10 offset-md-1">
          <div class="mb-6" />
          <div class="row mb-3">
            <div class="col-6 col-12">
              <div class="navigation_tab">
                <div class="d-flex">
                  <label>Regjistrohu</label>
                </div>
                <button
                  :class="{ active: changeForm === false }"
                  type="submit"
                  class="btn tab_navigation"
                  @click="change(1)"
                >
                  User
                </button>
                <button
                  :class="{ active: changeForm === true }"
                  type="submit"
                  class="btn tab_navigation"
                  @click="change(0)"
                >
                  Trader
                </button>
              </div>
            </div>
          </div>
          <form
            id="step-1"
            class="row reg-user"
            @submit.prevent="register"
            @keydown="form.onKeydown($event)"
          >
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="name">Emri</label>
              <input
                v-model="form.name"
                required
                class="form-control inputs"
                type="text"
                name="name"
                placeholder="Emri"
              />
              <has-error :form="form" field="name" />
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="last_name">Mbiemri</label>
              <input
                v-model="form.last_name"
                class="form-control inputs"
                type="text"
                name="last_name"
                placeholder="Mbiemri"
              />
              <has-error :form="form" field="last_name" />
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="email">E-Mail</label>
              <input
                v-model="form.email"
                required
                class="form-control inputs"
                type="email"
                name="email"
                placeholder="E-Mail"
              />
              <has-error :form="form" field="email" />
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="password">Fjalëkalimi</label>
              <input
                v-model="form.password"
                required
                class="form-control inputs"
                type="password"
                name="password"
                placeholder="Fjalëkalimi"
              />
              <has-error :form="form" field="password" />
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="password_confirmation">Konfirmo Fjalëkalimi</label>
              <input
                v-model="form.password_confirmation"
                required
                class="form-control inputs"
                type="password"
                name="password_confirmation"
                placeholder="Konfirmo fjalëkalimi"
              />
              <has-error :form="form" field="password_confirmation" />
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
              <label for="phone">Nr. i Telefoni</label>
              <input
                v-model="form.phone"
                required
                class="form-control inputs"
                type="text"
                name="phone"
                placeholder="Numri i telefonit"
              />
              <has-error :form="form" field="phone" />
            </div>
            <div class="input-group col-12 col-md-6 mb-3">
              <label for="city">Qyteti</label>
              <b-form-select
                :required="form.city_id == '0'"
                v-model="form.city_id"
                class="form-control"
              >
                <option value="0">Zgjedh qytetin</option>
                <option
                  required
                  v-for="(city, index) in cities"
                  :value="city.id"
                  :key="index"
                >
                  {{ city.name }}
                </option>
              </b-form-select>
              <!-- <has-error :form="errors" field="city id" /> -->
            </div>
            <div class="input-group col-12 col-md-6">
              <label for="address">Adresa</label>
              <input
                v-model="form.address"
                class="form-control inputs"
                name="address"
                placeholder="Adresa"
                rows="false"
              />
              <has-error :form="form" field="address" />
            </div>
            <!-- Pjesa per shop -->
            <div class="col-12">
              <div
                v-if="changeForm === true"
                class="input-group mb-3 col-12 col-md-6 p-0"
              >
                <label for="shop_name">Emri i Marketit</label>
                <input
                  v-if="changeForm"
                  v-model="form.shop_name"
                  class="form-control inputs"
                  type="text"
                  name="shop_name"
                  placeholder="Emri i shopit"
                />
                <has-error :form="form" field="shop_name" />
              </div>
            </div>
            <!-- Perfundimi i pjeses se shopit -->
            <div class="col-md-6 col-12 mt-3">
              <button type="submit" class="btn btn-primary w-100">
                Regjistrohu
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="mb-6" />
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import swal from "sweetalert2";
import LoginWithFacebook from "~/components/LoginWithFacebook";

export default {
  middleware: "guest",
  layout: "nosidebar",

  components: {
    LoginWithFacebook,
  },

  metaInfo() {
    return {
      title: this.$t("register"),
    };
  },

  data: () => ({
    categories: [],
    changeForm: false,
    cities: [],
    errMsg: "",
    form: new Form({
      name: "",
      last_name: "",
      email: "",
      city: 0,
      password: "",
      password_confirmation: "",
      shop_name: "",
      phone: "",
      address: "",
      interest_id: 0,
      country_id: "",
      city_id: 0,
      role: "client",
      cities: [],
      // errors: []
    }),
  }),
  mounted: function () {
    this.getMainCategories();
    this.getCities();
  },
  methods: {
    change(item) {
      if (item == 1) {
        this.changeForm = false;
        this.form.role = "client";
      } else {
        this.changeForm = true;
        this.form.role = "shop";
      }
    },
    getMainCategories() {
      this.axios.get("/api/categories/getParents").then((res) => {
        const { response } = res.data;
        this.categories = response;
      });
    },
    async register() {
      let $ = require("jquery");
      if (this.form.city_id === 0) {
        this.errMsg = "Ju lutem zgjedheni qytetin";
        $("#errAlert").modal("show");
        setTimeout(function () {
          $("#errAlert").modal("hide");
        }, 3000);
        return;
      }
      if (this.form.shop_name === "" && this.form.role === "shop") {
        this.errMsg = "Ju lutem zgjedheni nje emer per market";
        $("#errAlert").modal("show");
        setTimeout(function () {
          $("#errAlert").modal("hide");
        }, 3000);
        return;
      }
      let response = await fetch("/api/register", {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
        body: JSON.stringify(this.form),
      });

      let result = await response.json();

      if (response.status != 200 && response.status != 201) {
        if (result && result.message) {
          swal({
            type: "error",
            title: result.message,
            reverseButtons: true,
            confirmButtonText: "Ok",
          });
        }
        return;
      }
      const data = await result;
      // Log in the user.
      const {
        data: { token, role },
      } = await this.form.post("/api/login");

      // Save the token.
      await this.$store.dispatch("auth/saveToken", {
        token,
      });

      // Update the user.
      await this.$store.dispatch("auth/updateUser", {
        user: data,
      });

      if (role === "shop") {
        await this.getAuthFromPosta();
      }
      // Redirect home.
      this.$router.push({
        name: "home",
      });
      // location.reload();
    },
    async getAuthFromPosta() {
      const { data } = await this.form.post("/api/posta/login");
      await this.$store.dispatch("auth/saveTokenPosta", {
        token: data.response.token,
        remember: this.remember,
      });
    },
    getCities() {
      this.loadingall = true;
      axios.get("/api/get/cities").then((response) => {
        this.cities = response.data.response;
        this.loadingall = false;
      });
    },
  },
};
</script>
