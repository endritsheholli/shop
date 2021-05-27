<template>
  <main class="main">
    <div class="animated fadeIn">
      <!-- <Loader v-if="loadingall" :position="'fixed'" /> -->

      <div class="row mob-sec-header mb-3">
        <div class="container">
          <button v-on:click="triggerSidebar()" id="triside">
            <i class="fa fa-bars"></i> Paneli
          </button>
        </div>
      </div>

      <div class="row custom-form manual-prod container">
        <form @submit.prevent="createOrder()">
          <div class="container row">
            <div class="col-12 p-0">
              <h2 class="step-title">Shto porosi manuale</h2>
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Emri i produktit</label>
              <input
                v-model="name"
                class="form-control custom-input"
                placeholder="Emri i produktit"
                type="text"
                required
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Çmimi (€)</label>
              <input
                v-model="price"
                class="form-control custom-input"
                placeholder="Çmimi"
                type="number"
                min="0"
                required
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Lokacioni i blerësit</label>
              <input
                v-model="buyerLocation"
                class="form-control custom-input"
                type="text"
                required
                placeholder="Lokacioni i blerësit"
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Numri i telefonit të blerësit</label>
              <input
                pattern="[0-9]{0-15}"
                v-model="buyer_phone"
                type="number"
                required
                min="0"
                max="999999"
                class="form-control custom-input"
                placeholder="Numri i telefonit të blerësit"
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Emri i blerësit</label>
              <input
                v-model="buyer_name"
                type="text"
                required
                class="form-control custom-input"
                placeholder="Emri i blerësit"
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Email i blerësit</label>
              <input
                v-model="buyer_email"
                type="email"
                required
                class="form-control custom-input"
                placeholder="Email i blerësit"
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Pesha e produktit (kg)</label>
              <input
                v-model="weight"
                type="number"
                required
                min="1"
                class="form-control custom-input"
                placeholder="Pesha e produktit"
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Referenca</label>
              <input
                v-model="reference"
                class="form-control custom-input"
                placeholder="Referenca"
                type="text"
                required
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Sasia</label>
              <input
                v-model="quantity"
                class="form-control custom-input"
                placeholder="Sasia"
                type="number"
                min="1"
                required
              />
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Zgjedh qytetin</label>
              <select class="form-control custom-input" name="City Id" v-model="cityId" id>
                <option value="0" selected disabled>Zgjedh qytetin</option>
                <option
                  v-for="(city, index) in cities"
                  :value="city.id"
                  :key="index"
                >{{ city.name }}</option>
              </select>
            </div>
            <div class="col-md-6 col-12 p-0 ">
              <label>Zgjedh posten</label>

              <select class="form-control custom-input" name="City Id" v-model="company_id" id>
                <option value="0" selected disabled>Zgjedh posten</option>
                <option
                  v-for="(company, index) in companies"
                  :value="company.id"
                  :key="index"
                >{{ company.name }}</option>
              </select>
            </div>

            <div class="col-md-6 col-12 p-0  openable-check">
              <span>Mund te hapet?</span>
              <br />
              <input type="radio" id="po" value="1" v-model="openable" />
              <span class="checkmark-radio"></span>
              <label for="po">Po</label>
              <br />
              <input type="radio" id="jo" value="0" v-model="openable" />
              <span class="checkmark-radio"></span>
              <label for="jo">Jo</label>
              <br />

              <span>Mund te kthehet?</span>
              <br />
              <input type="radio" id="po" value="1" v-model="reversible" />
              <span class="checkmark-radio"></span>
              <label for="po">Po</label>
              <br />
              <input type="radio" id="jo" value="0" v-model="reversible" />
              <span class="checkmark-radio"></span>
              <label for="jo">Jo</label>
              <br />
            </div>
          </div>
          <div class="row">
            <div class="col-12 p-0">
              <div class="col-md-2" style="margin-top: 25px;">
                <button type="submit" class="btn btn-primary w-100 mb-3">Krijo</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
</template>

<script>
import Callout from "~/components/Callout";
import axios from "axios";
import dropdown from "vue-dropdowns";
import { mapGetters } from "vuex";
import Loader from "~/components/Loader";
import store from "~/store";

export default {
  name: "postmanslistorg",
  middleware: "auth",
  layout: "default",
  components: {
    Loader,
    Callout,
    dropdown
  },
  computed: mapGetters({
    user: "auth/user"
  }),
  data: () => ({
    qytetiSelektuar: 0,
    name: null,
    price: null,
    sellerLocation: null,
    buyerLocation: null,
    cityId: null,
    cities: [],
    companies: [],
    buyer_phone: "",
    buyer_name: "",
    buyer_email: "",
    openable: 0,
    weight: "",
    reversible: 0,
    quantity: "",
    reference: "",
    btn: false,
    company_id: 0,
    loadingall: true
  }),
  mounted: function() {
    this.getCreator();
    this.getCities();
    this.getCompanies();
  },
  methods: {
     triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    createOrder() {
      if (!this.cityId) {
        this.showAlert("Qyteti eshte obligativ");
      } else if (!this.company_id) {
        this.showAlert("Posta eshte obligative");
      } else if (
        !this.buyer_phone ||
        this.buyer_phone.length < 1 ||
        !/^\d+$/.test(this.buyer_phone)
      ) {
        this.showAlert(
          "Telefoni bleresit eshte obligativ dhe duhet te jete valid"
        );
      } else if (!this.buyer_name || this.buyer_name.length < 1) {
        this.showAlert("Emri bleresit eshte obligativ");
      } else if (!this.weight || this.weight.length < 1) {
        this.showAlert("Pesha eshte obligative dhe duhet te jete valide");
      } else if (
        !this.quantity ||
        this.quantity.length < 1 ||
        !/^\d+$/.test(this.quantity)
      ) {
        this.showAlert("Sasia eshte obligative dhe duhet te jete valide");
      } else {
        const token = store.getters["auth/token_posta"];
        this.loadingall = true;
        this.btn = true;
        axios
          .post("/api/posta/create/order", {
            name: this.name,
            price: this.price,
            seller_location: this.sellerLocation,
            buyer_location: this.buyerLocation,
            buyer_email: this.buyer_email,
            city_id: this.cityId,
            buyer_phone: this.buyer_phone,
            buyer_name: this.buyer_name,
            openable: this.openable,
            weight: this.weight,
            reversible: this.reversible,
            quantity: this.quantity,
            reference: this.reference,
            token: token,
            company_id: this.company_id
          })
          .then(response => {
            if (response.data.status === "failed") {
              this.showAlert("Diqka shkoj keq!");
            } else {
              this.showAlert("Porosia u krijua me sukses!");
              location.reload();
            }
            this.btn = false;
            this.loadingall = false;
          });
      }
    },
    getCities() {
      this.loadingall = true;
      axios.get("/api/get/cities").then(response => {
        this.cities = response.data.response;
        this.loadingall = false;
      });
    },
    getCompanies() {
      this.loadingall = true;
      axios.get("/api/posta/company").then(response => {
        this.companies = response.data.response;
        this.loadingall = false;
      });
    },
    getCreator() {
      this.loadingall = true;
      var token = store.getters["auth/token_posta"];
      axios.get(`/api/posta/user?token=` + token).then(response => {
        this.sellerLocation =
          "" +
          response.data.response.address +
          (response.data.response.city_name
            ? " ," + response.data.response.city_name
            : "");
        this.loadingall = false;
      });
    },
    nameChanged(message) {
      this.name = message;
    },
    priceChanged(message) {
      this.price = message;
    },
    sellerLocationChanged(message) {
      this.sellerLocation = message;
    },
    buyerLocationChanged(message) {
      this.buyerLocation = message;
    },
    showAlert(msg) {
      alert(msg);
    },
    onChange(event) {
      this.cityId = event.target.value;
    }
  }
};
</script>
