<template>
  <main class="main">
    <div class="animated fadeIn">
      <Loader v-if="loadingall" :position="'fixed'" />
      <div class="row">
        <div class="col-12">
          <h2 class="step-title mb-3">Raporti</h2>
        </div>
        <div class="mob-sec-header" style="overflow: hidden">
          <div class="container">
            <button v-on:click="triggerSidebar()" id="triside">
              <i class="fa fa-bars"></i> Paneli
            </button>
          </div>
        </div>
      </div>
      <div class="custom-head-post2">
        <div class="w-100">
          <form @submit.prevent="getOrdersBySalesman()" class="custom-form m-0">
            <div class="row raport-input">
              <div class="col-12 col-md-2 raport-input-content">
                <p>Duke filluar nga data:</p>
                <date-picker
                  :config="options"
                  placeholder="DD-MM-YYYY"
                  v-model="date"
                  @input="dateChange($event, 1)"
                  class="custom-input"
                ></date-picker>
              </div>
              <div class="col-12 col-md-2 raport-input-content">
                <p>Deri ne daten:</p>
                <date-picker
                  :config="options"
                  placeholder="DD-MM-YYYY"
                  v-model="endDate"
                  @input="dateChange($event, 0)"
                  class="custom-input"
                ></date-picker>
              </div>
              <div class="col-12 col-md-2 raport-input-content">
                <p>Statusi:</p>
                <select v-model="status_id" class="form-control custom-input">
                  <option selected value="0">Zgjedh statusin:</option>
                  <option
                    v-for="item in status"
                    :value="item.id"
                    v-bind:key="item.id"
                  >{{item.status_name}}</option>
                </select>
              </div>
              <div class="col-12 col-md-2 raport-input-content">
                <pre></pre>
                <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem">Kërko</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <h3 class="post-title">
        Te gjitha porosite:
        <span v-if="statusName !== ''">({{statusName}})</span>
        {{orders.length}}
        <span v-if="totaliQePosedon !== ''">({{totaliQePosedon}} €)</span>
      </h3>
      <div class="col-12 p-0">
        <table class="rwd-table">
          <tbody>
            <tr class="tr_table">
              <th>Numri i porosise</th>
              <th>Emri</th>
              <th>Pesha</th>
              <th>Cmimi</th>
              <th>Shitësi</th>
              <th>Postieri</th>
              <th>Data e porosis</th>
              <th>Qyteti</th>
              <th>Statusi</th>
            </tr>
            <tr v-if="orders.length == 0">
              <td style="text-align: left;">
                <h2>
                  <strong>Nuk ka porosi!</strong>
                </h2>
              </td>
            </tr>
            <tr
              v-for="(order) in orders"
              :key="order.order_id"
               class="tr_table_2"
                         :style="order.status_id == 1 ? 'background-color: #8FBECC': order.status_id == 6 ? 'background-color: #FF6666' : order.status_id == 7 ? 'background-color: #FF6666' : order.status_id == 3 ? 'background-color: #D0CAE6' : order.status_id == 2 ? 'background-color: #FFE673' : order.status_id == 5 ? 'background-color: #14638847' : order.status_id == 4 ? 'background-color: #BDFBBB' : 'background-color: #EAEAEA'  "
            >
              <td data-th="Numri i porosise">{{ order.order_id}}</td>
              <td data-th="Emri">{{ order.name}}</td>
              <td data-th="Pesha">{{ order.weight}} kg</td>
              <td data-th="Cmimi">{{ order.price }} &euro;</td>
              <td data-th="Shitësi">{{ order.creator ? order.creator.user_details.name : '--' }}</td>
              <td data-th="Postieri">{{ order.postman ? order.postman.user_details.name : '--' }}</td>
              <td data-th="Data e porosisë">{{ order.created_at.split(' ')[0] | formatDate}}</td>
              <td data-th="Qyteti">{{ order.city_name }}</td>
              <td data-th="Statusi">{{order.status.status_name}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</template>

<script>
import Callout from "~/components/Callout";
import axios from "axios";
import dropdown from "vue-dropdowns";
import Loader from "~/components/Loader";
import Vue from "vue";
import moment from "moment";
import store from "~/store";
// import VueMoment from "vue-moment";
import datePicker from "vue-bootstrap-datetimepicker";
import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";

export default {
  name: "postmanslistorg",
  middleware: "auth",
  layout: "default",
  components: {
    Loader,
    Callout,
    dropdown,
    moment,
    datePicker
  },
  data: () => ({
    postmans: [],
    orders: [],
    porosiTeDorezuara: null,
    numriPorosive: null,
    porositeNeDepo: null,
    porositeNeProces: null,
    porositeAnuluara: null,
    porositeTeShitesi: null,
    postmanSelected: 0,
    totali: 0,
    totaliQePosedon: 0,
    totaliProces: 0,
    totaliDepo: 0,
    totaliAnuluara: 0,
    totaliShitesi: 0,
    loadingall: true,
    selectedDate: 0,
    status_id: 0,
    status: [],
    statusName: "",
    // date: moment(new Date()).format("DD-MM-YYYY"),
    date: "",
    endDate: "",
    selectedEndDate: 0,
    options: {
      format: "DD-MM-YYYY",
      useCurrent: false
    }
  }),
  mounted: function() {
    this.urlData();
    this.getOrdersBySalesman();
    Vue.filter("formatDate", function(value) {
      if (value) {
        return moment(String(value)).format("DD/MM/YYYY");
      }
    });
    this.getStatus();
  },
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    getStatus() {
      this.loadingall = true;
      axios.get("/api/posta/status").then(response => {
        this.status = response.data.response;
        this.loadingall = false;
      });
    },
    urlData() {
      let query = this.$router.history.current.query;
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, "0");
      var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + "-" + mm + "-" + yyyy;

      if (this.date == "") {
        if (query.date !== "") {
          this.date = query.date ? query.date : "";
        } else {
          this.date = today;
        }
      }
      if (this.status_id == "") {
        this.status_id = this.status_id == 0 ? query.status : this.status_id;
      }
    },
    getOrdersBySalesman() {
      this.loadingall = true;
      this.urlData();
      this.getStatusName(this.status_id);
      this.porosiTeDorezuara = 0;
      this.numriPorosive = 0;
      this.porositeNeDepo = 0;
      this.porositeNeProces = 0;
      this.porositeAnuluara = 0;
      this.porositeTeShitesi = 0;
      this.totali = 0;
      this.totaliQePosedon = 0;
      this.totaliProces = 0;
      this.totaliDepo = 0;
      this.totaliAnuluara = 0;
      this.totaliShitesi = 0;
      let date = this.date;
      let endDate = this.endDate;
      let status_id = this.status_id;
      if (!date) {
        date = 0;
      }
      if (!endDate) {
        endDate = 0;
      }
      if (!status_id) {
        status_id = 0;
      }
      const token = store.getters["auth/token_posta"];

      let data = {
        token: token,
        date: date,
        endDate: endDate,
        status: status_id
      };
      axios;
      axios.post("/api/posta/raport/salesman", data).then(response => {
        this.numriPorosive = response.data.response.length;
        this.orders = response.data.response;
        for (var i = 0; i < response.data.response.length; i++) {
          this.totaliQePosedon += parseFloat(
            Number(response.data.response[i].price).toFixed(2)
          );

          if (response.data.response[i].status_id == 4) {
            this.porosiTeDorezuara++;
            this.totali += parseFloat(
              Number(response.data.response[i].price).toFixed(2)
            );
          }
          if (response.data.response[i].status_id == 3) {
            this.porositeNeDepo++;
            this.totaliDepo += parseFloat(
              Number(response.data.response[i].price).toFixed(2)
            );
          }
          if (
            response.data.response[i].status_id == 2 ||
            response.data.response[i].status_id == 5
          ) {
            this.porositeNeProces++;
            this.totaliProces += parseFloat(
              Number(response.data.response[i].price).toFixed(2)
            );
          }
          if (
            response.data.response[i].status_id == 6 ||
            response.data.response[i].status_id == 7
          ) {
            this.porositeAnuluara++;
            this.totaliAnuluara += parseFloat(
              Number(response.data.response[i].price).toFixed(2)
            );
          }
          if (response.data.response[i].status_id == 1) {
            this.porositeTeShitesi++;
            this.totaliShitesi += parseFloat(
              Number(response.data.response[i].price).toFixed(2)
            );
          }
        }
        this.totaliQePosedon = parseFloat(
          Number(this.totaliQePosedon).toFixed(2)
        );
        this.totali = parseFloat(Number(this.totali).toFixed(2));
        this.totaliProces = parseFloat(Number(this.totaliProces).toFixed(2));
        this.totaliDepo = parseFloat(Number(this.totaliDepo).toFixed(2));
        this.totaliAnuluara = parseFloat(
          Number(this.totaliAnuluara).toFixed(2)
        );
        this.totaliShitesi = parseFloat(Number(this.totaliShitesi).toFixed(2));
        // this.makePagination(

        this.loadingall = false;
      });
    },
    dateChange(date, from) {
      if (from == 1) {
        this.date = date;
      } else {
        this.endDate = date;
      }
    },
    getStatusName(status_id) {
      let name = status_id;
      if (name == 0) {
        this.statusName = "";
      }
      if (name == 1) {
        this.statusName = "Te Shitesi";
      }
      if (name == 2) {
        this.statusName = "Marrur nga shitorja";
      }
      if (name == 3) {
        this.statusName = "Ne Depo";
      }
      if (name == 4) {
        this.statusName = "E Dorzuar";
      }
      if (name == 5) {
        this.statusName = "Rruges per tek klienti";
      }
      if (name == 6) {
        this.statusName = "E Anuluar";
      }
      if (name == 7) {
        this.statusName = "E Anuluar nga shitesi";
      }
      if (name == 8) {
        this.statusName = "E Kthyer tek shitesi";
      }
    }
  }
};
</script>