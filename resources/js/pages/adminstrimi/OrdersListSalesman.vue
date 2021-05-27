<template>
  <div class="animated fadeIn" style=".main">
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="row mob-sec-header mb-3">
      <div class="container">
        <button v-on:click="triggerSidebar()" id="triside">
          <i class="fa fa-bars"></i> Paneli
        </button>
      </div>
    </div>
    <div class="col-12 p-0">
      <h2 class="step-title">Porositë në proces</h2>
    </div>
    <div class="custom-head-post">
      <form @submit.prevent="getOrdersBySalesman()" class="w-100 custom-form">
        <div class="row w-100 m-0">
          <div class="col-md-2 col-12 col-sm-12 p-0 mr-1">
            <label>Numri i porosis</label>
            <input v-model="order_id" class="form-control custom-input" placeholder="Numri i porosis" />
          </div>
          <div class="col-md-2 col-12 col-sm-12 p-0 mr-1">
            <label>Prej</label>
            <date-picker
              :config="options"
              placeholder="DD-MM-YYYY"
              v-model="from_date"
              @input="dateChange($event, 1)"
              class="custom-input"
            ></date-picker>
          </div>
          <div class="col-md-2 col-12 col-sm-12 p-0 mr-1">
            <label>Deri</label>
            <date-picker
              :config="options"
              placeholder="DD-MM-YYYY"
              v-model="to_date"
              @input="dateChange($event, 0)"
              class="custom-input"
            ></date-picker>
          </div>
          <div class="col-md-3 col-12 col-sm-12 p-0 mr-1">
            <label>Statusi</label>
            <select v-model="status_id" class="form-control custom-input">
              <option value="0">Zgjedh statusin</option>
              <option
                v-for="item in status"
                :value="item.id"
                v-bind:key="item.id"
              >{{item.status_name}}</option>
            </select>
          </div>
          <div class="col-md-2 col-12 float-right p-0 mr-1">
            <label for></label>
            <button type="submit" class="btn btn-primary w-100">Kërko</button>
          </div>
        </div>
      </form>
    </div>

    <table width="100%">
      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>
            <card class="order-card">
              <div class="row">
                <div class="col-12 col-md-4">
                  <p>
                    <b>Numri porosisë :</b>
                    {{order.id }}
                  </p>
                  <p>
                    <b>Emri shitesit :</b>
                    {{order.creator.user_details.name }}
                  </p>
                  <p>
                    <b>Emri blersit :</b>
                    {{order.buyer_name }}
                  </p>
                  <p>
                    <b>Emri porsise:</b>
                    {{order.name }}
                  </p>
                  <p>
                    <b>Posta e zgjedhur :</b>
                    {{order.company_name}}
                  </p>
                </div>
                <div class="col-12 col-md-4">
                  <p v-if="order.price==-1">{{ "Nuk ka qmim"}}</p>
                  <p v-if="order.price>-1">
                    <b>Qmimi :</b>
                    {{order.price +"€"}}
                  </p>
                  <p>
                    <b>Lokacioni i shitesit :</b>
                    {{order.seller_location }}
                  </p>
                  <p>
                    <b>Lokacioni i bleresit :</b>
                    {{order.buyer_location }}
                  </p>
                  <p>
                    <b>Statusi :</b>
                    {{order.status.status_name}}
                  </p>
                </div>
                <div class="col-12 col-md-4" v-if="order.status_id == 1">
                  <button class="btn btn-danger pull-right" @click="declineOrder(order.id)">Anulo</button>
                </div>
                <div
                  :id="'tables-pdf-' + order.id"
                  class="tables-pdf"
                  style="font-size: 12px; width: 100%;"
                >
                  <PdfInvoice v-bind:confirmedOrder="order" />
                  <hr
                    style="width: 100%; border: 1px dashed #000; margin-bottom: 40px; background: transparent;"
                  />
                  <PdfInvoice v-bind:confirmedOrder="order" />
                  <hr
                    style="width: 100%; border: 1px dashed #000; margin-bottom: 40px; background: transparent;"
                  />
                  <PdfInvoice v-bind:confirmedOrder="order" />
                </div>
                <div class="col-12 col-md-12 text-right">
                  <b-button
                    @click="demoFromHTML('tables-pdf-' + order.id, 'fatura-' + order.id )"
                    class="btn btn-add-new"
                  >Shkarko PDF</b-button>
                </div>
              </div>
            </card>
          </td>
        </tr>
      </tbody>
    </table>
    <nav aria-label="Page navigation">
      <ul class="pagination h-100 justify-content-center align-items-center">
        <li v-bind:class="[{disabled:!pagination.prev_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="changePage(2,pagination.current_page)">
            <span class="input-group-addon">
              <!-- <i class="icon-control-rewind"></i> -->
            </span>
            {{$t('previous')}}
          </a>
        </li>

        <li class="page-item disabled">
          <a
            class="page-link text-dark"
            href="#"
          >{{$t('page')}}: {{ pagination.current_page }}{{$t('of')}}{{ pagination.last_page }}</a>
        </li>

        <li v-bind:class="[{disabled:!pagination.next_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="changePage(1,pagination.current_page)">
            {{$t('next')}}
            <span class="input-group-addon">
              <!-- <i class="icon-control-forward"></i> -->
            </span>
          </a>
        </li>
      </ul>
    </nav>
    <!--/.row-->
  </div>
</template>

<script>
import axios from "axios";
import jsPDF from "jspdf";
import moment from "moment";
import Loader from "~/components/Loader";
import PdfInvoice from "~/components/PdfInvoice";
import datePicker from "vue-bootstrap-datetimepicker";
import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
import store from "~/store";

export default {
  name: "dashboard",
  middleware: "auth",
  layout: "default",
  components: {
    PdfInvoice,
    Loader,
    moment,
    datePicker
  },
  data: () => ({
    orders: [],
    selectedDate: 0,
    loadingall: true,
    pagination: {},
    from_date: "",
    to_date: "",
    status: [],
    status_id: 0,
    order_id: "",
    options: {
      format: "DD-MM-YYYY",
      useCurrent: false
    }
  }),
  mounted: function() {
    this.getOrdersBySalesman();
    this.getStatus();
    // this.interval();
  },
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    interval() {
      this.polling = setInterval(() => {
        let page = this.pagination ? this.pagination.current_page : 0;
        this.getOrdersBySalesman("", page);
      }, 240000);
    },
    changePage(status, page) {
      if (status == 1) {
        page = page + 1;
      }
      if (status == 2) {
        page = page - 1;
      }
      this.getOrdersBySalesman(page);
    },
    getStatus() {
      this.loadingall = true;
      const token = store.getters["auth/token_posta"];
      let url = "/api/posta/status?token=" + token;
      axios.get(url).then(response => {
        this.status = response.data.response;
        this.loadingall = false;
      });
    },
    convertDate(date) {
      var status = date + "";
      // var jun = moment(status)
      //   .add(9, "hours")
      //   .format("YYYY-MM-DD hh:mm:ss");
      // return jun;
      return date;
    },
    getOrdersBySalesman(page) {
      this.loadingall = true;
      let order_id = this.order_id;
      let from_date = this.from_date;
      let to_date = this.to_date;
      let status = this.status_id;

      if (!status) {
        status = 0;
      }
      if (!order_id) {
        order_id = 0;
      }
      if (!from_date) {
        from_date = 0;
      }
      if (!to_date) {
        to_date = 0;
      }

      if (!page) {
        page = 0;
      }
      const token = store.getters["auth/token_posta"];

      let data = {
        order_id: order_id,
        from_date: from_date,
        to_date: to_date,
        status: status,
        page: page,
        token: token
      };
      // let page_url =
      //   "/api/posta/orders/salesman?id=" +
      //   order_id +
      //   "&status=" +
      //   status +
      //   "&from_date=" +
      //   from_date +
      //   "&to_date=" +
      //   to_date +
      //   "&page=" +
      //   page;

      let page_url = "/api/posta/orders/salesman";
      axios.post(page_url, data).then(result => {
        this.orders = result.data.response.data;
        this.loadingall = false;
        this.makePagination(
          result.data.response.current_page,
          result.data.response.last_page,
          result.data.response.next_page_url,
          result.data.response.prev_page_url
        );
      });
    },
    makePagination(current_page, last_page, next_page_url, prev_page_url) {
      let pagination = {
        current_page: current_page,
        last_page: last_page,
        next_page_url: next_page_url,
        prev_page_url: prev_page_url
      };

      this.pagination = pagination;
    },
    dateChange(date, from) {
      if (from === 1) {
        this.from_date = date;
      } else {
        this.to_date = date;
      }
    },
    demoFromHTML(id, name) {
      this.$htmlToPaper(id);
    },
    declineOrder(id) {
      this.loadingall = true;

      const token = store.getters["auth/token_posta"];

      let data = {
        id: id,
        token: token
      };
      let page_url = "/api/posta/decline/order";
      axios.post(page_url, data).then(result => {
        let page = this.pagination ? this.pagination.current_page : 0;
        this.getOrdersBySalesman("", page);
        this.loadingall = false;
      });
      this.loadingall = false;
    }
  }
};
</script>
