<template>
  <div>
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="container">
      <div class="row">
        <!-- <div class="col-md-6 col-12 my-auto">
          <div class="pw-image-cover">
            <img src="./../../../img/strong_password.png" alt="password img" />
          </div>
        </div> -->
        <div class="col-12 col-md-8">
          <div class="custom-form p-0">
            <div class="row">
              <div class="col-12">
                <h2 class="step-title">Ndrysho fjalëkalimin</h2>
              </div>
              <div class="input-group mb-3 col-6">
                <label for="password">Old Password</label>
                <input
                  v-model="old_password"
                  required
                  class="form-control inputs custom-input"
                  type="password"
                  name="password"
                  placeholder="Old password"
                />
              </div>
            </div>
            <div class="row">
              <div class="input-group mb-3 col-6">
                <label for="password">Password</label>
                <input
                  v-model="password"
                  required
                  class="form-control inputs custom-input"
                  type="password"
                  name="password"
                  placeholder="Fjalëkalimi"
                />
              </div>
              <div class="input-group mb-3 col-6">
                <label for="address">Confirm Password</label>
                <input
                  v-model="password_confirmation"
                  class="form-control inputs custom-input"
                  type="password"
                  name="Confirm Password"
                  placeholder="Konfirmo Fjalëkalimin"
                  rows="false"
                />
              </div>
            </div>
            <div class="col-12">
              <pre></pre>
              <button
                type="submit"
                class="btn btn-primary float-right"
                style="margin-top: 1.5rem"
                v-on:click="changePassword()"
              >
                Ndrysho
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Form from "vform";
import axios from "axios";
import FormError from "~/components/FormError.vue";
import swal from "sweetalert2";
import router from "~/router";
import { mapGetters } from "vuex";
import store from "~/store";

import LoginWithFacebook from '~/components/LoginWithFacebook'

export default {
  middleware: "auth",
  // layout: "nosidebar",

  components: {
    LoginWithFacebook,
    FormError,
  },
  computed: mapGetters({
    user: "auth/user",
  }),
  metaInfo() {
    return {
      title: "edit",
    };
  },
  data: () => ({
    password: "",
    password_confirmation: "",
    old_password: "",
    loadingall: false,
  }),
  mounted: function () {},
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function () {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    changePassword() {
      if (this.password == "") {
        swal({
          type: "error",
          text: "Fjalëkalimi nuk mund të jetë i zbrazët",
          reverseButtons: true,
          confirmButtonText: "ok",
        });
      }
      if (this.password !== this.password_confirmation) {
        swal({
          type: "error",
          text: "Fjalëkalimet nuk përputhen",
          reverseButtons: true,
          confirmButtonText: "ok",
        });
        this.password = "";
        this.password_confirmation = "";
        return;
      }
      this.loadingall = true;
      var token = "";
      if (this.user.role === "shop") {
        token = store.getters["auth/token_posta"];
      }
      let changeUserPassword = {
        password: this.password,
        password_confirmation: this.password_confirmation,
        token: token,
      };
      axios
        .patch("/api/settings/password", changeUserPassword)
        .then((response) => {
          if (response.status == 200) {
            swal({
              type: "success",
              text: "Fjalëkalimi u ndryshua me sukses",
              reverseButtons: true,
              confirmButtonText: "ok",
              timer: 2000,
            }).then(() => {
              this.$router.push({ name: "edit-profile" });
            });
          }
        });
      this.loadingall = false;
    },
  },
};
</script>
