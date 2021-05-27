<template>
  <div class="small-container">
    <div class="row">
      <div class="col-md-12">
        <h3>Login</h3>
        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
          <div class="row">
            <div class="form-group col-md-12">
              <label for="email">
                Email
                <span class="required">*</span>
              </label>
              <input
                v-model="form.email"
                required
                :class="{ 'is-invalid': form.errors.has('email') }"
                class="form-control"
                type="email"
                name="email"
                placeholder="email"
              />
              <has-error :form="form" field="email" />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-12">
              <label for="password">
                Fjalekalimi
                <span class="required">*</span>
              </label>
              <input
                v-model="form.password"
                required
                :class="{ 'is-invalid': form.errors.has('password') }"
                class="form-control"
                type="password"
                name="password"
                placeholder="fjalekalimi"
              />
              <has-error :form="form" field="password" />
              <div class="col-12 m-0 p-0">
                <router-link
                  :to="{ name: 'password.request' }"
                  class="small ml-auto my-auto"
                  >Keni harruar fjalekalimin ?</router-link
                >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <v-button
                v-if="isLoading"
                class="orangebtn pull-left"
                style="opacity: 0.69; pointer-events: none"
                >Kyçu</v-button
              >
              <v-button
                v-if="!isLoading"
                :loading="isLoading"
                :disabled="isLoading"
                class="orangebtn pull-left"
                >Kyçu</v-button
              >
            </div>
            <!-- <div class="col-12">
              <LoginWithFacebook />
            </div> -->
          </div>
        </form>
      </div>
    </div>
    <div class="col-12 p-0">
      <div class="not-a-member">
        <p>Nuk keni ende llogari ?</p>
        <button @click="registerRoute()" class="btn text-btn">
          Regjistrohu Tani
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import swal from "sweetalert2";

import LoginWithFacebook from "~/components/LoginWithFacebook";

export default {
  middleware: "guest",
  layout: "basic",
  name: "events-form",
  components: {
    LoginWithFacebook,
  },
  metaInfo() {
    return {
      title: "Kyçu",
    };
  },

  data: () => ({
    form: new Form({
      email: "",
      password: "",
    }),
    isLoading: false,
    remember: false,
    failed_login: false,
  }),
  
  methods: {
    registerRoute() {
      this.$bvModal.hide("login_modal");
      this.$bvModal.show("register_modal");
    },
    async login() {
      this.isLoading = true;
      try {
        this.failed_login = false;
        this.isLoading = true;
        const { data } = await this.form.post("/api/login");
        if (data && data.message) {
          swal({
            type: "error",
            title: data.message,
            reverseButtons: true,
            confirmButtonText: "Ok",
          });
          return;
        }
        this.$store.dispatch("auth/saveToken", {
          token: data.token,
          remember: this.remember,
        });
        if (data.role === "shop") {
          await this.getAuthFromPosta();
        }

        await this.$store.dispatch("auth/fetchUser");

        if (data.role == "client") {
          this.$bvModal.hide("login_modal");
          this.$router.push({
            name: "ballina",
          });
        } else {
          if (data.role == "shop") {
            this.$router.push({
              name: "orders",
            });
          } else {
            this.$router.push({
              name: "dashboard",
            });
          }
        }
        this.isLoading = false;
      } catch (e) {
        this.failed_login = true;
        this.isLoading = false;
      }
    },
    async getAuthFromPosta() {
      const { data } = await this.form.post("/api/posta/login");
      await this.$store.dispatch("auth/saveTokenPosta", {
        token: data.response.token,
        remember: this.remember,
      });
    },
  },
};
</script>
